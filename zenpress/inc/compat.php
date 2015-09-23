<?php
/**
 * ZenPress back compat handling
 *
 * Some functions to add backwards compatibility to older WordPress versions
 * or to add some new functions to be more (for example)  compatible
 *
 * @package ZenPress
 * @subpackage compat
 * @since ZenPress 1.5.0
 */

/**
 * adds compat handling for WP versions pre-*.
 *
 * @category pre-all
 */

/**
 * adds the new  input types to the comment-form
 *
 * @param string $form
 * @return string
 */
function zenpress_comment_autocomplete($fields) {
	$fields['author'] = preg_replace( '/<input/', '<input autocomplete="nickname name" ', $fields['author'] );
	$fields['email'] = preg_replace( '/<input/', '<input autocomplete="email" ', $fields['email'] );
	$fields['url'] = preg_replace( '/<input/', '<input autocomplete="url" ', $fields['url'] );

	return $fields;
}
add_filter( 'comment_form_default_fields', 'zenpress_comment_autocomplete' );
