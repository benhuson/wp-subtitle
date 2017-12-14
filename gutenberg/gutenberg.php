<?php

/**
 * @package     WP Subtitle
 * @subpackage  Gutenberg
 */

class WPSubtitle_Gutenberg {

	/**
	 * Constructor
	 *
	 * @internal  Do not create multiple instances.
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );

	}

	public function register_meta() {

		register_meta( 'page', 'wps_subtitle', array(
			'show_in_rest' => true,
			'single'       => true
		) );

	}

	/**
	 * Enqueue Block Editor Assets
	 *
	 * @internal  Called via the `enqueue_block_editor_assets` action.
	 */
	public function enqueue_block_editor_assets() {

		wp_enqueue_script(
			'wp-subtitle-gutenberg-block',
			plugins_url( 'block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
		);

	}

}
