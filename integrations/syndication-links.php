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
	wp_dequeue_style( 'syndication-style' );
}
add_action( 'init', 'zenpress_syndication_links_init' );

function zenpress_syndication_links_print_scripts() {
	wp_dequeue_style( 'syndication-style' );
}
add_action( 'wp_print_styles', 'zenpress_syndication_links_print_scripts', 100 );

/**
 * Added links to the post-footer
 */
function zenpress_syndication_links() {
	if ( function_exists( 'get_syndication_links' ) ) {
		_e( 'Syndication Links', 'zenpress' );
		echo get_syndication_links( null, array( 'show_text_before' => null) );
	}
}
add_action( 'zenpress-entry-footer', 'zenpress_syndication_links' );
