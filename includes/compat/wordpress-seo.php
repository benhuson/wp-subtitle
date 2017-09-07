<?php

/**
 * @package     WP Subtitle
 * @subpackage  WordPress SEO
 *
 * Compatibility for the Yoast SEO plugin:
 * https://wordpress.org/plugins/wordpress-seo/
 *
 * Adds support for a `%%wps_subtitle%%` placeholder to include
 * the subtitle in browser titles and meta descriptions.
 */

class WPSubtitle_WPSEO {

	/**
	 * Constructor
	 *
	 * @internal  Do not create multiple instances.
	 */
	public function __construct() {

		add_filter( 'wpseo_replacements', array( $this, 'add_wpseo_replacements' ) );

	}

	/**
	 * Add SEO Replacements
	 *
	 * @param    array  $replacements  SEO replacements.
	 * @return   array                 Filtered replacements.
	 *
	 * @internal  Called via the `wpseo_replacements` filter.
	 */
	public function add_wpseo_replacements( $replacements ) {

		global $post;

		$subtitle = new WP_Subtitle( $post );

		$replacements['%%wps_subtitle%%'] = $subtitle->get_subtitle();

		return $replacements;

	}

}
