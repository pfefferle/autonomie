<?php
/**
 * ZenPress Syndication Links
 *
 * Adds support for Syndication Links
 *
 * @link https://github.com/dshanske/syndication-links
 *
 * @package ZenPress
 * @subpackage indieweb
 */

/**
 * Remove the integration into `the_content`
 */
function zenpress_syndication_links_init() {
	remove_filter( 'the_content', array( 'Syn_Config', 'the_content' ) , 30 );
}
add_action( 'init', 'zenpress_syndication_links_init' );

/**
 * Added links to the post-footer
 */
function zenpress_syndication_links() {
	if ( function_exists( 'get_syndication_links' ) ) {
		echo __( 'Syndication Links', 'zenpress' );
		echo get_syndication_links( null, array( 'show_text_before' => null) );
	}
}
add_action( 'zenpress-entry-footer', 'zenpress_syndication_links' );
