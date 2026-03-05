<?php

/**
 * @package     WP Subtitle
 * @subpackage  Helper
 */

class WPSubtitle_Helper {

	/**
	 * Sanitize subtitle value for storage.
	 *
	 * @param  mixed $value Raw subtitle value.
	 * @return string Sanitized subtitle value.
	 */
	public static function sanitize_subtitle_value( $value ) {

		if ( ! is_string( $value ) ) {
			$value = '';
		}

		return wp_kses( $value, wp_kses_allowed_html( 'data' ) );

	}

}
