<?php
/**
 * Autonomie Syndication Links
 *
 * Adds support for Syndication Links
 *
 * @link https://github.com/dshanske/syndication-links
 *
 * @package Autonomie
 * @subpackage indieweb
 */

/**
 * Remove the integration of `the_content` filter
 */
function autonomie_syndication_links_init() {
	remove_filter( 'the_content', array( 'Syn_Config', 'the_content' ) , 30 );
}
add_action( 'init', 'autonomie_syndication_links_init' );

/**
 * Remove the Syndication-Links CSS
 */
function autonomie_syndication_links_print_scripts() {
	wp_dequeue_style( 'syndication-style' );
}
add_action( 'wp_print_styles', 'autonomie_syndication_links_print_scripts', 100 );

/**
 * Added links to the post-footer
 */
function autonomie_syndication_links() {
	if ( function_exists( 'get_syndication_links' ) ) {
		_e( 'Syndication Links', 'autonomie' );
		echo get_syndication_links( null, array( 'show_text_before' => null) );
	}
}
add_action( 'autonomie_entry_footer', 'autonomie_syndication_links' );
