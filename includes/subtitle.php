<?php

/**
 * @package     WP Subtitle
 * @subpackage  Subtitle Class
 */

class WP_Subtitle {

	/**
	 * Post ID
	 *
	 * @var  int
	 */
	private $post_id = 0;

	/**
	 * Constructor
	 *
	 * @param  int|WP_Post  $post  Post object or ID.
	 */
	public function __construct( $post ) {

		// Post ID
		if ( is_a( $post, 'WP_Post' ) ) {
			$this->post_id = absint( $post->ID );
		} else {
			$this->post_id = absint( $post );
		}

	}

}
