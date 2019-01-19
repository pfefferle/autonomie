<?php
/**
 * ZenPress ActivityPub
 *
 * Adds support for ActivityPub
 *
 * @link https://github.com/pfefferle/wordpress-activitypub
 *
 * @package ZenPress
 * @subpackage indieweb
 */

function zenpress_activitypub_author_meta( $meta, $author_id ) {
	$followers = get_user_option( 'activitypub_followers', $author_id );

	if ( $followers ) {
		$followers = count( $followers );
	} else {
		$followers = 0;
	}

	array_unshift( $meta, sprintf( __( '%s Followers' ), $followers ) );

	$meta[] = sprintf( __( 'Follow <code>%s</code> (fediverse)' ), activitypub_get_webfinger_resource( $author_id ) );

	return $meta;
}
add_filter( 'zenpress_author_meta', 'zenpress_activitypub_author_meta', 10, 2 );
