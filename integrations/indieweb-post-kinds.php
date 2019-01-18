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
		add_filter('kind_icon_display','zenpress_kind_icon_display',10,2);
	}
}

if(!function_exists('zenpress_kind_icon_display')) {
	function zenpress_kind_icon_display($bool) {
		return false;
	}
}
