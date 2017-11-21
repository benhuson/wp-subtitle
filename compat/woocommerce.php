<?php

/**
 * @package     WP Subtitle
 * @subpackage  WooCommerce
 *
 * Compatibility for the WooCommerce plugin:
 * https://wordpress.org/plugins/woocommerce/
 */

class WPSubtitle_WooCommerce {

	/**
	 * Constructor
	 *
	 * @internal  Do not create multiple instances.
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'add_product_post_type_support' ) );
		add_action( 'woocommerce_single_product_summary' , array( $this, 'single_product_summary' ), 6 );
		add_action( 'woocommerce_shop_loop_item_title' , array( $this, 'shop_loop_item_title' ) );

	}

	/**
	 * Add Product Post Type Support
	 *
	 * @internal  Private. Called via the `init` action.
	 */
	public function add_product_post_type_support() {

		add_post_type_support( 'product', 'wps_subtitle' );

	}

	/**
	 * Single Product Summary
	 *
	 * @internal  Private. Called via the `woocommerce_single_product_summary` action.
	 */
	public function single_product_summary() {

		the_subtitle( '<h2 class="product_subtitle entry-subtitle wp-subtitle">', '</h2>' );

	}

	/**
	 * Shop Loop Item Title
	 *
	 * @internal  Private. Called via the `woocommerce_shop_loop_item_title` action.
	 */
	public function shop_loop_item_title() {

		the_subtitle( '<p class="wp-subtitle">', '</p>' );

	}

}
