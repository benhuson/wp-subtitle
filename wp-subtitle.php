<?php

/*
Plugin Name: WP Subtitle
Plugin URI: http://wordpress.org/plugins/wp-subtitle/
Description: Adds a subtitle field to pages and posts. Possible to add support for custom post types.
Author: Husani Oakley, Ben Huson
Author URI: https://github.com/benhuson/wp-subtitle
Version: 2.0.1
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

add_action( 'plugins_loaded', array( 'WPSubtitle', '_init' ) );

class WPSubtitle {

	/**
	 * Init
	 *
	 * @since  2.0
	 * @internal
	 *
	 * @uses  WPSubtitle::_add_default_post_type_support()
	 * @uses  WPSubtitle::_add_meta_boxes()
	 * @uses  WPSubtitle::_save_post()
	 */
	function _init() {
		add_action( 'init', array( 'WPSubtitle', '_add_default_post_type_support' ), 5 );
		add_action( 'add_meta_boxes', array( 'WPSubtitle', '_add_meta_boxes' ) );
		add_action( 'save_post', array( 'WPSubtitle', '_save_post' ) );
	}

	/**
	 * Add Default Post Type Support
	 *
	 * @since  2.0
	 * @internal
	 */
	function _add_default_post_type_support() {
		add_post_type_support( 'page', 'wps_subtitle' );
		add_post_type_support( 'post', 'wps_subtitle' );
	}

	/**
	 * Add Meta Boxes
	 *
	 * @since  2.0
	 * @internal
	 *
	 * @uses  WPSubtitle::get_supported_post_types()
	 * @uses  apply_filters( 'wps_meta_box_title' )
	 * @uses  WPSubtitle::_add_subtitle_meta_box()
	 */
	function _add_meta_boxes() {
		$post_types = WPSubtitle::get_supported_post_types();
		foreach ( $post_types as $post_type ) {
			$meta_box_title = apply_filters( 'wps_meta_box_title', 'Subtitle', $post_type );
			add_meta_box( 'wps_subtitle_panel', __( $meta_box_title ), array( 'WPSubtitle', '_add_subtitle_meta_box' ), $post_type, 'normal', 'high' );
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
	function _add_subtitle_meta_box() {
		global $post;
		echo '<input type="hidden" name="wps_noncename" id="wps_noncename" value="' . wp_create_nonce( 'wp-subtitle' ) . '" />
			<input type="text" id="wpsubtitle" name="wps_subtitle" value="' . WPSubtitle::_get_post_meta( $post->ID ) . '" style="width:99%;" />';
		echo apply_filters( 'wps_subtitle_field_description', '', $post );
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
	function _save_post( $post_id ) {

		// Verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		// Verify nonce
		if ( ! WPSubtitle::_verify_posted_nonce( 'wps_noncename', 'wp-subtitle' ) )
			return;

		// Check edit capability
		if ( ! WPSubtitle::_verify_post_edit_capability( $post_id ) )
			return;
	
		// Save data
		if ( isset( $_POST['wps_subtitle'] ) )
			update_post_meta( $post_id, 'wps_subtitle', $_POST['wps_subtitle'] );
	}

	/**
	 * Verify Post Edit Capability
	 *
	 * @since  2.0.1
	 * @internal
	 *
	 * @param   int  $post_id  Post ID.
	 * @return  bool
	 */
	function _verify_post_edit_capability( $post_id ) {
		$abort = true;
		$post_types = WPSubtitle::get_supported_post_types();
		$post_types_obj = (array) get_post_types( array(
			'_builtin' => false
		), 'objects' );

		// Check supported post type
		if ( isset( $_POST['post_type'] ) && in_array( $_POST['post_type'], $post_types ) ) {
			if ( 'page' == $_POST['post_type'] && current_user_can( 'edit_page', $post_id ) )
				return true;
			elseif ( 'post' == $_POST['post_type'] && current_user_can( 'edit_post', $post_id ) )
				return true;
			elseif ( current_user_can( $post_types_obj[$_POST['post_type']]->cap->edit_post, $post_id ) )
				return true;
		}

		return false;
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
	function _verify_posted_nonce( $nonce, $action ) {
		if ( isset( $_POST[$nonce] ) && wp_verify_nonce( $_POST[$nonce], $action ) )
			return true;
		return false;
	}

	/**
	 * Get Supported Post Types
	 *
	 * @since  2.0
	 *
	 * @return  array  Array of supported post types.
	 */
	function get_supported_post_types() {
		$post_types = (array) get_post_types( array(
			'_builtin' => false
		) );
		$post_types = array_merge( $post_types, array( 'post', 'page' ) );
		$supported = array();
		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'wps_subtitle' ) )
				$supported[] = $post_type;
		}
		return $supported;
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
	function get_the_subtitle( $post = 0 ) {
		$post = get_post( $post );
		$subtitle = WPSubtitle::_get_post_meta( $post );
		return apply_filters( 'wps_subtitle', $subtitle, $post );
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
	function _get_post_meta( $id = 0 ) {
		$post = get_post( $id );
		return get_post_meta( $post->ID, 'wps_subtitle', true );
	}

}

/**
 * Query DB and echo page/post subtitle, if any
 *
 * @uses  WPSubtitle::_get_post_meta()
 *
 * @since  1.0
 * @deprecated  2.0  Use get_the_subtitle() instead.
 */
function wps_get_the_subtitle() {
	echo WPSubtitle::_get_post_meta();
}

/**
 * Display XHTML for subtitle panel
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_addPanelXHTML() {
}

/**
 * Include CSS for subtitle panel
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_addPanelCSS() {
}

/**
 * Include XHTML for form inside panel
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_showSubtitlePanel() {
}

/**
 * For pre-2.5, include shell for panel
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_showSubtitlePanelOld() {
}

/**
 * Store subtitle content in db as custom field
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_saveSubtitle( $post_id ) {
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

	if ( ! $echo )
		return $subtitle;
	echo $subtitle;
}
