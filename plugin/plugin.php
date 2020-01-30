<?php

/**
 * @package     WP Subtitle
 * @subpackage  Plugin
 */

// Plugin directory and url paths.
define( 'WPSUBTITLE_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPSUBTITLE_SUBDIR', '/' . str_replace( basename( __FILE__ ), '', WPSUBTITLE_BASENAME ) );
define( 'WPSUBTITLE_URL', plugins_url( WPSUBTITLE_SUBDIR ) );
define( 'WPSUBTITLE_DIR', plugin_dir_path( __FILE__ ) );

// Includes
include_once( WPSUBTITLE_DIR . 'includes/class-api.php' );
include_once( WPSUBTITLE_DIR . 'includes/subtitle.php' );
include_once( WPSUBTITLE_DIR . 'includes/deprecated.php' );
include_once( WPSUBTITLE_DIR . 'includes/shortcode.php' );
include_once( WPSUBTITLE_DIR . 'includes/rest.php' );
include_once( WPSUBTITLE_DIR . 'includes/compat/wordpress-seo.php' );
include_once( WPSUBTITLE_DIR . 'includes/compat/seopress.php' );
include_once( WPSUBTITLE_DIR . 'includes/compat/woocommerce.php' );

// Include admin-only functionality
if ( is_admin() ) {
	require_once( WPSUBTITLE_DIR . 'admin/admin.php' );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		// Load AJAX functions here if required...
	} else {
		require_once( WPSUBTITLE_DIR . 'admin/pointers.php' );
	}
}

add_action( 'plugins_loaded', array( 'WPSubtitle', 'load' ) );
add_action( 'init', array( 'WPSubtitle', '_add_default_post_type_support' ), 5 );

// Default subtitle filters
add_filter( 'wps_subtitle', 'wptexturize' );
add_filter( 'wps_subtitle', 'trim' );

class WPSubtitle {

	/**
	 * API
	 *
	 * @var  WP_Subtitle_API|null
	 */
	private static $api = null;

	/**
	 * REST API
	 *
	 * @since  3.1
	 *
	 * @var  WPSubtitle_REST|null
	 */
	private static $rest = null;

	/**
	 * WP SEO (plugin compatibility)
	 *
	 * @since  3.1
	 *
	 * @var  WPSubtitle_WPSEO|null
	 */
	private static $wpseo = null;

	/**
	 * SEOPress (plugin compatibility)
	 *
	 * @since  3.4
	 *
	 * @var  WPSubtitle_SEOPress|null
	 */
	private static $seopress = null;

	/**
	 * WooCommerce
	 *
	 * @since  3.1
	 *
	 * @var  WPSubtitle_WooCommerce|null
	 */
	private static $woocommerce = null;

	/**
	 * Load
	 *
	 * @since  3.1
	 */
	public static function load() {

		self::$api = new WP_Subtitle_API();
		self::$rest = new WPSubtitle_REST();
		self::$wpseo = new WPSubtitle_WPSEO();
		self::$seopress = new WPSubtitle_SEOPress();
		self::$woocommerce = new WPSubtitle_WooCommerce();

		self::$api->setup_hooks();

	}

	/**
	 * Add Default Post Type Support
	 *
	 * @since  2.0
	 * @internal
	 */
	public static function _add_default_post_type_support() {

		add_post_type_support( 'page', 'wps_subtitle' );
		add_post_type_support( 'post', 'wps_subtitle' );
		add_post_type_support( 'revision', 'wps_subtitle' );

	}

	/**
	 * Get Supported Post Types
	 *
	 * @since  2.0
	 *
	 * @return  array  Array of supported post types.
	 */
	public static function get_supported_post_types() {
		$post_types = (array) get_post_types( array(
			'_builtin' => false
		) );
		$post_types = array_merge( $post_types, array( 'post', 'page', 'revision' ) );
		$supported = array();
		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'wps_subtitle' ) ) {
				$supported[] = $post_type;
			}
		}
		return $supported;
	}

	/**
	 * Is Supported Post Type
	 *
	 * @since  2.3
	 *
	 * @param   string   $post_type  Post Type.
	 * @return  boolean
	 */
	public static function is_supported_post_type( $post_type ) {
		$post_types = self::get_supported_post_types();
		if ( in_array( $post_type, $post_types ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get the Subtitle
	 *
	 * @since  2.0
	 *
	 * @uses  WP_Subtitle::get_subtitle()
	 *
	 * @param   int|object  $post  Post ID or object.
	 * @return  string             The filtered subtitle meta value.
	 */
	public static function get_the_subtitle( $post = 0 ) {

		$subtitle = new WP_Subtitle( $post );

		return $subtitle->get_subtitle();

	}

	/**
	 * Get Post Meta
	 *
	 * @since  2.0
	 * @internal
	 *
	 * @uses  WP_Subtitle::get_raw_subtitle()
	 *
	 * @param   int|object  $post  Post ID or object.
	 * @return  string             The subtitle meta value.
	 */
	public static function _get_post_meta( $post = 0 ) {

		$subtitle = new WP_Subtitle( $post );

		return $subtitle->get_raw_subtitle();

	}

	/**
	 * Get Post Meta Key
	 *
	 * @since  2.5.x
	 * @internal
	 *
	 * @param   int     $post  Post ID.
	 * @return  string         The subtitle meta key.
	 */
	public static function _get_post_meta_key( $post_id = 0 ) {

		return apply_filters( 'wps_subtitle_key', 'wps_subtitle', $post_id );

	}

}
