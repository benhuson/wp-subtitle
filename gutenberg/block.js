
/**
 * @package     WP Subtitle
 * @subpackage  Gutenberg Subtitle Block
 */

( function( blocks, i18n, element ) {

	var el = element.createElement;
	var __ = i18n.__;
	var Editable = blocks.Editable;
	var children = blocks.source.children;

	blocks.registerBlockType( 'wp-subtitle/subtitle', {

		title      : __( 'Subtitle', 'wp-subtitle' ),
		icon       : 'heading',
		category   : 'common',
		useOnce    : true,

		attributes : {
			content : {
				type     : 'array',
				source   : 'children',
				selector : 'h2',
			},
		},

		edit : function( props ) {

			var content = props.attributes.content;
			var focus = props.focus;

			function onChangeContent( newContent ) {
				props.setAttributes( { content : newContent } );
			}

			return el(
				Editable,
				{
					tagName   : 'h2',
					className : props.className,
					onChange  : onChangeContent,
					value     : content,
					focus     : focus,
					onFocus   : props.setFocus
				}
			);

		},

		save: function( props ) {
			return el( 'h2', {}, props.attributes.content );
		},

	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element
);
