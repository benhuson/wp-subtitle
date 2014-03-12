<?php

/**
 * @package     WP Subtitle
 * @subpackage  Admin
 */

// Language
load_plugin_textdomain( 'wp-subtitle', false, dirname( WPSUBTITLE_BASENAME ) . '/languages' );

// Includes
add_action( 'add_meta_boxes', array( 'WPSubtitle_Admin', '_add_meta_boxes' ) );
add_action( 'save_post', array( 'WPSubtitle_Admin', '_save_post' ) );

class WPSubtitle_Admin {

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
	static function _add_meta_boxes() {
		$post_types = WPSubtitle::get_supported_post_types();
		foreach ( $post_types as $post_type ) {
			$meta_box_title = apply_filters( 'wps_meta_box_title', __( 'Subtitle', 'wp-subtitle' ), $post_type );
			add_meta_box( 'wps_subtitle_panel', __( $meta_box_title ), array( 'WPSubtitle_Admin', '_add_subtitle_meta_box' ), $post_type, 'normal', 'high' );
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
	static function _add_subtitle_meta_box() {
		global $post;
		echo '<input type="hidden" name="wps_noncename" id="wps_noncename" value="' . wp_create_nonce( 'wp-subtitle' ) . '" />';
		echo '<input type="text" id="wpsubtitle" name="wps_subtitle" value="' . WPSubtitle::_get_post_meta( $post->ID ) . '" style="width:99%;" />';
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
	static function _save_post( $post_id ) {

		// Verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Verify nonce
		if ( ! WPSubtitle_Admin::_verify_posted_nonce( 'wps_noncename', 'wp-subtitle' ) ) {
			return;
		}

		// Check edit capability
		if ( ! WPSubtitle_Admin::_verify_post_edit_capability( $post_id ) ) {
			return;
		}
	
		// Save data
		if ( isset( $_POST['wps_subtitle'] ) ) {
			update_post_meta( $post_id, 'wps_subtitle', $_POST['wps_subtitle'] );
		}
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
	static function _verify_post_edit_capability( $post_id ) {
		$abort = true;
		$post_types = WPSubtitle::get_supported_post_types();
		$post_types_obj = (array) get_post_types( array(
			'_builtin' => false
		), 'objects' );

		// Check supported post type
		if ( isset( $_POST['post_type'] ) && in_array( $_POST['post_type'], $post_types ) ) {
			if ( 'page' == $_POST['post_type'] && current_user_can( 'edit_page', $post_id ) ) {
				return true;
			} elseif ( 'post' == $_POST['post_type'] && current_user_can( 'edit_post', $post_id ) ) {
				return true;
			} elseif ( current_user_can( $post_types_obj[ $_POST['post_type'] ]->cap->edit_post, $post_id ) ) {
				return true;
			}
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
	static function _verify_posted_nonce( $nonce, $action ) {
		if ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], $action ) ) {
			return true;
		}
		return false;
	}

}
