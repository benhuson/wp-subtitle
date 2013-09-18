=== WP Subtitle ===
Contributors: husani, husobj
Tags: subtitle, content, title, subheading, subhead, alternate title
Requires at least: 3.0
Tested up to: 3.6.1
Stable tag: 2.0.1
License: GPL2

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

= Where can I get help? =

Please post support requests and questions in the [WordPress.org Support](http://wordpress.org/support/plugin/wp-subtitle) forum.

= How should I report a bug? =

Please submit bugs/errors directly to the [GitHub Issues](https://github.com/benhuson/wp-subtitle/issues) list.

= How can I contribute code? =

The plugin is [hosted on GitHub](https://github.com/benhuson/wp-subtitle) and pull requests are welcome.

== Screenshots ==

1. Edit post screen
2. A single page showing a subtitle

== Changelog ==

= 2.0.1 =
* Use `<?php` instead of just `<?`.
* Break out some of the code into separate functions. 

= 2.0 =
* Added custom post type support - use add_post_type_support( '{post_type}', 'wps_subtitle' ).
* Fixed bug in more recent versions of WordPress.
* Added 'wps_meta_box_title' filter.
* Added 'wps_subtitle' filter.
* Added 'wps_subtitle_field_description' filter.

= 1.0 =
* First version.

== Upgrade Notice ==

= 2.0 =
* Added custom post type support and support for more recent versions of WordPress.

= 1.0 =
* Initial release.