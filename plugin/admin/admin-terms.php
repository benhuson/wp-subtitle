<?php

/**
 * @package     WP Subtitle
 * @subpackage  Admin Terms
 */

add_action( 'plugins_loaded', array( 'WPSubtitle_Admin_Terms', 'setup_hooks' ) );

class WPSubtitle_Admin_Terms {

	/**
	 * Setup Hooks
	 */
	public static function setup_hooks() {

		add_action( 'admin_init', array( get_class(), 'add_admin_fields' ) );

		$taxonomies = self::get_supported_taxonomies();

		foreach ( $taxonomies as $taxonomy ) {
			add_filter( 'manage_edit-' . $taxonomy . '_columns', array( get_class(), 'taxonomy_columns' ) );
			add_filter( 'manage_' . $taxonomy . '_custom_column', array( get_class(), 'term_row' ), 15, 3 );
			add_action( 'create_' . $taxonomy, array( get_class(), 'update_term_meta' ), 10 );
			add_action( 'edited_' . $taxonomy, array( get_class(), 'update_term_meta' ), 10 );
		}

	}

	/**
	 * Edit Taxonomy Columns
	 *
	 * @param   array  A list of columns.
	 * @return  array  List of columns with "Subtitle" inserted after the title.
	 *
	 * @internal  Private. Called via the `manage_edit-{$taxonomy}_columns` filter.
	 */
	public function taxonomy_columns( $original_columns ) {

		$new_columns = array();

		foreach ( $original_columns as $key => $value ) {

			$new_columns[ $key ] = $value;

			if ( 'name' == $key ) {
				$new_columns['subtitle'] = esc_html__( 'Subtitle', 'wp-subtitle' );
			}

		}

		return $new_columns;

	}

	/**
	 * Edit Term Row
	 *
	 * @param   string   Row.
	 * @param   string   Name of the current column.
	 * @param   integer  Term ID.
	 * @return  string   HTML display.
	 *
	 * @internal  Private.  Called via the `manage_{$taxonomy}_custom_column` filter.
	 */
	public function term_row( $row, $column_name, $term_id ) {

		if ( 'subtitle' === $column_name ) {

			$subtitle = new WP_Subtitle_Term( $term_id );

			return $row . esc_html( $subtitle->get_meta_value() );

		}

		return $row;

	}

	/**
	 * Add Admin Fields
	 *
	 * @internal  Private. Called via the `admin_init` action.
	 */
	public static function add_admin_fields() {

		$taxonomies = self::get_supported_taxonomies();

		foreach ( $taxonomies as $taxonomy ) {
			add_action( $taxonomy . '_add_form_fields', array( get_class(), 'add_form' ) );
			add_action( $taxonomy . '_edit_form_fields', array( get_class(), 'edit_form' ), 30, 2 );
		}

	}

	/**
	 * Add Term Form
	 *
	 * Create image control for `wp-admin/term.php`.
	 *
	 * @param  string   Taxonomy slug.
	 *
	 * @internal  Private. Called via the `{$taxonomy}_add_form_fields` action.
	 */
	public static function add_form( $taxonomy ) {

		?>
		<div class="form-field term-wps-subtitle-wrap">
			<label for="wps_subtitle"><?php esc_html_e( 'Subtitle', 'wp-subtitle' ); ?></label>
			<input name="wps_subtitle" id="wps_subtitle" type="text" value="" size="40">
		</div>
		<?php

	}

	/**
	 * Edit Term Form
	 *
	 * Create image control for `wp-admin/term.php`.
	 *
	 * @param  WP_Term  Term object.
	 * @param  string   Taxonomy slug.
	 *
	 * @internal  Private. Called via the `{$taxonomy}_edit_form_fields` action.
	 */
	public static function edit_form( $term, $taxonomy ) {

		$term_subtitle = new WP_Subtitle_Term( $term );

		?>
		<tr class="form-field term-wps-subtitle-wrap">
			<th scope="row"><label for="wps_subtitle"><?php esc_html_e( 'Subtitle', 'wp-subtitle' ); ?></label></th>
			<td><input name="wps_subtitle" id="wps_subtitle" type="text" value="<?php echo esc_attr( $term_subtitle->get_meta_value() ); ?>" size="40"></td>
		</tr>
		<?php

	}

	/**
	 * Update Term Meta
	 *
	 * @param  integer  $term_id  Term ID.
	 *
	 * @internal  Private. Called via the `edited_{$taxonomy}` action.
	 */
	public static function update_term_meta( $term_id ) {

		$term_subtitle = new WP_Subtitle_Term( $term_id );

		if ( ! $term_subtitle->current_user_can_edit() ) {
			return;
		}

		if ( isset( $_POST[ 'wps_subtitle' ] ) ) {
			$term_subtitle->update_subtitle( $_POST[ 'wps_subtitle' ] );
		}

	}

	/**
	 * Get Supported Taxonomies
	 *
	 * @return  array
	 */
	private static function get_supported_taxonomies() {

		return apply_filters( 'plugins/wp_subtitle/supported_taxonomies', array( 'category' ) );

	}

}
