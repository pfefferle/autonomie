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
function zenpress_comment_autocomplete( $fields ) {
	$fields['author'] = preg_replace( '/<input/', '<input autocomplete="nickname name" ', $fields['author'] );
	$fields['email'] = preg_replace( '/<input/', '<input autocomplete="email" ', $fields['email'] );
	$fields['url'] = preg_replace( '/<input/', '<input autocomplete="url" ', $fields['url'] );

	return $fields;
}
add_filter( 'comment_form_default_fields', 'zenpress_comment_autocomplete' );

function zenpress_query_format_standard( $query ) {
	if (
		isset( $query->query_vars['post_format'] ) &&
		'post-format-standard' == $query->query_vars['post_format']
	) {
		$post_formats = get_theme_support( 'post-formats' );

		if (
			$post_formats &&
			is_array( $post_formats[0] ) && count( $post_formats[0] )
		) {
			$terms = array();
			foreach ( $post_formats[0] as $format ) {
				$terms[] = 'post-format-' . $format;
			}
			$query->is_tax = null;

			unset( $query->query_vars['post_format'] );
			unset( $query->query_vars['taxonomy'] );
			unset( $query->query_vars['term'] );
			unset( $query->query['post_format'] );

			$query->set( 'tax_query', array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'post_format',
					'terms' => $terms,
					'field' => 'slug',
					'operator' => 'NOT IN',
				),
			));
		}
	}
}
add_action( 'pre_get_posts', 'zenpress_query_format_standard' );
