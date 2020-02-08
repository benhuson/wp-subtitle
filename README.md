WP Subtitle
===========

Add subtitles (subheadings) to your pages, posts or custom post types.

The WP Subtitle plugin allows your pages and posts to contain a subtitle.  Also called a sub-heading, this this short line of text is meant to appear beneath a post's (or page's) title, but can be inserted in your template wherever you choose.

The subtitle can be inserted into your theme template files (or plugin) using the following API:

### Display The Subtitle

All parameters are optional. If 'post_id' is omitted then the current post ID in the loop is used.

PHP Code:

```
do_action( 'plugins/wp_subtitle/the_subtitle', array(
    'before'        => '<p class="subtitle">',
    'after'         => '</p>',
    'post_id'       => get_the_ID(),
    'default_value' => ''
) );
```

Output:

`<p class="subtitle">My Post Subtitle</p>`

### Get The Subtitle

All parameters are optional. If 'post_id' is omitted then the current post ID in the loop is used.

A default value can be supplied as the second parameter for `apply_filters`. This will be used if the post does not have a subtitle. Leave as an empty string to return an empty string if the post does not have a subtitle.

PHP Code:

```
$subtitle = apply_filters( 'plugins/wp_subtitle/get_subtitle', '', array(
    'before'  => '<p class="subtitle">',
    'after'   => '</p>',
    'post_id' => get_the_ID()
) );
```

Result:

`$subtitle = '<p class="subtitle">My Post Subtitle</p>'`

### Parameters

The array of arguments accepted for the `plugins/wp_subtitle/the_subtitle` action and `plugins/wp_subtitle/get_subtitle` filter are:

**before**  
*(string)* Text to place before the subtitle if one exists. Defaults to an empty string.

**after**  
*(string)* Text to place after the subtitle if one exists. Defaults to to an empty string.

**post_id**  
*(integer)* Post, page or custom post type ID.

**default_value**  
*(string)* Only used by the `plugins/wp_subtitle/the_subtitle` action, allows you to specify a default subtitle to display if the post does not have one. For the `plugins/wp_subtitle/get_subtitle` filter the second parameter of `apply_filters` should be used instead. Defaults to to an empty string.

### Post Type Support

By default, subtitle are supported by both posts and pages. To add support for custom post types add teh following to your theme functions file or plugin:

`add_post_type_support( 'my_post_type', 'wps_subtitle' )`

### WooCommerce Plugin Support

Subtitles can automatically be added to your WooCommerce products without needing to make template changes. In the admin go to WooCommerce > Settings > Products where you can choose to:

 - Enable Product Subtitles
 - Display the subtitle on single product pages
 - Display the subtitle on product archives (category pages)

### Yoast SEO and SEOPress Plugin Support

The plugin allows you to include the subtitle in your meta titles and descriptions via the [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/) and [SEOPress](https://wordpress.org/plugins/wp-seopress/) plugins.

Similar to the Yoast `%%title%%` placeholder which inserts the post title, you can use `%%wps_subtitle%%`.

There are also addition placeholders and filters to allow to to customize seperators for the subtitle.

For more information, [view the SEO support documentation here](https://github.com/benhuson/wp-subtitle/wiki/Yoast-SEO-Plugin-Support).

Installation
------------

1. Upload the WP Subtitle plugin to your WordPress site in the `/wp-content/plugins` folder or install via the WordPress admin.
1. Activate it from the Wordpress plugin admin screen.
1. Use the API to display the subtitle in your theme.

For full details on the API and how to display the subtitle, [view the documentation here](https://github.com/benhuson/wp-subtitle/wiki).

Frequently Asked Questions
--------------------------

__What does WP Subtitle do?__  

The plugin adds a Subtitle field when editing posts, pages or custom post types. The subtitle is stored as a custom field (post meta data) and can be output using API actions and filters.

__Where does WP Subtitle store the subtitles?__  

All subtitles are stored as post meta data. Deactivating this plugin will not remove those fields.

__Compatibility with WordPress 5.0+__  

In the new editor in WordPress 5.0 the subtitle is editable via a panel in the sidebar (like excerpts).

__How do I add the subtitle to my pages?__  

Refer to [the documentation](https://github.com/benhuson/wp-subtitle/wiki).

__How do I add support for custom post types?__  

To add support for custom post types add the following to your theme functions file or plugin:

```
function my_wp_subtitle_page_part_support() {
	add_post_type_support( 'my_post_type', 'wps_subtitle' );
}
add_action( 'init', 'my_wp_subtitle_page_part_support' );
```

__Where can I get help?__  

Please post support requests and questions in the [WordPress.org Support](http://wordpress.org/support/plugin/wp-subtitle) forum.

__How should I report a bug?__  

Please submit bugs/errors directly to the [GitHub Issues](https://github.com/benhuson/wp-subtitle/issues) list.

__How can I contribute code?__  

The plugin is [hosted on GitHub](https://github.com/benhuson/wp-subtitle) and pull requests are welcome.

Upgrade Notice
--------------

### 3.4
Added support for the SEOPress plugin and updating the subtitle via the REST API.

### 3.3.1
Fixed broken closing H2 tag for WooCommerce subtitle.

### 3.3
New API for getting and displaying the subtitle using `do_action( 'plugins/wp_subtitle/the_subtitle' )` and `apply_filters( 'plugins/wp_subtitle/get_subtitle', '' )`. Please see the documentation.

### 3.2
Fix WordPress 5.0 compatibility.

### 3.1
WooCommerce compatibility: Go to `WooCommerce > Settings > Products > Display` for settings. Yoast SEO compatibility: Added `%%wps_subtitle%%` placeholders. Gutenberg compatibility: Use metabox UI.

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
