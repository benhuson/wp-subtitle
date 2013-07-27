<?
/*
Plugin Name: WP Subtitle
Plugin URI: http://www.husani.com/ventures/wordpress-plugins/wp-subtitle/
Description: Add a subtitle to pages and posts.  Place &lt;?=the_subtitle()?&gt; where you'd like the subtitle to appear.  Similar to the_title, you can pass before, after, and display arguments.  Documentation included in this plugin's readme file.  Get help at <a href="http://forums.husani.com/forum/wp-subtitle/" target="_blank">support forums</a> and sign up for the <a href="http://www.husani.com/ventures/wordpress-plugins/wp-subtitle/mailing-list/" target="_blank">mailing list</a> to receive updates and news about WP Subtitle.  By <a href="http://www.husani.com" target="_blank">Husani Oakley</a>.
Author
Version: 1.0
*/

/*  Copyright 2009  Husani Oakley  (email : wordpressplugins@husani.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * query db and echo page/post subtitle, if any
 */
function wps_get_the_subtitle(){
  global $post;
  echo get_post_meta($post->ID, "wps_subtitle", true);
}

/** HOOKS */
add_action('admin_menu', 'wps_addPanelXHTML');
add_action('save_post', 'wps_saveSubtitle');

/** FUNCTIONS FOR HOOKS */

/**
 * display xhtml for subtitle panel
 */
function wps_addPanelXHTML(){
  if( function_exists( 'add_meta_box' )) {
    add_meta_box('wps_panel', 'Page Subtitle', 'wps_showSubtitlePanel', 'page', 'normal', 'high');
    add_meta_box('wps_panel', 'Post Subtitle', 'wps_showSubtitlePanel', 'post', 'normal', 'high');
  } else {
    add_action('dbx_page_advanced', 'wps_showSubtitlePanelOld');
    add_action('dbx_post_advanced', 'wps_showSubtitlePanelOld');
  }
  //include css if admin
  if(is_admin()){
    add_action('admin_print_styles', 'wps_addPanelCSS');
  }
}

/**
 * include CSS for subtitle panel
 */
function wps_addPanelCSS(){
  $css = WP_PLUGIN_URL . '/wp-subtitle/admin/css/panel.css';
  wp_register_style('wps_css', $css);
  wp_enqueue_style( 'wps_css');
}

/**
 * include XHTML for form inside panel
 */
function wps_showSubtitlePanel(){
  include ABSPATH . PLUGINDIR . "/wp-subtitle/admin/panel.inc.php";
}

/**
 * for pre-2.5, include shell for panel
 */
function wps_showSubtitlePanelOld(){
  include ABSPATH . PLUGINDIR . "/wp-subtitle/admin/compat_panel.inc.php";
}

/**
 * store subtitle content in db as custom field
 */
function wps_saveSubtitle($post_id){
  //verify
  if (!wp_verify_nonce( $_POST['wps_noncename'], 'wp-subtitle')) {
    return $post_id;
  }
  if ('page' == $_POST['post_type']){
    if (!current_user_can('edit_page', $post_id)){
      return $post_id;
    }
  } else {
    if (!current_user_can('edit_post', $post_id)){
      return $post_id;
    }
  }
  //save data
  if(!update_post_meta($post_id, "wps_subtitle", $_POST["wps_subtitle"])){
    add_post_meta($post_id, "wps_subtitle", $_POST["wps_subtitle"]);
  }
}

/**
 * return subtitle from post inside The Loop
 */
function the_subtitle($before="", $after="", $display=true){
  global $post;
  $subtitle = $before . get_post_meta($post->ID, "wps_subtitle", true) . $after;
  if($display){
    echo $subtitle;
  } else {
    return $subtitle;
  }
}

/**
 * return (or display) subtitle from post with ID passed as argument
 */
function get_the_subtitle($id, $before="", $after="", $display=true){
  $subtitle = $before . get_post_meta($id, "wps_subtitle", true) . $after;
  if($display){
    echo $subtitle;
  } else {
    return $subtitle;
  }
}


?>