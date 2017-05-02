<?php

/**
 * @package     WP Subtitle
 * @subpackage  Admin
 */

add_action( 'plugins_loaded', array( 'WPSubtitle_Admin', '_setup' ) );

class WPSubtitle_Admin {

	/**
	 * Setup
	 *
	 * @since  2.2
	 * @internal
	 */
	public static function _setup() {

		// Language
		load_plugin_textdomain( 'wp-subtitle', false, dirname( WPSUBTITLE_BASENAME ) . '/languages' );

		add_action( 'admin_init', array( 'WPSubtitle_Admin', '_admin_init' ) );
		add_action( 'post_updated', array( 'WPSubtitle_Admin', '_save_post' ), 9 );
		add_action( 'save_post', array( 'WPSubtitle_Admin', '_save_post' ) );
		add_action( 'admin_enqueue_scripts', array( 'WPSubtitle_Admin', '_add_admin_scripts' ) );

		add_filter( '_wp_post_revision_fields', array( 'WPSubtitle_Admin', '_wp_post_revision_fields' ), 9 );
		add_action( 'wp_restore_post_revision', array( 'WPSubtitle_Admin', 'wp_restore_post_revision' ), 10, 2 );

	}

	/**
	 * Admin Init
	 *
	 * @since  2.3
	 * @internal
	 */
	public static function _admin_init() {

		global $pagenow;

		// Get post type
		$post_type = '';

		if ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = sanitize_text_field( $_REQUEST['post_type'] );
		} elseif ( isset( $_GET['post'] ) ) {
			$post_type = get_post_type( absint( $_GET['post'] ) );
		} elseif ( in_array( $pagenow, array( 'post-new.php', 'edit.php' ) ) ) {
			$post_type = 'post';
		}

