
/**
 * @package     WP Subtitle
 * @subpackage  Gutenberg Subtitle Block
 */

( function( blocks, i18n, element ) {

	var __ = i18n.__;
	var Editable = blocks.Editable;
	var el = element.createElement;

	blocks.registerBlockType( 'wp-subtitle/subtitle', {

		title      : __( 'Subtitle', 'wp-subtitle' ),
		icon       : 'heading',
		category   : 'common',
		useOnce    : true,

		attributes : {
			content : {
				type     : 'string',
				source   : 'meta',
				selector : 'h2',
				meta     : 'wps_subtitle',
			},
		},

		edit : function( props ) {

			var content = props.attributes.content;

			function onChangeContent( event ) {
				props.setAttributes( { content : event[0] } );
			}

			return el( 'h2', {}, el(
				Editable,
				{
					onChange    : onChangeContent,
					value       : content,
					placeholder : __( 'Enter subtitle here', 'wp-subtitle' )
				}
			) );

		},

		save : function( props ) {
			return null;
		},

	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element
);
