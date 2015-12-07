/**
 * @package     WP Subtitle
 * @subpackage  JavaScript > Admin Edit
 */

( function ( $, inlineEditPost ) {

	// inlineEditPost does not invoke any events, but does ensure to stop
	// propagation to all other event handlers; swap it out.
	inlineEditPost.editPreWpSubtitle = inlineEditPost.edit;
	inlineEditPost.edit = function ( id ) {

		// Invoke original edit event handler.
		this.editPreWpSubtitle.apply( this, arguments );

		// Get the post ID
		var post_id = 0;
		if ( typeof( id ) == 'object' ) {
			post_id = parseInt( this.getId( id ) );
		}

		if ( post_id > 0 ) {

			// Define the edit row
			var edit_row = $( '#edit-' + post_id );
			var post_row = $( '#post-' + post_id );

			// Get the data
			var subtitle = $( '.column-wps_subtitle', post_row ).find( '[data-wps_subtitle]' ).data( 'wps_subtitle' );

			// Populate the data
			$( ':input[name="wps_subtitle"]', edit_row ).val( subtitle );

		}

	}

} ) ( jQuery, inlineEditPost );