		// Setup Field / Meta Box
		if ( WPSubtitle::is_supported_post_type( $post_type ) ) {
			if ( self::edit_form_after_title_supported( $post_type ) ) {
				add_action( 'admin_head', array( 'WPSubtitle_Admin', '_add_admin_styles' ) );
				add_action( 'edit_form_after_title', array( 'WPSubtitle_Admin', '_add_subtitle_field' ) );
			} else {
				add_action( 'add_meta_boxes', array( 'WPSubtitle_Admin', '_add_meta_boxes' ) );
			}

			add_filter( 'manage_edit-' . $post_type . '_columns', array( 'WPSubtitle_Admin', 'manage_subtitle_columns' ) );
			add_action( 'manage_' . $post_type . '_posts_custom_column', array( 'WPSubtitle_Admin', 'manage_subtitle_columns_content' ), 10, 2 );
			add_action( 'quick_edit_custom_box', array( 'WPSubtitle_Admin', 'quick_edit_custom_box' ), 10, 2 );
		}

	}

	/**
	 * Add subtitle input to quick edit.
	 *
	 * @since  2.6
	 *
	 * @uses  add_action( 'quick_edit_custom_box' )
	 *
	 * @param  string  $column_name  Column name.
	 * @param  string  $post_type 	 Post type
	 */
	public static function quick_edit_custom_box( $column_name, $post_type ) {

		if ( $column_name !== 'wps_subtitle' ) {
			return;
		}

		wp_nonce_field( 'wp-subtitle', 'wps_noncename' );

		?>
		<fieldset class="inline-edit-col-left inline-edit-col-left-wps-subtitle">
			<div class="inline-edit-col column-<?php echo $column_name; ?>">
				<label>
					<span class="title"><?php esc_html_e( 'Subtitle', 'wp-subtitle' ); ?></span>
					<span class="input-text-wrap"><input type="text" name="wps_subtitle" class="wps_subtitle" value=""></span>
				</label>
			</div>
		</fieldset>
		<?php

	}

	/**
	 * Add subtitle admin column.
	 *
	 * @since  2.4
	 *
	 * @param   array  $columns  A columns
	 * @return  array            Updated columns.
	 */
	public static function manage_subtitle_columns( $columns ) {

		$new_columns = array();

		foreach ( $columns as $column => $value ) {
			$new_columns[ $column ] = $value;
			if ( 'title' == $column ) {
				$new_columns['wps_subtitle'] = __( 'Subtitle', 'wp-subtitle' );
			}
		}

		return $new_columns;

	}

	/**
	 * Display subtitle column.
	 *
	 * @since  2.4
	 *
	 * @param  string  $column_name  Column name.
	 * @param  int     $post_id      Post ID
	 */
	public static function manage_subtitle_columns_content( $column_name, $post_id ) {

		if ( $column_name == 'wps_subtitle' ) {

			$subtitle = new WP_Subtitle( $post_id );
			echo '<span data-wps_subtitle="' . esc_attr( $subtitle->get_subtitle() ) . '">' . esc_html( $subtitle->get_subtitle() ) . '</span>';

		}

	}

	/**
	 * Add Admin scripts.
	 *
	 * @since  2.6
	 * @internal
	 */
	public static function _add_admin_scripts( $hook ) {

		if ( 'edit.php' != $hook ) {
			return;
		}

		wp_enqueue_script( 'wps_subtitle', plugins_url( 'js/admin-edit.js', __FILE__ ), false, null, true );

	}

	/**
	 * Add `wps_subtitle` to post revision fields.
	 *
	 * @since     2.9
	 * @internal
	 *
	 * @param  array  $fields  Revision fields.
	 */
	public static function _wp_post_revision_fields( $fields ) {

		$fields['wps_subtitle'] = __( 'Subtitle', 'wp-subtitle' );

		return $fields;

	}

	/**
	 * Restore revisioned `wps_subtitle` value to post.
	 *
	 * @since  2.9
	 *
	 * @param  int  $post_id      Post ID.
	 * @param  int  $revision_id  Revision ID.
	 */
	public static function wp_restore_post_revision( $post_id, $revision_id ) {

		$subtitle = new WP_Subtitle( $post_id );
		$subtitle->restore_post_revision( $revision_id );

	}

	/**
	 * Add Admin Styles
	 *
	 * @since  2.2
	 * @internal
	 */
	public static function _add_admin_styles() {
		?>
		<style>
		#subtitlediv.top {
			margin-top: 5px;
			margin-bottom: 15px;
			position: relative;
		}
		#subtitlediv.top #subtitlewrap {
			border: 0;
			padding: 0;
		}
		#subtitlediv.top #wpsubtitle {
			background-color: #fff;
			font-size: 1.4em;
			line-height: 1em;
			margin: 0;
			outline: 0;
			padding: 3px 8px;
			width: 100%;
			height: 1.7em;
		}
		#subtitlediv.top #wpsubtitle::-webkit-input-placeholder { padding-top: 3px; }
		#subtitlediv.top #wpsubtitle:-moz-placeholder { padding-top: 3px; }
		#subtitlediv.top #wpsubtitle::-moz-placeholder { padding-top: 3px; }
		#subtitlediv.top #wpsubtitle:-ms-input-placeholder { padding-top: 3px; }
		#subtitlediv.top #subtitledescription {
			margin: 5px 10px 0 10px;
		}
		.quick-edit-row-post .inline-edit-col-left-wps-subtitle {
			clear: left;
		}
		</style>
		<?php
	}

	/**
	 * Get Meta Box Title
	 *
	 * @since  2.2
	 *
	 * @uses  apply_filters( 'wps_meta_box_title' )
	 */
	public static function get_meta_box_title( $post_type ) {
		return apply_filters( 'wps_meta_box_title', __( 'Subtitle', 'wp-subtitle' ), $post_type );
	}

	/**
	 * Add Meta Boxes
	 *
	 * @since  2.0
	 * @internal
	 *
	 * @uses  WPSubtitle::get_supported_post_types()
	 * @uses  apply_filters( 'wps_meta_box_title' )
	 * @uses  WPSubtitle_Admin::_add_subtitle_meta_box()
	 */
	public static function _add_meta_boxes() {
		$post_types = WPSubtitle::get_supported_post_types();
		foreach ( $post_types as $post_type ) {
			add_meta_box( 'wps_subtitle_panel',  self::get_meta_box_title( $post_type ), array( 'WPSubtitle_Admin', '_add_subtitle_meta_box' ), $post_type, 'normal', 'high' );
		}
	}

	/**
	 * Add Subtitle Meta Box
	 *
	 * @since  2.0
	 * @internal
	 *
	 * @uses  WPSubtitle::_get_post_meta()
	 * @uses  apply_filters( 'wps_subtitle_field_description' )
	 */
	public static function _add_subtitle_meta_box() {

		global $post;

		$value = self::get_admin_subtitle_value( $post );

		echo '<input type="hidden" name="wps_noncename" id="wps_noncename" value="' . wp_create_nonce( 'wp-subtitle' ) . '" />';

		// As of WordPress 4.3 no need to esc_attr() AND htmlentities().
		// @see  https://core.trac.wordpress.org/changeset/33271
		echo '<input type="text" id="wpsubtitle" name="wps_subtitle" value="' . esc_attr( $value ) . '" autocomplete="off" placeholder="' . esc_attr( apply_filters( 'wps_subtitle_field_placeholder', __( 'Enter subtitle here', 'wp-subtitle' ) ) ) . '" style="width:99%;" />';

		echo apply_filters( 'wps_subtitle_field_description', '', $post );

	}

	/**
	 * Add Subtitle Field
	 *
	 * @since  2.2
	 * @internal
	 *
	 * @uses  WPSubtitle::_get_post_meta()
	 * @uses  apply_filters( 'wps_subtitle_field_description' )
	 */
	public static function _add_subtitle_field() {

		global $post;

		$value = self::get_admin_subtitle_value( $post );

		echo '<input type="hidden" name="wps_noncename" id="wps_noncename" value="' . wp_create_nonce( 'wp-subtitle' ) . '" />';
		echo '<div id="subtitlediv" class="top">';
		echo '<div id="subtitlewrap">';

		// As of WordPress 4.3 no need to esc_attr() AND htmlentities().
		// @see  https://core.trac.wordpress.org/changeset/33271
		echo '<input type="text" id="wpsubtitle" name="wps_subtitle" value="' . esc_attr( $value ) . '" autocomplete="off" placeholder="' . esc_attr( apply_filters( 'wps_subtitle_field_placeholder', __( 'Enter subtitle here', 'wp-subtitle' ) ) ) . '" />';

		echo '</div>';

		// Description
		$description = apply_filters( 'wps_subtitle_field_description', '', $post );
		if ( ! empty( $description ) ) {
			echo '<div id="subtitledescription">' . $description . '</div>';
		}
		echo '</div>';
	}

	/**
	 * Get Admin Subtitle Value
	 *
	 * @since  2.8
	 * @internal
	 *
	 * @param   WP_Post  $post  Post object.
	 * @return  string          Subtitle value.
	 */
	private static function get_admin_subtitle_value( $post ) {

		$subtitle = new WP_Subtitle( $post );

		$value = $subtitle->get_raw_subtitle();

		// Default subtitle if adding new post
		if ( function_exists( 'get_current_screen' ) && empty( $value ) ) {
			$screen = get_current_screen();
			if ( isset( $screen->action ) && 'add' == $screen->action ) {
				$value = $subtitle->get_default_subtitle( $post );
			}
		}

		return $value;

	}

	/**
	 * Save Subtitle
	 *
	 * @since  2.0
	 * @internal
	 *
	 * @uses  WPSubtitle::get_supported_post_types()
	 *
	 * @param  int  $post_id  Post ID or object.
	 */
	public static function _save_post( $post_id ) {

		// Verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Verify nonce
		if ( ! self::_verify_posted_nonce( 'wps_noncename', 'wp-subtitle' ) ) {
			return;
		}

		// Check data and save
		if ( isset( $_POST['wps_subtitle'] ) ) {

			$new_value = $_POST['wps_subtitle'];

			$subtitle = new WP_Subtitle( $post_id );

			// Don't save if value not changed
			if ( $subtitle->is_current_subtitle( $new_value ) ) {
				return;
			}

			if ( $subtitle->current_user_can_edit() ) {
				$subtitle->update_subtitle( $new_value );
			}

		}

	}

	/**
	 * Verify Post Edit Capability
	 *
	 * @since        2.0.1
	 * @deprecated   2.7    Use WP_Subtitle->current_user_can_edit() instead.
	 * @internal
	 *
	 * @param   int  $post_id  Post ID.
	 * @return  bool
	 */
	private static function _verify_post_edit_capability( $post_id ) {

		_deprecated_function( '_verify_post_edit_capability()', '2.7', 'WP_Subtitle->current_user_can_edit()' );

		$subtitle = new WP_Subtitle( $post_id );

		return $subtitle->current_user_can_edit();

	}

	/**
	 * Verify Posted Nonce
	 *
	 * @since  2.0.1
	 * @internal
	 *
	 * @param   string  $nonce   Posted nonce name.
	 * @param   string  $action  Nonce action.
	 * @return  bool
	 */
	private static function _verify_posted_nonce( $nonce, $action ) {
		if ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], $action ) ) {
			return true;
		}
		return false;
	}

	/**
	 * edit_form_after_title Supported
	 *
	 * @since  2.2
	 *
	 * @param   string  $post_type  Post type.
	 * @return  bool
	 */
	private static function edit_form_after_title_supported( $post_type = '' ) {
		global $wp_version;

		if ( version_compare( $wp_version, '3.5', '<' ) ) {
			return false;
		}
		return ! apply_filters( 'wps_subtitle_use_meta_box', false, $post_type );
	}

}
