=== WP Subtitle ===
Contributors: husobj, husani
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SLZUF4XJTS4E6
Tags: subtitle, content, title, subheading, subhead, alternate title
Requires at least: 3.7
Tested up to: 4.8.1
Stable tag: 3.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Add subtitles (subheadings) to your pages, posts or custom post types.

== Description ==

The WP Subtitle plugin allows your pages and posts to contain a subtitle.  Also called a sub-heading, this this short line of text is meant to appear beneath a post's (or page's) title, but can be inserted in your template wherever you choose.

`<?php the_subtitle(); ?>` is used for inside The Loop. If you wish to get a page/post's subtitle outside The Loop, use `<?php get_the_subtitle( $post ); ?>`, where $post is a post object or ID ($post->ID).

= Parameters =

Just like WP's built-in `<?php the_title(); ?>` method, `<?php the_subtitle(); ?>` tag accepts three parameters:

**$before**  
*(string)* Text to place before the subtitle. Defaults to "".

**$after**  
*(string)* Text to place after the subtitle. Defaults to "".

**$echo**  
*(boolean)* If true, display the subtitle in HTML. If false, return the subtitle for use in PHP. Defaults to true.

Things are slightly different in `<?php get_the_subtitle(); ?>`:

**$post**  
*(int|object)* Post, page or custom post type object or ID.

**$before**  
*(string)* Text to place before the subtitle. Defaults to "".

**$after**  
*(string)* Text to place after the subtitle. Defaults to "".

**$echo**  
*(boolean)* If true, display the subtitle in HTML. If false, return the subtitle for use in PHP. Defaults to true.

