<?php

/**
 * @package     WP Subtitle
 * @subpackage  Subtitle Class
 */

class WP_Subtitle {

	/**
	 * Post ID
	 *
	 * @var  int
	 */
	private $post_id = 0;

	/**
	 * Constructor
	 *
	 * @param  int|WP_Post  $post  Post object or ID.
	 */
	public function __construct( $post ) {

		// Post ID
		if ( is_a( $post, 'WP_Post' ) ) {
			$this->post_id = absint( $post->ID );
		} else {
			$this->post_id = absint( $post );
		}

	}

	/**
	 * The Subtitle
	 *
	 * @param  array  $args  Display parameters.
	 */
	public function the_subtitle( $args = '' ) {

		echo $this->get_subtitle( $args );

	}

	/**
	 * Get the Subtitle
	 *
	 * @uses  apply_filters( 'wps_subtitle' )
	 *
	 * @param   array   $args  Display parameters.
	 * @return  string         The filtered subtitle meta value.
	 */
	public function get_subtitle( $args = '' ) {

		if ( $this->post_id && $this->is_supported_post_type() ) {

			$args = wp_parse_args( $args, array(
				'before' => '',
				'after'  => ''
			) );

			$subtitle = apply_filters( 'wps_subtitle', $this->get_raw_subtitle(), get_post( $this->post_id ) );

			if ( ! empty( $subtitle ) ) {
				$subtitle = $args['before'] . $subtitle . $args['after'];
			}

			return $subtitle;

		}

		return '';

	}

	/**
	 * Get Raw Subtitle
	 *
	 * @return  string  The subtitle meta value.
	 */
	public function get_raw_subtitle() {

		if ( is_preview() ) {

			if ( isset( $_GET['preview_id'] ) ) {
				$p =  wp_get_post_autosave( $this->post_id );
				return get_post_meta( $p->ID, $this->get_post_meta_key(), true );
			}

			if ( $revisions = wp_get_post_revisions( $this->post_id ) ) {
				$p = array_shift( $revisions );
				return get_post_meta( $p->ID, $this->get_post_meta_key(), true );
			}

		}

		return get_post_meta( $this->post_id, $this->get_post_meta_key(), true );

	}

	/**
	 * Get Default Subtitle
	 *
	 * @since  2.8
	 *
	 * @return  string  Default title.
	 */
	public function get_default_subtitle() {

		return apply_filters( 'wps_default_subtitle', '', $this->post_id );

	}

	/**
	 * Update Subtitle
	 *
	 * @param   string    $subtitle  Subtitle.
	 * @return  int|bool             Meta ID if new entry. True if updated, false if not updated or the same as current value.
	 */
	public function update_subtitle( $subtitle ) {

		// Uses `update_metadata` as `update_post_meta` doesn't work with revisions.
		return update_metadata( 'post', $this->post_id, $this->get_post_meta_key(), $subtitle );

	}

	/**
	 * Is Current Subtitle?
	 *
	 * @since  2.9
	 *
	 * @param   string   $subtitle  Subtitle value.
	 * @return  boolean
	 */
	public function is_current_subtitle( $subtitle ) {

		return $subtitle === get_metadata( 'post', $this->post_id, 'wps_subtitle', true );

	}

	/**
	 * Get Post Meta Key
	 *
	 * @uses  apply_filters( 'wps_subtitle_key' )
	 *
	 * @return  string  The subtitle meta key.
	 */
	private function get_post_meta_key() {

		return apply_filters( 'wps_subtitle_key', 'wps_subtitle', $this->post_id );

	}

	/**
	 * Restore revision.
	 *
	 * @since  2.9
	 *
	 * @param  int  $revision_id  Revision ID.
	 */
	public function restore_post_revision( $revision_id ) {

		$meta_value = get_metadata( 'post', $revision_id, $this->get_post_meta_key(), true );

		if ( $meta_value ) {
			$this->update_subtitle( $meta_value );
		} else {
			delete_post_meta( $this->post_id, $this->get_post_meta_key() );
		}

	}

	/**
	 * Is Supported Post Type?
	 *
	 * @return  boolean
	 */
	private function is_supported_post_type() {

		$post_types = $this->get_supported_post_types();

		return in_array( get_post_type( $this->post_id ), $post_types );

	}

	/**
	 * Get Supported Post Types
	 *
	 * @since  2.7
	 *
	 * @return  array  Array of supported post types.
	 */
	private function get_supported_post_types() {

		$post_types = (array) get_post_types( array(
			'_builtin' => false
		) );

		$post_types = array_merge( $post_types, array( 'post', 'page', 'revision' ) );

		$supported = array();

		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'wps_subtitle' ) ) {
				$supported[] = $post_type;
			}
		}

		return $supported;

	}

	/**
	 * Current User Can Edit
	 *
	 * @since  2.8
	 *
	 * @return  boolean
	 */
	public function current_user_can_edit() {

		// Check supported post type
		if ( $this->is_supported_post_type() ) {

			$post_type = get_post_type( $this->post_id );

			if ( $revision = wp_is_post_revision( $this->post_id ) ) {
				$post_type = get_post_type( $revision );
			}

			// Current user can...
			switch ( $post_type ) {

				// ... edit page
				case 'page':
					return current_user_can( 'edit_page', $this->post_id );

				// ... edit post
				case 'post':
					return current_user_can( 'edit_post', $this->post_id );

				// ... edit other post type
				default:

					$post_types = (array) get_post_types( array(
						'_builtin' => false
					), 'objects' );

					return current_user_can( $post_types[ $post_type ]->cap->edit_post, $this->post_id );

			}

		}

		return false;

	}

}
