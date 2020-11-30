<?php
/**
 * Autonomie back compat handling
 *
 * Some functions to add backwards compatibility to older WordPress versions
 * or to add some new functions to be more (for example)  compatible
 *
 * @package Autonomie
 * @subpackage compat
 * @since Autonomie 1.5.0
 */

/**
 * adds compat handling for WP versions pre-*.
 *
 * @category pre-all
 */

/**
 * Adds the new  input types to the comment-form
 *
 * @param string $form
 * @return string
 */
function autonomie_comment_autocomplete( $fields ) {
	$fields['author'] = preg_replace( '/<input/', '<input autocomplete="nickname name" enterkeyhint="next" ', $fields['author'] );
	$fields['email'] = preg_replace( '/<input/', '<input autocomplete="email" inputmode="email" enterkeyhint="next" ', $fields['email'] );
	$fields['url'] = preg_replace( '/<input/', '<input autocomplete="url" inputmode="url" enterkeyhint="send" ', $fields['url'] );

	return $fields;
}
add_filter( 'comment_form_default_fields', 'autonomie_comment_autocomplete' );

/**
 * Adds the new HTML5 input types to the comment-text-area
 *
 * @param string $field
 * @return string
 */
function autonomie_comment_field_input_type( $field ) {
	return preg_replace( '/<textarea/', '<textarea enterkeyhint="next"', $field );
}
add_filter( 'comment_form_field_comment', 'autonomie_comment_field_input_type' );

/**
 * Fix archive for "standard" post type
 *
 * @param WP_Query $query
 */
function autonomie_query_format_standard( $query ) {
	if (
		isset( $query->query_vars['post_format'] ) &&
		'post-format-standard' === $query->query_vars['post_format']
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

			$query->set(
				'tax_query',
				array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'post_format',
						'terms' => $terms,
						'field' => 'slug',
						'operator' => 'NOT IN',
					),
				)
			);
		}
	}
}
add_action( 'pre_get_posts', 'autonomie_query_format_standard' );

/**
 * Add lazy loading attribute
 *
 * @see https://www.webrocker.de/2019/08/20/wordpress-filter-for-lazy-loading-src/
 *
 * @param string $content
 *
 * @return string the filtered content
 */
function autonomie_add_lazy_loading( $content ) {
	$content = preg_replace( '/(<[^>]*?)(\ssrc=)(.*?\/?>)/', '\1 loading="lazy" src=\3', $content );

	return $content;
}
add_filter( 'the_content', 'autonomie_add_lazy_loading', 99 );

add_filter(
	'wp_lazy_loading_enabled',
	function( $default, $tag_name, $context ) {
		if ( 'the_content' === $context ) {
			return false;
		}

		return $default;
	},
	20,
	3
);

if ( ! function_exists( 'get_self_link' ) ) {
	/**
	 * Returns the link for the currently displayed feed.
	 *
	 * @since 5.3.0
	 *
	 * @return string Correct link for the atom:self element.
	 */
	function get_self_link() {
		$host = @parse_url( home_url() );
		return set_url_scheme( 'http://' . $host['host'] . wp_unslash( $_SERVER['REQUEST_URI'] ) );
	}
}
