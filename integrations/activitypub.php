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
	$followers = get_user_option( 'activitypub_followers', $author_id );

	if ( $followers ) {
		$followers = count( $followers );
	} else {
		$followers = 0;
	}

	array_unshift( $meta, sprintf( __( '%s Followers', 'zenpress' ), $followers ) );

	$meta[] = sprintf( __( 'Follow <code>%s</code> (fediverse)', 'zenpress' ), activitypub_get_webfinger_resource( $author_id ) );

	return $meta;
}
add_filter( 'zenpress_archive_author_meta', 'zenpress_activitypub_archive_author_meta', 10, 2 );
