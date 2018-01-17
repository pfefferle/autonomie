<?php
/**
 * ZenPress syndication links
 *
 * Adds support for https://github.com/dshanske/syndication-links
 *
 * @link http://microformats.org/wiki/microformats
 * @link http://microformats.org/wiki/microformats2
 * @link http://schema.org
 * @link http://indiewebcamp.com
 *
 * @package ZenPress
 * @subpackage indieweb
 */


add_action( 'init', 'zenpress_syndication_links_init' );

function zenpress_syndication_links_init() {
	if(has_filter('the_content',array('Syn_Config','the_content'))) {
		remove_filter( 'the_content', array( 'Syn_Config', 'the_content' ) , 30 );
	}
}

if(!function_exists('zenpress_syndication_links')) {
	function zenpress_syndication_links() {
		if(function_exists('get_syndication_links'))
			echo get_syndication_links();
	}
}
