<?php

/*
Plugin Name: WP Subtitle
Plugin URI: http://wordpress.org/plugins/wp-subtitle/
Description: Adds a subtitle field to pages and posts. Possible to add support for custom post types.
Author: Husani Oakley, Ben Huson
Author URI: https://github.com/benhuson/wp-subtitle
Version: 2.3
License: GPLv2
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
include_once( WPSUBTITLE_DIR . 'includes/deprecated.php' );

// Include admin-only functionality
if ( is_admin() ) {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		// Load AJAX functions here if required...
	} else {
		require_once( WPSUBTITLE_DIR . 'admin/admin.php' );
		require_once( WPSUBTITLE_DIR . 'admin/pointers.php' );
	}
}

add_action( 'init', array( 'WPSubtitle', '_add_default_post_type_support' ), 5 );

class WPSubtitle {

	/**
	 * Add Default Post Type Support
	 *
	 * @since  2.0
	 * @internal
	 */
	static function _add_default_post_type_support() {
		add_post_type_support( 'page', 'wps_subtitle' );
		add_post_type_support( 'post', 'wps_subtitle' );
	}

	/**
	 * Get Supported Post Types
	 *
	 * @since  2.0
	 *
	 * @return  array  Array of supported post types.
	 */
	static function get_supported_post_types() {
		$post_types = (array) get_post_types( array(
			'_builtin' => false
		) );
		$post_types = array_merge( $post_types, array( 'post', 'page' ) );
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
	static function is_supported_post_type( $post_type ) {
		$post_types = WPSubtitle::get_supported_post_types();
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
	 * @uses  WPSubtitle::_get_post_meta()
	 * @uses  apply_filters( 'wps_subtitle' )
	 *
	 * @param   int|object  $post  Post ID or object.
	 * @return  string             The filtered subtitle meta value.
	 */
	static function get_the_subtitle( $post = 0 ) {
		$post = get_post( $post );
		if ( WPSubtitle::is_supported_post_type( $post->post_type ) ) {
			$subtitle = WPSubtitle::_get_post_meta( $post );
			return apply_filters( 'wps_subtitle', $subtitle, $post );
		}
		return '';
	}

	/**
	 * Get Post Meta
	 *
	 * @since  2.0
	 * @internal
	 *
	 * @param   int|object  $post  Post ID or object.
	 * @return  string             The subtitle meta value.
	 */
	static function _get_post_meta( $id = 0 ) {
		$post = get_post( $id );
		return get_post_meta( $post->ID, 'wps_subtitle', true );
	}

}

/**
 * The Subtitle
 *
 * @since  1.0
 *
 * @uses  get_the_subtitle()
 *
 * @param   string  $before  Before the subtitle.
 * @param   string  $after   After the subtitle.
 * @param   bool    $echo    Output if true, return if false.
 * @return  string           The subtitle string.
 */
function the_subtitle( $before = '', $after = '', $echo = true ) {
	return get_the_subtitle( 0, $before, $after, $echo );
}

/**
 * Get the Subtitle
 *
 * @since  1.0
 *
 * @uses  WPSubtitle::get_the_subtitle()
 *
 * @param   int|object  $post    Post ID or object.
 * @param   string      $before  Before the subtitle.
 * @param   string      $after   After the subtitle.
 * @param   bool        $echo    Output if true, return if false.
 * @return  string               The subtitle string.
 */
function get_the_subtitle( $post = 0, $before = '', $after = '', $echo = true ) {
	$subtitle = WPSubtitle::get_the_subtitle( $post );

	if ( ! empty( $subtitle ) ) {
		$subtitle = $before . $subtitle . $after;
	}

	if ( ! $echo ) {
		return $subtitle;
	}
	echo $subtitle;
}
