<?php

/**
 * @package     WP Subtitle
 * @subpackage  SEOPress
 *
 * @since  3.4
 *
 * Compatibility for the SEOPress plugin:
 * https://wordpress.org/plugins/wp-seopress/
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

class WPSubtitle_SEOPress {

	/**
	 * Constructor
	 *
	 * @since  3.4
	 *
	 * @internal  Do not create multiple instances.
	 */
	public function __construct() {

		add_filter( 'seopress_titles_template_variables_array', array( $this, 'add_seopress_replacements' ) );
		add_filter( 'seopress_titles_template_replace_array', array( $this, 'replace_seopress_replacements' ) );

	}

	/**
	 * Add SEOPress Replacements Variables
	 *
	 * @since  3.4
	 *
	 * @param    array  $replacements  SEO replacements variables.
	 * @return   array                 Filtered replacements variables.
	 *
	 * @internal  Called via the `seopress_titles_template_variables_array` filter.
	 */
	public function add_seopress_replacements( $replacements ) {

		$replacements[] = '%%wps_subtitle%%';
		$replacements[] = '%%wps_subtitle_before_sep%%';
		$replacements[] = '%%wps_subtitle_after_sep%%';

		return $replacements;

	}

	/**
	 * Replace SEOPress Replacements Values
	 *
	 * @since  3.4
	 *
	 * @param    array  $replacements  SEO replacements values.
	 * @return   array                 Filtered replacements values.
	 *
	 * @internal  Called via the `seopress_titles_template_replace_array` filter.
	 */
	public function replace_seopress_replacements( $replacements ) {

		global $post;

		$wp_subtitle = new WP_Subtitle( $post );
		$subtitle = $wp_subtitle->get_subtitle();

		$replacements[] = $subtitle;

		$sep = ' ' . $replacements[0] . ' ';

		$before_sep = '';
		$after_sep = '';

		if ( ! empty( $subtitle ) ) {

			$before_sep = apply_filters( 'wps_subtitle_seo_before_sep', $sep );
			$after_sep = apply_filters( 'wps_subtitle_seo_after_sep', $sep );

		}

		$replacements[] = $before_sep;
		$replacements[] = $after_sep;

		return $replacements;

	}

}
