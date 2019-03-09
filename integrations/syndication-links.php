<?php
/**
 * Autonom Syndication Links
 *
 * Adds support for Syndication Links
 *
 * @link https://github.com/dshanske/syndication-links
 *
 * @package Autonom
 * @subpackage indieweb
 */

/**
 * Remove the integration of `the_content` filter
 */
function autonom_syndication_links_init() {
	remove_filter( 'the_content', array( 'Syn_Config', 'the_content' ) , 30 );
}
add_action( 'init', 'autonom_syndication_links_init' );

/**
 * Remove the Syndication-Links CSS
 */
function autonom_syndication_links_print_scripts() {
	wp_dequeue_style( 'syndication-style' );
}
add_action( 'wp_print_styles', 'autonom_syndication_links_print_scripts', 100 );

/**
 * Added links to the post-footer
 */
function autonom_syndication_links() {
	if ( function_exists( 'get_syndication_links' ) ) {
		_e( 'Syndication Links', 'autonom' );
		echo get_syndication_links( null, array( 'show_text_before' => null) );
	}
}
add_action( 'autonom-entry-footer', 'autonom_syndication_links' );
