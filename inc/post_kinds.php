<?php
/**
 * ZenPress Indiweb Post Kind Support
 *
 *
 * @link http://microformats.org/wiki/microformats
 * @link http://microformats.org/wiki/microformats2
 * @link http://schema.org
 * @link http://indiewebcamp.com
 *
 * @package ZenPress
 * @subpackage indieweb
 */


add_action( 'init', 'zenpress_post_kinds_init' );

function zenpress_post_kinds_init() {
	if(method_exists('Kind_Taxonomy','get_icon')) {
		add_filter('kind_icon_sprite','zenpress_kind_icon_sprite')
	}
}

if(!function_exists('zenpress_kind_icon_sprite')) {
	function zenpress_kind_icon_sprite($url, $kind) {
		return ''
	}
}
