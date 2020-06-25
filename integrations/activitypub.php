<?php
/**
 * Autonomie ActivityPub
 *
 * Adds support for ActivityPub
 *
 * @link https://github.com/pfefferle/wordpress-activitypub
 *
 * @package Autonomie
 * @subpackage indieweb
 */

/**
 * Add ActivityPub informations to the archive author meta data
 *
 * @param  array $meta      the meta array
 * @param  int   $author_id the author id
 *
 * @return array            the filtered meta array
 */
function autonomie_activitypub_archive_author_meta( $meta, $author_id ) {
	// translators: how to follow
	$meta[] = sprintf( __( '<indie-action do="follow" width="%1$s">Follow <code>%2$s</code> (fediverse)</indie-action>', 'autonomie' ), get_author_posts_url( $author_id ), \Activitypub\get_webfinger_resource( $author_id ) );

	return $meta;
}
add_filter( 'autonomie_archive_author_meta', 'autonomie_activitypub_archive_author_meta', 10, 2 );

/**
 * ActivityPub follower counter
 *
 * @param  int $followers the follower counter
 * @param  int $author_id the author id
 *
 * @return array          the filtered counter
 */
function autonomie_activitypub_followers( $followers, $author_id ) {
	$activitypub_followers = get_user_option( 'activitypub_followers', $author_id );

	if ( $activitypub_followers ) {
		$activitypub_followers = count( $activitypub_followers );
	} else {
		$activitypub_followers = 0;
	}

	$followers = $followers + $activitypub_followers;

	return $followers;
}
add_filter( 'autonomie_archive_author_followers', 'autonomie_activitypub_followers', 10, 2 );
