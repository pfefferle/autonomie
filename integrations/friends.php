<?php
/**
 * Autonomie Friends
 *
 * Adds support for Friends
 *
 * @link https://github.com/akirk/friends
 *
 * @package Autonomie
 */

/**
 * Undocumented function
 *
 * @param [type] $path
 * @return void
 */
function autonomie_friends_template_path( $path ) {
	if ( strpos( $path, 'friends/posts.php' ) !== false ) {
		return get_template_directory() . '/friends.php';
	}

	return $path;
}
add_filter( 'friends_template_path', 'autonomie_friends_template_path', 99, 1 );

/**
 * Undocumented function
 *
 * @return void
 */
function autonomie_dequeue_friends_scripts() {
	wp_dequeue_style( 'friends' );
}
add_action( 'wp_enqueue_scripts', 'autonomie_dequeue_friends_scripts' );
