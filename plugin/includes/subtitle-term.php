<?php

/**
 * @package     WP Subtitle
 * @subpackage  Term Class
 */

class WP_Subtitle_Term {

	/**
	 * Object ID
	 *
	 * @var  integer
	 */
	protected $term_id = 0;

	/**
	 * Constructor
	 *
	 * @param  integer|WP_Term  $term  Term object or ID.
	 */
	public function __construct( $term ) {

		if ( is_a( $term, 'WP_Term' ) ) {
			$this->object_id = absint( $term->term_id );
		} else {
			$this->object_id = absint( $term );
		}

	}

	/**
	 * Get Meta Value
	 *
	 * @return  string  The subtitle meta value.
	 */
	public function get_meta_value() {

		return get_term_meta( $this->object_id, $this->get_meta_key(), true );

	}

	/**
	 * Update Subtitle
	 *
	 * @param   string                 $subtitle  Subtitle.
	 * @return  integer|WP_Error|bool             Meta ID if new entry. True if updated, false if not updated or the same
	 *                                            as current value.  WP_Error when term_id is ambiguous between taxonomies.
	 */
	public function update_subtitle( $subtitle ) {

		$subtitle = trim( sanitize_text_field( $subtitle ) );

		if ( '' == $subtitle ) {
			return $this->delete_subtitle();
		}

		return update_term_meta( $this->object_id, $this->get_meta_key(), $subtitle );

	}

	/**
	 * Delete Subtitle
	 *
	 * @return  boolean  True if deleted, false if failed.
	 */
	protected function delete_subtitle() {

		return delete_term_meta( $this->object_id, $this->get_meta_key() );

	}

	/**
	 * Get Meta Key
	 *
	 * @return  string  The subtitle meta key.
	 */
	protected function get_meta_key() {

		return 'wps_subtitle';

	}

	/**
	 * Current User Can Edit
	 *
	 * @return  boolean
	 */
	public function current_user_can_edit() {

		$term = get_term( $this->object_id );
		$tax = get_taxonomy( $term->taxonomy );

		return current_user_can( $tax->cap->edit_terms );

	}

}
