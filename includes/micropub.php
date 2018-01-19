<?php
/**
 * ZenPress wordpress-micropub
 *
 * Adds support for https://github.com/snarfed/wordpress-micropub
 *
 * @package ZenPress
 * @subpackage indieweb
 */


if(has_filter('micropub_post_content',array( Micropub_Plugin, 'generate_post_content' )) {
	remove_filter('micropub_post_content', array( Micropub_Plugin, 'generate_post_content' ));
}
