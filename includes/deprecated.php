<?php

/**
 * @package    WP Subtitle
 * @subpackage Deprecated Functions
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
