<?php

add_shortcode( 'wp_subtitle', array( 'WPSubtitle_Shortcode', 'shortcode' ) );

class WPSubtitle_Shortcode {

	/**
	 * [wp_subtitle] Shortcode
	 *
	 * @since  2.5
	 *
	 * Outputs the post subtitle.
	 *
	 * If the [wp_subtitle] shortcode tag is wrapped around content, that
	 * content will be used as a fallback if no subtitle is specified.
	 * e.g. [wp_subtitle]Fallback Subtitle[/wp_subtitle]
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Fallback content (content between the shortcode tags).
	 * @return  string            Subtitle HTML.
	 */
	public static function shortcode( $atts, $content = null ) {

		global $post;

		$atts = shortcode_atts( array(
			'tag'    => self::get_default_tag(),
			'before' => '',
			'after'  => ''
		), $atts, 'wp_subtitle' );

		// Get HTML tag
		if ( ! empty( $atts['tag'] ) ) {
			$tag = self::validate_tag( $atts['tag'] );
			$before = sprintf( '<%s class="wp-subtitle">', $tag );
			$after = sprintf( '</%s>', $tag );
		} else {
			$before = '';
			$after = '';
		}

		// Add before/after content
		$before .= self::format_subtitle_content( $atts['before'], 'before' );
		$after = self::format_subtitle_content( $atts['after'], 'after' ) . $after;

		$subtitle = new WP_Subtitle( $post );

		return $subtitle->get_subtitle( array(
			'before' => $before,
			'after'  => $after
		) );

	}

	/**
	 * Get Default Tag
	 *
	 * @since  2.5
	 * @internal
	 *
	 * @return  string  Default tag.
	 */
	private static function get_default_tag() {

		return 'p';

	}

	/**
	 * Get Allowed Tags
	 *
	 * @since  2.5
	 * @internal
	 *
	 * @return  array  Allowed HTML tags.
	 */
	private static function get_allowed_tags() {

		return array( 'span', 'div', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );

	}

	/**
	 * Validate Tag
	 *
	 * Returns validated tag. Reverts to default if invalid.
	 *
	 * @since  2.5
	 * @internal
	 *
	 * @param   string  $tag  Tag to validate.
	 * @return  string        Validated tag.
	 */
	private static function validate_tag( $tag ) {

		if ( ! in_array( $tag, self::get_allowed_tags() ) ) {
			$tag = self::get_default_tag();
		}

		return $tag;

	}

	/**
	 * Format Subtitle Content
	 *
	 * @since  2.5
	 * @internal
	 *
	 * @param   string  $content  Content.
	 * @param   string  $type     Content type.
	 * @return  string            HTML formatted content.
	 */
	private static function format_subtitle_content( $content, $type ) {

		$type = sanitize_html_class( $type );

		if ( ! empty( $content ) && ! empty( $type ) ) {
			$content = sprintf( '<span class="wp-subtitle-%s">%s</span>', $type, $content );
		}

		return $content;

	}

}
