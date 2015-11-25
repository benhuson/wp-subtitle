/**
 * @package     WP Subtitle
 * @subpackage  JavaScript > Admin Edit
 */

( function ( $, inlineEditPost ) {

	// inlineEditPost does not invoke any events, but does ensure to stop
	// propagation to all other event handlers; swap it out.
	inlineEditPost.editPreWpSubtitle = inlineEditPost.edit;
	inlineEditPost.edit = function ( link ) {
		// Invoke original edit event handler.
		this.editPreWpSubtitle( link );

		var $outputContext = $( link ).closest( 'tr[id]' );
		var $inputContext = $outputContext.siblings( '.inline-edit-row' );
		$inputContext.find( ':input[name="wps_subtitle"]' ).val(
			$outputContext.find( '[data-wps_subtitle]' ).data( 'wps_subtitle' )
		);
		return false;
	}

} ) ( jQuery, inlineEditPost );
