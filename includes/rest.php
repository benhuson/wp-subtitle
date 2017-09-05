<?php

/**
 * @package     WP Subtitle
 * @subpackage  REST API
 *
 * @since  3.0
 */

class WPSubtitle_REST {

	/**
	 * Constructor
	 *
	 * @since  3.0
	 *
	 * @internal  Do not create multiple instances.
	 */
	public function __construct() {

		add_action( 'rest_api_init', array( $this, 'register_rest_field' ) );

	}

	/**
	 * Register REST Field
	 *
	 * @since  3.0
	 *
	 * @internal  Called via the `rest_api_init` action.
	 */
	public function register_rest_field() {

		$post_types = WPSubtitle::get_supported_post_types();

		foreach ( $post_types as $post_type ) {

			register_rest_field( $post_types, 'wps_subtitle', array(
				'get_callback'    => array( $this, 'get_rest_field' ),
				'update_callback' => null,
				'schema'          => null
			) );

		}

	}

	/**
	 * Get REST Field
	 *
	 * @since  3.0
	 *
	 * @internal  Called via register_rest_field() callback.
	 *
	 * @param   array            $object      Current post details.
	 * @param   string           $field_name  Name of field.
	 * @param   WP_REST_Request  $request     Current request.
	 * @return  string                        Subtitle
	 */
	public function get_rest_field( $object, $field_name, $request ) {

		$subtitle = new WP_Subtitle( $object['id'] );

		return $subtitle->get_raw_subtitle();

	}

}
