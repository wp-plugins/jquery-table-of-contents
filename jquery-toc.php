<?php
/*
Plugin Name: jQuery Table of Contents
Plugin URI: http://www.tedcarnahan.com/2010/01/10/wordpress-jquery-table-of-contents-plugin/
Description: Adds a table of contents to posts and pages based on HTML header tags using jQuery.
Version: 1.2
Author: Ted Carnahan
Author URI: http://www.tedcarnahan.com/

    Copyright 2010  Ted Carnahan  (email : ted@tedcarnahan.com)

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

DEFINE("JQUERY_TOC_URL", WP_PLUGIN_URL . "/jquery-table-of-contents");

add_action('init', 'jquery_toc_init');
add_action('admin_init', 'jquery_toc_options_init');
add_action('admin_menu', 'jquery_toc_options_menu');

register_activation_hook( __FILE__, 'jquery_toc_activate' );
register_uninstall_hook( __FILE__, 'jquery_toc_uninstall' );

function jquery_toc_activate() {
  $options = get_option('jquery_toc_options');
  foreach (array(
    'source_selector' => '.single .entry',
    'header_tag' => 'h2',
    'output_id' => 'jquery_toc',
    'output_title' => 'On this page:',
  ) as $option_name => $default) {
    if ($options[$option_name] == '') { $options[$option_name] = $default; }
  }
  update_option('jquery_toc_options', $options);
}

function jquery_toc_uninstall() {
  delete_option('jquery_toc_options');
}

function jquery_toc_init() {
  wp_enqueue_script('jquery-toc', JQUERY_TOC_URL . '/jquery-toc.js', array('jquery'));
  wp_localize_script('jquery-toc', 'jQueryTOC', get_option('jquery_toc_options'));
  wp_enqueue_style('jquery-toc', '/wp-content/plugins/jquery-table-of-contents/jquery-toc.css');
}

function jquery_toc_options_init() {
  register_setting('jquery_toc_options', 'jquery_toc_options');

  add_settings_section('jquery_toc_section_theme', 'Theme Settings', 'jquery_toc_section_theme', 'jquery_toc');
  add_settings_field('jquery_toc_field_source_selector', 'jQuery content selector', 'jquery_toc_field_source_selector', 'jquery_toc', 'jquery_toc_section_theme');
  add_settings_field('jquery_toc_field_header_tag', 'Header Tag', 'jquery_toc_field_header_tag', 'jquery_toc', 'jquery_toc_section_theme');

  add_settings_section('jquery_toc_section_output', 'Output Settings', 'jquery_toc_section_output', 'jquery_toc');
  add_settings_field('jquery_toc_field_output_title', 'TOC Title', 'jquery_toc_field_output_title', 'jquery_toc', 'jquery_toc_section_output');
  add_settings_field('jquery_toc_field_output_id', 'Put TOC where?', 'jquery_toc_field_output_id', 'jquery_toc', 'jquery_toc_section_output');
}

function jquery_toc_options_menu() {
  add_options_page('jQuery TOC Options', 'jQuery TOC', 'edit_themes', 'jquery_toc', 'jquery_toc_options_page');
}

function jquery_toc_options_page() {
  ?>
  <div class="wrap">
    <h2>jQuery Table of Contents - Options</h2>
    <form method="post" action="options.php">
      <?php 
        settings_fields('jquery_toc_options');
        do_settings_sections('jquery_toc');
      ?>
      <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>
    </form>
  </div>
  <?php 
}
        
function jquery_toc_section_theme() { 
  ?>
  <p>jQuery TOC needs to know a little bit about your theme.  Usually, the default setting works well, but
     if your theme names things differently, you might need to adjust these options.</p>
  <?php
}

function jquery_toc_field_source_selector() {
  $options = get_option('jquery_toc_options');
  ?>
  <p><input type="text" name="jquery_toc_options[source_selector]" 
            value="<?php echo $options['source_selector']; ?>" /></p>
  <p>Most of the time this will just work, but if your theme defines the primary "content" 
     <code>div</code> to have a id other than "content," you'll need to change that here.  
     This field accepts a <a href="http://docs.jquery.com/Selectors">jQuery selector</a> that should
     uniquely identify where the post's content is.</p>
  <?php
}

function jquery_toc_field_header_tag() {
  $options = get_option('jquery_toc_options');
  ?>
  <p><input type="text" name="jquery_toc_options[header_tag]" 
           value="<?php echo $options['header_tag']; ?>" /></p>
  <p>Most sites will put the highest-level headers in <code>h2</code>.
  <?php
}

function jquery_toc_section_output() { 
  ?>
  <p>Customize the look of your Table of Contents.</p>
  <?php
}

function jquery_toc_field_output_title() {
  $options = get_option('jquery_toc_options');
  ?>
  <p><input type="text" name="jquery_toc_options[output_title]" 
           value="<?php echo $options['output_title']; ?>" /></p>
  <p>The title of the Table of Contents.</p>
  <?php
}

function jquery_toc_field_output_id() {
  $options = get_option('jquery_toc_options');
  ?>
  <p><input type="text" name="jquery_toc_options[output_id]" 
           value="<?php echo $options['output_id']; ?>" /></p>
  <p>The id of the new div for the Table of Contents.  Use the default, <code>jquery_toc</code> to choose the
     default stylesheet, or change this setting and add your style to your theme's style.css file.</p>
  <?php
}

?>