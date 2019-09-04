<?php

/**
 * @package     WP Subtitle
 * @subpackage  Pointers
 */

add_action( 'admin_init', array( 'WPSubtitle_Pointers', '_setup' ) );

class WPSubtitle_Pointers {

	/**
	 * Setup
	 *
	 * @since  2.2
	 * @internal
	 */
	public static function _setup() {
		add_action( 'admin_enqueue_scripts', array( 'WPSubtitle_Pointers', '_pointer_load' ) );

		// Post Pointers
		$post_types = WPSubtitle::get_supported_post_types();
		foreach ( $post_types as $post_type ) {
			add_filter( 'wps_subtitle_admin_pointers-' . $post_type, array( 'WPSubtitle_Pointers', '_post_type_pointers' ) );
		}
	}

	/**
	 * Load Pointers
	 *
	 * @since  2.2
	 * @internal
	 *
	 * @param   string  $hook_suffix  Page hook.
	 */
	public static function _pointer_load( $hook_suffix ) {

		// Don't run on WP < 3.3
		if ( get_bloginfo( 'version' ) < '3.3' ) {
			return;
		}

		// Get pointers for this screen, or return.
		$pointers = self::get_current_pointers();
		if ( empty( $pointers ) ) {
			return;
		}

		// Get dismissed pointers, or return.
		$valid_pointers = self::remove_dismissed_pointers( $pointers );
		if ( empty( $valid_pointers ) ) {
			return;
		}

		// Enqueue pointer scripts and styles.
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wps-subtitle-pointer', plugins_url( 'js/pointers.js', __FILE__ ), array( 'wp-pointer' ) );
		wp_localize_script( 'wps-subtitle-pointer', 'wpsSubtitlePointer', $valid_pointers );

	}

	/**
	 * Get Current Pointers
	 *
	 * Get pointers for the current aadmin screen.
	 *
	 * @since  2.4
	 * @internal
	 *
	 * @return  array  Current screen pointers.
	 */
	private static function get_current_pointers() {

		$screen = get_current_screen();
		$pointers = apply_filters( 'wps_subtitle_admin_pointers-' . $screen->id, array() );

		// Only return valid array of pointers.
		if ( is_array( $pointers ) ) {
			return $pointers;
		}

		return array();

	}

	/**
	 * Remove Dismissed Pointers
	 *
	 * @since  2.4
	 * @internal
	 *
	 * @param   array  $pointers  Pointers.
	 * @return  array             Active pointers.
	 */
	private static function remove_dismissed_pointers( $pointers ) {

		$dismissed = self::get_dismissed_pointers();
		$valid_pointers = array();

		// Check pointers and remove dismissed ones.
		foreach ( $pointers as $pointer_id => $pointer ) {

			// Sanity check
			if ( in_array( $pointer_id, $dismissed ) || empty( $pointer )  || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) ) {
				continue;
			}

			$pointer['pointer_id'] = $pointer_id;

			// Add the pointer to $valid_pointers array
			$valid_pointers['pointers'][] = $pointer;
		}

		return $valid_pointers;

	}

	/**
	 * Get Dismissed Pointers
	 *
	 * @since  2.4
	 * @internal
	 *
	 * @return  array  Dismissed pointers.
	 */
	private static function get_dismissed_pointers() {

		return explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	}

	/**
	 * Post Type Pointers.
	 * The add pointers for multiple post types.
	 *
	 * @since  2.2
	 * @internal
	 *
	 * @param   array  $pointers  Pointers.
	 * @return  array             Pointers.
	 */
	public static function _post_type_pointers( $pointers ) {

		// Subtitle field moved to below the post title (v.2.2)
		$pointers['wps_subtitle_field_to_top'] = array(
			'target'  => '#subtitlewrap',
			'options' => array(
				'content' => sprintf( '<h3>%s</h3><p>%s</p>',
					sprintf( __( '%s Field', 'wp-subtitle' ), WPSubtitle_Admin::get_meta_box_title( get_post_type( get_queried_object_id() ) ) ),
					__( 'This field has moved from a meta box to below the post title.', 'wp-subtitle' )
				),
				'position' => array(
					'edge'  => 'top',
					'align' => 'middle'
				)
			)
		);

		return $pointers;
	}

}
