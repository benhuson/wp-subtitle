<?php

/**
 * @package     WP Subtitle
 * @subpackage  Deprecated Functions
 */

/**
 * Query DB and echo page/post subtitle, if any
 *
 * @uses  WPSubtitle::_get_post_meta()
 *
 * @since  1.0
 * @deprecated  2.0  Use get_the_subtitle() instead.
 */
function wps_get_the_subtitle() {
	_deprecated_function( 'wps_get_the_subtitle()', '2.0', 'the_subtitle()' );

	$subtitle = new WP_Subtitle( get_the_ID() );
	$subtitle->the_subtitle();

}

/**
 * Display XHTML for subtitle panel
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_addPanelXHTML() {
	_deprecated_function( 'wps_addPanelXHTML()', '2.0' );
}

/**
 * Include CSS for subtitle panel
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_addPanelCSS() {
	_deprecated_function( 'wps_addPanelCSS()', '2.0' );
}

/**
 * Include XHTML for form inside panel
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_showSubtitlePanel() {
	_deprecated_function( 'wps_addPanelCSS()', '2.0' );
}

/**
 * For pre-2.5, include shell for panel
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_showSubtitlePanelOld() {
	_deprecated_function( 'wps_showSubtitlePanelOld()', '2.0' );
}

/**
 * Store subtitle content in db as custom field
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_saveSubtitle( $post_id ) {
	_deprecated_function( 'wps_saveSubtitle()', '2.0' );
}
