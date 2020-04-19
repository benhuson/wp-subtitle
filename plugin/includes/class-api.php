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
 *
 * // Example: Display term subtitle
 * do_action( 'plugins/wp_subtitle/the_term_subtitle', array(
 *    'before'        => '<p class="subtitle">',  // Before subtitle HTML output (default empty string)
 *    'after'         => '</p>',                  // After subtitle HTML output (default empty string)
 *    'term_id'       => 0,                       // Term ID (default to none)
 *    'default_value' => ''                       // Default output (if no subtitle)
 * ) );
 *
 * // Example: Get term subtitle display
 * $subtitle = apply_filters( 'plugins/wp_subtitle/get_term_subtitle', '', array(
 *    'before' => '<p class="subtitle">',  // Before subtitle HTML output (default empty string)
 *    'after'  => '</p>',                  // After subtitle HTML output (default empty string)
 *    'term_id' => 0                       // Term ID (default to none)
 * ) );
 *
 * // Example: Display archive subtitle
 * do_action( 'plugins/wp_subtitle/the_archive_subtitle', array(
 *    'before'        => '<p class="subtitle">',  // Before subtitle HTML output (default empty string)
 *    'after'         => '</p>',                  // After subtitle HTML output (default empty string)
 *    'default_value' => ''                       // Default output (if no subtitle)
 * ) );
 *
 * // Example: Get archive subtitle display
 * $subtitle = apply_filters( 'plugins/wp_subtitle/get_archive_subtitle', '', array(
 *    'before' => '<p class="subtitle">',  // Before subtitle HTML output (default empty string)
 *    'after'  => '</p>',                  // After subtitle HTML output (default empty string)
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

		add_action( 'plugins/wp_subtitle/the_term_subtitle', array( $this, 'the_term_subtitle' ) );
		add_filter( 'plugins/wp_subtitle/get_term_subtitle', array( $this, 'get_term_subtitle' ), 10, 2 );

		add_action( 'plugins/wp_subtitle/the_archive_subtitle', array( $this, 'the_archive_subtitle' ) );
		add_filter( 'plugins/wp_subtitle/get_archive_subtitle', array( $this, 'get_archive_subtitle' ), 10, 2 );

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

		$args = $this->post_parse_args( $args );

		$subtitle_obj = new WP_Subtitle( $args['post_id'] );
		$subtitle = $subtitle_obj->get_subtitle( $args );

		if ( ! empty( $subtitle ) ) {
			return $subtitle;
		}

		return $default_subtitle;

	}

	/**
	 * The Term Subtitle (Action)
	 *
	 * @param  array  $args  Display args.
	 */
	public function the_term_subtitle( $args = '' ) {

		echo apply_filters( 'plugins/wp_subtitle/get_term_subtitle', '', $args );

	}

	/**
	 * The Term Subtitle (Filter)
	 *
	 * @param   string  $subtitle  Subtitle.
	 * @param   array   $args      Display args.
	 * @return  string             Subtitle.
	 */
	public function get_term_subtitle( $subtitle = '', $args = '' ) {

		$args = $this->term_parse_args( $args );

		$subtitle = new WP_Subtitle_Term( $args['term_id'] );

		return $this->get_display( $subtitle->get_meta_value(), $args );

	}

	/**
	 * The Archive Subtitle (Action)
	 *
	 * @param  array  $args  Display args.
	 */
	public function the_archive_subtitle( $args = '' ) {

		echo apply_filters( 'plugins/wp_subtitle/get_archive_subtitle', '', $args );

	}

	/**
	 * The Archive Subtitle (Filter)
	 *
	 * If the main blog page when posts page is set, get the subtitle of the page.
	 * If a categiry/tag/term archive, get the term subtitle.
	 *
	 * @param   string  $subtitle  Subtitle.
	 * @param   array   $args      Display args.
	 * @return  string             Subtitle.
	 */
	public function get_archive_subtitle( $subtitle = '', $args = '' ) {

		if ( is_home() && ! is_front_page() ) {
			$args['post_id'] = get_option( 'page_for_posts', 0 );
			return apply_filters( 'plugins/wp_subtitle/get_subtitle', '', $args );
		}

		if ( is_category() || is_tag() || is_tax() ) {
			$args['term_id'] = get_queried_object_id();
			return apply_filters( 'plugins/wp_subtitle/get_term_subtitle', '', $args );
		}

		return '';

	}

	/**
	 * Get Display
	 *
	 * @param   string  $subtitle  Subtitle.
	 * @param   array   $args      Display args.
	 * @return  string             Subtitle.
	 */
	protected function get_display( $subtitle, $args ) {

		if ( ! empty( $subtitle ) ) {
			$subtitle = $args['before'] . $subtitle . $args['after'];
		}

		return $subtitle;

	}

	/**
	 * Post Parse Args
	 *
	 * @param   array  $args  Args.
	 * @return  array         Args.
	 */
	protected function post_parse_args( $args = '' ) {

		$args = wp_parse_args( $args, array(
			'post_id' => get_the_ID(),  // Post ID
			'before'  => '',            // Before subtitle HTML output
			'after'   => ''             // After subtitle HTML output
		) );

		return $args;

	}

	/**
	 * Term Parse Args
	 *
	 * @param   array  $args  Args.
	 * @return  array         Args.
	 */
	protected function term_parse_args( $args = '' ) {

		$args = wp_parse_args( $args, array(
			'term_id' => 0,
			'before'  => '',
			'after'   => ''
		) );

		return $args;

	}

}
