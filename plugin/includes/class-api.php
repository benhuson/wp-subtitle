<?php

/**
 * @package     WP Subtitle
 * @subpackage  API Class
 *
 * Usage examples below. All parameters are optional.
 *
 * // Example: Display subtitle
 * do_action( 'plugins/wp_subtitle/the_subtitle', array(
 *    'before'        => '<p class="subtitle">',  // Before subtitle HTML output (default empty string)
 *    'after'         => '</p>',                  // After subtitle HTML output (default empty string)
 *    'post_id'       => get_the_ID(),            // Post ID (default current post ID)
 *    'default_value' => ''                       // Default output (if no subtitle)
 * ) );
 *
 * // Example: Get subtitle display
 * $subtitle = apply_filters( 'plugins/wp_subtitle/get_subtitle', '', array(
 *    'before' => '<p class="subtitle">',  // Before subtitle HTML output (default empty string)
 *    'after'  => '</p>',                  // After subtitle HTML output (default empty string)
 *    'post_id' => get_the_ID()            // Post ID (default current post ID)
 * ) );
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WP_Subtitle_API {

	/**
	 * Setup Hooks
	 */
	public function setup_hooks() {

		add_action( 'plugins/wp_subtitle/the_subtitle', array( $this, 'the_subtitle' ) );
		add_filter( 'plugins/wp_subtitle/get_subtitle', array( $this, 'get_subtitle' ), 10, 2 );

	}

	/**
	 * The Subtitle
	 *
	 * @param  array  $args  Display args.
	 *
	 * @internal  Private. Called via the `the_subtitle` action.
	 */
	public function the_subtitle( $args = '' ) {

		$default_value = isset( $args['default_value'] ) ? $args['default_value'] : '';

		echo $this->get_subtitle( $default_value, $args );

	}

	/**
	 * Get Subtitle
	 *
	 * @param   string  $default_subtitle  Default/fallback subtitle.
	 * @param   array   $args              Display args.
	 * @return  string                     The subtitle.
	 *
	 * @internal  Private. Called via the `get_subtitle` action.
	 */
	public function get_subtitle( $default_subtitle, $args = '' ) {

		$args = wp_parse_args( $args, array(
			'post_id' => get_the_ID(),  // Post ID
			'before'  => '',            // Before subtitle HTML output
			'after'   => ''             // After subtitle HTML output
		) );

		$subtitle_obj = new WP_Subtitle( $args['post_id'] );
		$subtitle = $subtitle_obj->get_subtitle( $args );

		if ( ! empty( $subtitle ) ) {
			return $subtitle;
		}

		return $default_subtitle;

	}

}