For full details on the template tags and their arguments, [view the documentation here](https://github.com/benhuson/wp-subtitle/wiki).

By default, subtitle are supported by both posts and pages. To add support for custom post types use add_post_type_support( 'my_post_type', 'wps_subtitle' ).

== Installation ==

1. Upload the WP Subtitle plugin to your WordPress site in the `/wp-content/plugins` folder or install via the WordPress admin.
2. Activate it from the Wordpress plugin admin screen.
3. Edit your page and/or post template and use the `<?php the_subtitle(); ?>` template tag where you'd like the subtitle to appear.

For full details on the template tags and their arguments, [view the documentation here](https://github.com/benhuson/wp-subtitle/wiki).

== Frequently Asked Questions ==

= What does WP Subtitle do? =

The plugin adds a Subtitle field when editing posts or pages. The subtitle is stores as a custom field (post meta data) and can be output using template tags.

= Where does WP Subtitle store the subtitles? =

All subtitles are stored as post meta data. Deactivating this plugin will not remove those fields.

= How do I add the subtitle to my pages? =

Refer to [the documentation](https://github.com/benhuson/wp-subtitle/wiki).

= How do I add support for custom post types? =

To add support for custom post types use add_post_type_support( 'my_post_type', 'wps_subtitle' ):

`
function my_wp_subtitle_page_part_support() {
	add_post_type_support( 'my_post_type', 'wps_subtitle' );
}
add_action( 'init', 'my_wp_subtitle_page_part_support' );
`

= Where can I get help? =

Please post support requests and questions in the [WordPress.org Support](http://wordpress.org/support/plugin/wp-subtitle) forum.

= How should I report a bug? =

Please submit bugs/errors directly to the [GitHub Issues](https://github.com/benhuson/wp-subtitle/issues) list.

= How can I contribute code? =

The plugin is [hosted on GitHub](https://github.com/benhuson/wp-subtitle) and pull requests are welcome.

== Screenshots ==

1. Edit post screen (WordPress 3.5+ and WP Title 2.2+)
1. Edit post screen (for earlier versions of WordPress or using the 'wps_subtitle_use_meta_box' filter)
2. A single page showing a subtitle

== Changelog ==

= Unreleased =

= 3.0 =
* Make `wps_subtitle` available via WordPress REST API.

= 2.9.1 =
* Fix preview not rendering correct template and other post meta.

= 2.9 =
* Add support for post revisions. Props [Fabian Marz](https://github.com/fabianmarz).
* As of WordPress 4.3 no need to esc_attr() AND htmlentities() - can mess up special characters.

= 2.8.1 =
* Fix PHP warning - `get_admin_subtitle_value()` should be declared static.

= 2.8 =
* Allow subtitle to contain HTML (same as main post title ).
* Add `wps_default_subtitle` filter.
* Use `WP_Subtitle` class to validate saving of subtitle in the admin.

= 2.7.1 =
* Fix incorrect post ID reference preventing subtitle from saving.

= 2.7 =
* Trim subtitle by default.
* Apply wptexturize() on subtitle.
* Use WP_Subtitle class to manage post subtitle.

= 2.6 =
* Security Update: Sanitize `$_REQUEST` and `$_GET` when establishing post type in the admin.
* Added quick edit support for subtitle. Props [Fabian Marz](https://github.com/fabianmarz) and [sun](https://github.com/sun).
* Allow subtitle post meta key to be filtered using `wps_subtitle_key`.
* Add German translation. Props [hatsumatsu](https://github.com/hatsumatsu).

= 2.5 =
* Add [wp_subtitle] shortcode.
* Do not use variable for textdomain - causes issues for parsers.
* Declare methods as public or private.

= 2.4.1 =
* Fix PHP notice warning on 404 error page. Props Jay Williams.
* Add a little space above subtitle field when below title field in admin.

= 2.4 =
* Add subtitle admin column.

= 2.3.2 =
* Show subtitle admin field when adding new post. Props Gabriel Doty.

= 2.3.1 =
* Security Update: Ensure subtitles are sanitized when saving.

= 2.3 =
* Prevent subtitle fields from displaying on post types for which support has not been added using add_post_type_support(). Previously the fields were displayed but the subtitle would not be saved.
* Escape subtitle admin field value - fixes issues with subtitles with quotes.

= 2.2 =
* Added 'wps_subtitle_use_meta_box' filter to allow the edit field to be displayed in a meta box (the old way).
* Moved subtitle field from meta box to below title field in WordPress 3.5+ (props Tor Morten)

= 2.1 =
* Ready for translation - .pot file added.
* Added deprecated function warnings if WP_DEBUG enabled.
* Fix static method warnings.
* Only include admin functionality when needed.

= 2.0.1 =
* Use `<?php` instead of just `<?`.
* Break out some of the code into separate functions.

= 2.0 =
* Added custom post type support - use add_post_type_support( '{post_type}', 'wps_subtitle' ).
* Added 'wps_meta_box_title' filter.
* Added 'wps_subtitle' filter.
* Added 'wps_subtitle_field_description' filter.
* Fixed bug in more recent versions of WordPress.

= 1.0 =
* First version.

== Upgrade Notice ==

= 3.0 =
* Make `wps_subtitle` available via WordPress REST API.

= 2.9.1 =
Fix preview not rendering correct template and other post meta.

= 2.9 =
Add support for revisions and fix special character encoding.

= 2.8.1 =
Fix PHP warning - `get_admin_subtitle_value()` should be declared static.

= 2.8 =
Allow subtitle to contain HTML (same as main post title ) and add `wps_default_subtitle` filter.

= 2.7.1 =
Fix incorrect post ID reference preventing subtitle from saving.

= 2.7 =
Trim subtitle and wptexturize() by default.

= 2.6 =
Added quick edit support for subtitle. Security Update: Sanitize `$_REQUEST` and `$_GET` when establishing post type in the admin.

= 2.5 =
Add [wp_subtitle] shortcode. Do not use variable for textdomain - causes issues for parsers.

= 2.4.1 =
Fix PHP notice warning on 404 error page.

= 2.4 =
Add subtitle admin column.

= 2.3.1 =
Security Update: Ensure subtitles are sanitized when saving.

= 2.3 =
Prevent subtitle fields from displaying on unsupported post types and fix issue with quotes in subtitles.

= 2.2 =
Subtitle field moved to below title field (only in WordPress 3.5+)

= 2.1 =
Fixed static method warnings and only load admin functionality when needed.

= 2.0 =
Added custom post type support and support for more recent versions of WordPress.

= 1.0 =
Initial release.
