<?php
/**
 * ZenPress wordpress-micropub
 *
 * Adds support for https://github.com/snarfed/wordpress-micropub
 *
 * @package ZenPress
 * @subpackage indieweb
 */

add_action('after_setup_theme', 'micropub_post_removal');

function micropub_post_removal() {
 if(has_filter('micropub_post_content', array( Micropub_Plugin, 'generate_post_content' ))) {
   remove_filter('micropub_post_content', array( Micropub_Plugin, 'generate_post_content' ));
 }
}
