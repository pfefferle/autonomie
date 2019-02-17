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

/**
 * Add ActivityPub informations to the archive author meta data
 *
 * @param  array $meta      the meta array
 * @param  int   $author_id the author id
 *
 * @return array            the filtered meta array
 */
function zenpress_activitypub_archive_author_meta( $meta, $author_id ) {
	// translators: how to follow
	$meta[] = sprintf( __( 'Follow <code>%s</code> (fediverse)', 'zenpress' ), activitypub_get_webfinger_resource( $author_id ) );

	return $meta;
}
add_filter( 'zenpress_archive_author_meta', 'zenpress_activitypub_archive_author_meta', 10, 2 );

/**
 * ActivityPub follower counter
 *
 * @param  int $followers the follower counter
 * @param  int $author_id the author id
 *
 * @return array          the filtered counter
 */
function zenpress_activitypub_followers( $followers, $author_id ) {
	$activitypub_followers = get_user_option( 'activitypub_followers', $author_id );

	if ( $activitypub_followers ) {
		$activitypub_followers = count( $activitypub_followers );
	} else {
		$activitypub_followers = 0;
	}

	$followers = $followers + $activitypub_followers;

	return $followers;
}
add_filter( 'zenpress_archive_author_followers', 'zenpress_activitypub_followers', 10, 2 );
