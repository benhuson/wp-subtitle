<?php

/**
 * @package     WP Subtitle
 * @subpackage  WooCommerce
 *
 * @since  3.1
 *
 * Compatibility for the WooCommerce plugin:
 * https://wordpress.org/plugins/woocommerce/
 */

class WPSubtitle_WooCommerce {

	/**
	 * Constructor
	 *
	 * @since  3.1
	 *
	 * @internal  Do not create multiple instances.
	 */
	public function __construct() {

		if ( 'yes' == get_option( 'wp_subtitle_woocommerce_enabled' ) ) {

			add_action( 'init', array( $this, 'add_product_post_type_support' ) );

			if ( 'yes' == get_option( 'wp_subtitle_woocommerce_show_on_single' ) ) {
				add_action( 'woocommerce_single_product_summary' , array( $this, 'single_product_summary' ), 6 );
			}

			if ( 'yes' == get_option( 'wp_subtitle_woocommerce_show_in_loop' ) ) {
				add_action( 'woocommerce_shop_loop_item_title' , array( $this, 'shop_loop_item_title' ) );
			}

		}

		add_filter( 'woocommerce_product_settings' , array( $this, 'product_settings' ) );

	}

	/**
	 * Add Product Post Type Support
	 *
	 * @since  3.1
	 *
	 * @internal  Private. Called via the `init` action.
	 */
	public function add_product_post_type_support() {

		add_post_type_support( 'product', 'wps_subtitle' );

	}

	/**
	 * Single Product Summary
	 *
	 * @since  3.1
	 *
	 * @internal  Private. Called via the `woocommerce_single_product_summary` action.
	 */
	public function single_product_summary() {

		do_action( 'plugins/wp_subtitle/the_subtitle', array(
			'before' => '<h2 class="product_subtitle entry-subtitle wp-subtitle">',
			'after'  => '</h2>'
		) );

	}

	/**
	 * Shop Loop Item Title
	 *
	 * @since  3.1
	 *
	 * @internal  Private. Called via the `woocommerce_shop_loop_item_title` action.
	 */
	public function shop_loop_item_title() {

		do_action( 'plugins/wp_subtitle/the_subtitle', array(
			'before' => '<p class="woocommerce-loop-product__subtitle wp-subtitle">',
			'after'  => '</p>'
		) );

	}

	/**
	 * Product Settings
	 *
	 * @since  3.1
	 *
	 * @param   array  $settings  Settings.
	 * @return  array             Settings.
	 *
	 * @internal  Private. Called via the `woocommerce_product_settings` filter.
	 */
	public function product_settings( $settings ) {

		$subtitle_settings = array(

			array(
				'title' => __( 'WP Subtitle', 'wp-subtitle' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'wp_subtitle_options'
			),

			array(
				'title'   => __( 'Enable Product Subtitles', 'wp-subtitle' ),
				'desc'    => __( 'Add subtitle field to product edit screen', 'wp-subtitle' ),
				'id'      => 'wp_subtitle_woocommerce_enabled',
				'default' => 'no',
				'type'    => 'checkbox',
			),

			array(
				'title'         => __( 'Subtitle Display', 'wp-subtitle' ),
				'desc'          => __( 'Show on single product pages', 'wp-subtitle' ),
				'id'            => 'wp_subtitle_woocommerce_show_on_single',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start'
			),

			array(
				'desc'          => __( 'Show on product archives', 'wp-subtitle' ),
				'id'            => 'wp_subtitle_woocommerce_show_in_loop',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'end'
			),

			array(
				'type' => 'sectionend',
				'id'   => 'wp_subtitle_options'
			)

		);

		return array_merge( $settings, $subtitle_settings );

	}

}
