=== WP Subtitle ===
Contributors: husani
Tags: subtitle, content, title, subheading, subhead, alternate title
Donate link: http://www.husani.com/ventures/wordpress-plugins/wp-subtitle/#donate
Requires at least: 2.7
Tested up to: 2.8
Stable tag: 1.0

Add subtitles (subheadings) to your pages and posts.

== Description ==

The WP Subtitle plugin allows your pages and posts to contain a subtitle.  Also called a sub-heading, this this short line of text is meant to appear beneath a post's (or page's) title, but can be inserted in your template wherever you choose.

&lt;?the_subtitle()?&gt; is used for inside The Loop -- if you wish to get a page/post's subtitle outside The Loop, use &lt;?get_the_subtitle($id)?&gt;, where $id is the page or post's ID ($post->ID).

Just like WP's built-in &lt;?the_title()?&gt; method, &lt;?the_subtitle()?&gt; tag accepts three parameters:

$before
(string) Text to place before the subtitle, defaults to "".

$after
(string) Text to place after the subtitle, defaults to "".

$display
(boolean) If true, display the subtitle in XHTML; if false, return the subtitle for use in PHP.  Defaults to true.

Things are slightly different in &lt;?get_the_subtitle()?&gt;:

$id
(integer) Post (or page) ID.

$before
(string) Text to place before the subtitle, defaults to "".

$after
(string) Text to place after the subtitle, defaults to "".

$display
(boolean) If true, display the subtitle in XHTML; if false, return the subtitle for use in PHP.  Defaults to true.

Changelog:

* 1.0:
    - First version


== Installation ==

1.  Upload the WP Subtitle plugin to your blog (YOURBLOG/wp-content/plugins) and activate it using the Wordpress plugin admin screen.
2.  Edit your page and/or post template and add &lt;?the_subtitle()?&gt; or &lt;?get_the_subtitle()?&gt; where you'd like the subtitle to appear.  Pass arguments per above instructions.

== Frequently Asked Questions ==

= How does WP Subtitle work? =

The plugin uses a WP's actions/hooks to add a custom field to posts and pages.  Simple.

= Does WP Subtitle use the Wordpress database? =

Yes, all subtitles are stored as custom fields.  Deactivating this plugin will not remove those firleds.

== Screenshots ==

1. Post edit
2. Page edit
3. List of posts showing subtitles
4. A single page showing a subtitle
