
/**
 * @package     WP Subtitle
 * @subpackage  JavaScript > Pointers
 */

jQuery( document ).ready( function( $ ) {

	function wps_subtitle_open_pointer( i ) {
		pointer = wpsSubtitlePointer.pointers[ i ];
		options = $.extend( pointer.options, {
			close : function() {
				$.post( ajaxurl, {
				    pointer : pointer.pointer_id,
				    action  : 'dismiss-wp-pointer'
				} );
			}
		});

		$( pointer.target ).pointer( options ).pointer( 'open' );
	}

	wps_subtitle_open_pointer( 0 );

} );
