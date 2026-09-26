<?php
/**
 * Add Webactions to the reply links in the comment section.
 *
 * @param string     $link    The HTML markup for the comment reply link.
 * @param array      $args    An array of arguments overriding the defaults.
 * @param WP_Comment $comment The object of the comment being replied.
 * @param WP_Post    $post    The WP_Post object.
 *
 * @return string The new reply link.
 */
function autonomie_webaction_comment_reply_link( $link, $args, $comment, $post ) {
	$permalink = get_permalink( $post->ID );
	return '<indie-action do="reply" with="' . esc_url( add_query_arg( 'replytocom', $comment->comment_ID, $permalink ) ) . '">' . $link . '</indie-action>';
}
add_filter( 'comment_reply_link', 'autonomie_webaction_comment_reply_link', null, 4 );

/**
 * Surround comment form with a reply action.
 */
function autonomie_webaction_comment_form_before() {
	$post = get_queried_object();
	$permalink = get_permalink( $post->ID );
	echo '<indie-action do="reply" with="' . $permalink . '">';
}
add_action( 'comment_form_before', 'autonomie_webaction_comment_form_before', 0 );

/**
 * Surround comment form with a reply action.
 */
function autonomie_webaction_comment_form_after() {
	echo '</indie-action>';
}
add_action( 'comment_form_after', 'autonomie_webaction_comment_form_after', 0 );
