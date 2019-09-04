<?php

/**
 * @package     WP Subtitle
 * @subpackage  Deprecated Functions
 */

/**
 * The Subtitle
 *
 * @since  1.0
 * @deprecated  3.3  Use do_action( 'plugins/wp_subtitle/the_subtitle' ) instead.
 *
 * @uses  WP_Subtitle::get_subtitle()
 *
 * @param   string  $before  Before the subtitle.
 * @param   string  $after   After the subtitle.
 * @param   bool    $echo    Output if true, return if false.
 * @return  string           The subtitle string.
 */
function the_subtitle( $before = '', $after = '', $echo = true ) {

	if ( ! $echo ) {

		return apply_filters( 'plugins/wp_subtitle/get_subtitle', '', array(
			'before' => $before,
			'after'  => $after
		) );

	}

	do_action( 'plugins/wp_subtitle/the_subtitle', array(
		'before' => $before,
		'after'  => $after
	) );

}

/**
 * Get the Subtitle
 *
 * @since  1.0
 * @deprecated  3.3  Use apply_filters( 'plugins/wp_subtitle/get_subtitle' ) instead.
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

	$output = apply_filters( 'plugins/wp_subtitle/get_subtitle', '', array(
		'post_id' => is_a( $post, 'WP_Post' ) ? $post->ID : $post,
		'before' => $before,
		'after'  => $after
	) );

	if ( ! $echo ) {
		return $output;
	}

	echo $output;

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
	_deprecated_function( 'wps_get_the_subtitle()', '2.0', "do_action( 'plugins/wp_subtitle/the_subtitle' )" );

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
