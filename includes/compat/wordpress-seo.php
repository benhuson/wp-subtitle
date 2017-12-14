<?php

/**
 * @package     WP Subtitle
 * @subpackage  WordPress SEO
 *
 * @since  3.1
 *
 * Compatibility for the Yoast SEO plugin:
 * https://wordpress.org/plugins/wordpress-seo/
 *
 * Adds support for a `%%wps_subtitle%%` placeholder to include
 * the subtitle in browser titles and meta descriptions.
 *
 * Also adds `%%wps_subtitle_before_sep%%` and `%%wps_subtitle_after_sep%%`.
 * These can be used to add seperators before/after the subtitle. If there
 * is no subtitle set these placeholders will not be output.
 *
 * The seperator placeholders can be customized using the `wps_subtitle_seo_before_sep`
 * and `wps_subtitle_seo_after_sep` filters.
 *
 * The seperator placeholders include a 'space' either side by default. This means that
 * you should create your title template with no whitespace around the seperator placeholders.
 * It allows you to customize the seperators to include commas and butt the seperator up to
 * the preceding/following text.
 *
 * e.g. If '%%wps_subtitle_before_sep%%' is set to ', ':
 *      `%%title%%%%wps_subtitle_before_sep%%%%wps_subtitle%% %%sep%% %%sitename%%`
 *      "Title, Subtitle - Sitename"
 */

class WPSubtitle_WPSEO {

	/**
	 * Constructor
	 *
	 * @since  3.1
	 *
	 * @internal  Do not create multiple instances.
	 */
	public function __construct() {

		add_filter( 'wpseo_replacements', array( $this, 'add_wpseo_replacements' ) );

	}

	/**
	 * Add SEO Replacements
	 *
	 * @since  3.1
	 *
	 * @param    array  $replacements  SEO replacements.
	 * @return   array                 Filtered replacements.
	 *
	 * @internal  Called via the `wpseo_replacements` filter.
	 */
	public function add_wpseo_replacements( $replacements ) {

		global $post;

		$wp_subtitle = new WP_Subtitle( $post );
		$subtitle = $wp_subtitle->get_subtitle();

		$replacements['%%wps_subtitle%%'] = $subtitle;
		$replacements['%%wps_subtitle_before_sep%%'] = '';
		$replacements['%%wps_subtitle_after_sep%%'] = '';

		if ( ! empty( $subtitle ) ) {

			$sep = isset( $replacements['%%sep%%'] ) ? ' ' . $replacements['%%sep%%'] . ' ' : ' - ';

			$replacements['%%wps_subtitle_before_sep%%'] = apply_filters( 'wps_subtitle_seo_before_sep', $sep );
			$replacements['%%wps_subtitle_after_sep%%'] = apply_filters( 'wps_subtitle_seo_after_sep', $sep );

		}

		return $replacements;

	}

}
