<?php

/*
Plugin Name: WP Subtitle
Plugin URI: http://wordpress.org/plugins/wp-subtitle/
Description: Adds a subtitle field to pages and posts. Possible to add support for custom post types.
Version: 2.9
Author: Ben Huson, Husani Oakley
Author URI: https://github.com/benhuson/wp-subtitle
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: wp-subtitle
Domain Path: /languages
*/

/*
Copyright 2009  Husani Oakley  (email : wordpressplugins@husani.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Plugin directory and url paths.
define( 'WPSUBTITLE_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPSUBTITLE_SUBDIR', '/' . str_replace( basename( __FILE__ ), '', WPSUBTITLE_BASENAME ) );
define( 'WPSUBTITLE_URL', plugins_url( WPSUBTITLE_SUBDIR ) );
define( 'WPSUBTITLE_DIR', plugin_dir_path( __FILE__ ) );

// Includes
include_once( WPSUBTITLE_DIR . 'includes/subtitle.php' );
include_once( WPSUBTITLE_DIR . 'includes/deprecated.php' );
include_once( WPSUBTITLE_DIR . 'includes/shortcode.php' );

// Include admin-only functionality
if ( is_admin() ) {
	require_once( WPSUBTITLE_DIR . 'admin/admin.php' );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		// Load AJAX functions here if required...
	} else {
		require_once( WPSUBTITLE_DIR . 'admin/pointers.php' );
	}
}

add_action( 'init', array( 'WPSubtitle', '_add_default_post_type_support' ), 5 );

// Default subtitle filters
add_filter( 'wps_subtitle', 'wptexturize' );
add_filter( 'wps_subtitle', 'trim' );

class WPSubtitle {

	/**
	 * Add Default Post Type Support
	 *
	 * @since  2.0
	 * @internal
	 */
	public static function _add_default_post_type_support() {
		add_post_type_support( 'page', 'wps_subtitle' );
		add_post_type_support( 'post', 'wps_subtitle' );
		add_post_type_support( 'revision', 'wps_subtitle' );

		add_filter( 'the_preview', array( 'WPSubtitle', 'the_preview' ), 10, 2 );

	}

	/**
	 * Returns the autosaved data to make changes visible in preview mode.
	 *
	 * @since  2.9
	 *
	 * @param   object         $post   Post object.
	 * @param   object         $query  Query object.
	 * @return  WP_Post|false          The autosaved data or false on failure or when no autosave exists.
	 */
	public static function the_preview( $post, $query ) {

		if ( isset( $_GET['preview_id'] ) ) {
			return wp_get_post_autosave( $post->ID );
		}

		if ( $revisions = wp_get_post_revisions( $post->ID ) ) {
			return array_shift( $revisions );
		}

		return $post;

	}

	/**
	 * Get Supported Post Types
	 *
	 * @since  2.0
	 *
	 * @return  array  Array of supported post types.
	 */
	public static function get_supported_post_types() {
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
	 * Is Supported Post Type
	 *
	 * @since  2.3
	 *
	 * @param   string   $post_type  Post Type.
	 * @return  boolean
	 */
	public static function is_supported_post_type( $post_type ) {
		$post_types = self::get_supported_post_types();
		if ( in_array( $post_type, $post_types ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get the Subtitle
	 *
	 * @since  2.0
	 *
	 * @uses  WP_Subtitle::get_subtitle()
	 *
	 * @param   int|object  $post  Post ID or object.
	 * @return  string             The filtered subtitle meta value.
	 */
	public static function get_the_subtitle( $post = 0 ) {

		$subtitle = new WP_Subtitle( $post );

		return $subtitle->get_subtitle();

	}

	/**
	 * Get Post Meta
	 *
	 * @since  2.0
	 * @internal
	 *
	 * @uses  WP_Subtitle::get_raw_subtitle()
	 *
	 * @param   int|object  $post  Post ID or object.
	 * @return  string             The subtitle meta value.
	 */
	public static function _get_post_meta( $post = 0 ) {

		$subtitle = new WP_Subtitle( $post );

		return $subtitle->get_raw_subtitle();

	}

	/**
	 * Get Post Meta Key
	 *
	 * @since  2.5.x
	 * @internal
	 *
	 * @param   int     $post  Post ID.
	 * @return  string         The subtitle meta key.
	 */
	public static function _get_post_meta_key( $post_id = 0 ) {

		return apply_filters( 'wps_subtitle_key', 'wps_subtitle', $post_id );

	}

}

/**
 * The Subtitle
 *
 * @since  1.0
 *
 * @uses  WP_Subtitle::get_subtitle()
 *
 * @param   string  $before  Before the subtitle.
 * @param   string  $after   After the subtitle.
 * @param   bool    $echo    Output if true, return if false.
 * @return  string           The subtitle string.
 */
function the_subtitle( $before = '', $after = '', $echo = true ) {

	$subtitle = new WP_Subtitle( get_the_ID() );

	$output = $subtitle->get_subtitle( array(
		'before' => $before,
		'after'  => $after
	) );

	if ( ! $echo ) {
		return $output;
	}

	echo $output;

}

/**
 * Get the Subtitle
 *
 * @since  1.0
 *
 * @uses  WP_Subtitle::get_subtitle()
 *
 * @param   int|object  $post    Post ID or object.
 * @param   string      $before  Before the subtitle.
 * @param   string      $after   After the subtitle.
 * @param   bool        $echo    Output if true, return if false.
 * @return  string               The subtitle string.
 */
function get_the_subtitle( $post = 0, $before = '', $after = '', $echo = true ) {

	$subtitle = new WP_Subtitle( $post );

	$output = $subtitle->get_subtitle( array(
		'before' => $before,
		'after'  => $after
	) );

	if ( ! $echo ) {
		return $output;
	}

	echo $output;

}
