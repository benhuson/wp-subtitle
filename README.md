WP Subtitle
===========

Add subtitles (subheadings) to your pages, posts or custom post types.

The WP Subtitle plugin allows your pages and posts to contain a subtitle.  Also called a sub-heading, this this short line of text is meant to appear beneath a post's (or page's) title, but can be inserted in your template wherever you choose.

`<?php the_subtitle(); ?>` is used for inside The Loop. If you wish to get a page/post's subtitle outside The Loop, use `<?php get_the_subtitle( $post ); ?>`, where $post is a post object or ID ($post->ID).

### Parameters

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

Installation
------------

1. Upload the WP Subtitle plugin to your WordPress site in the `/wp-content/plugins` folder or install via the WordPress admin.
1. Activate it from the Wordpress plugin admin screen.
1. Edit your page and/or post template and use the `<?php the_subtitle(); ?>` template tag where you'd like the subtitle to appear.

For full details on the template tags and their arguments, [view the documentation here](https://github.com/benhuson/wp-subtitle/wiki).

Frequently Asked Questions
--------------------------

__What does WP Subtitle do?__  

The plugin adds a Subtitle field when editing posts or pages. The subtitle is stores as a custom field (post meta data) and can be output using template tags.

__Where does WP Subtitle store the subtitles?__  

All subtitles are stored as post meta data. Deactivating this plugin will not remove those fields.

__How do I add the subtitle to my pages?__  

Refer to [the documentation](https://github.com/benhuson/wp-subtitle/wiki).

__How do I add support for custom post types?__  

To add support for custom post types use add_post_type_support( 'my_post_type', 'wps_subtitle' ):

`
function my_wp_subtitle_page_part_support() {
	add_post_type_support( 'my_post_type', 'wps_subtitle' );
}
add_action( 'init', 'my_wp_subtitle_page_part_support' );
`

__Where can I get help?__  

Please post support requests and questions in the [WordPress.org Support](http://wordpress.org/support/plugin/wp-subtitle) forum.

__How should I report a bug?__  

Please submit bugs/errors directly to the [GitHub Issues](https://github.com/benhuson/wp-subtitle/issues) list.

__How can I contribute code?__  

The plugin is [hosted on GitHub](https://github.com/benhuson/wp-subtitle) and pull requests are welcome.

Upgrade Notice
--------------

### 3.0
Make `wps_subtitle` available via WordPress REST API.

### 2.9.1
Fix preview not rendering correct template and other post meta.

### 2.9
Add support for revisions and fix special character encoding.

### 2.8.1
Fix PHP warning - `get_admin_subtitle_value()` should be declared static.

### 2.8
Allow subtitle to contain HTML (same as main post title ) and add `wps_default_subtitle` filter.

### 2.7.1
Fix incorrect post ID reference preventing subtitle from saving.

### 2.7
Trim subtitle and wptexturize() by default.

### 2.6
Added quick edit support for subtitle. Security Update: Sanitize `$_REQUEST` and `$_GET` when establishing post type in the admin.

### 2.5
Add [wp_subtitle] shortcode. Do not use variable for textdomain - causes issues for parsers.

### 2.4.1
Fix PHP notice warning on 404 error page.

### 2.4
Add subtitle admin column.

### 2.3.1
Security Update: Ensure subtitles are sanitized when saving.

### 2.3
Prevent subtitle fields from displaying on unsupported post types and fix issue with quotes in subtitles.

### 2.2
Subtitle field moved to below title field (only in WordPress 3.5+)

### 2.1
Fixed static method warnings and only load admin functionality when needed.

### 2.0
Added custom post type support and support for more recent versions of WordPress.

### 1.0
Initial release.

Changelog
---------

View a list of all plugin changes in [CHANGELOG.md](https://github.com/benhuson/wp-subtitle/blob/master/CHANGELOG.md).
