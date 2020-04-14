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
 * adds the new  input types to the comment-form
 *
 * @param string $form
 * @return string
 */
function autonomie_comment_autocomplete( $fields ) {
	$fields['author'] = preg_replace( '/<input/', '<input autocomplete="nickname name" enterkeyhint="next" ', $fields['author'] );
	$fields['email'] = preg_replace( '/<input/', '<input autocomplete="email" enterkeyhint="next" ', $fields['email'] );
	$fields['url'] = preg_replace( '/<input/', '<input autocomplete="url" enterkeyhint="next" ', $fields['url'] );

	return $fields;
}
add_filter( 'comment_form_default_fields', 'autonomie_comment_autocomplete' );

/**
 * Fix archive for "standard" post type
 *
 * @param WP_Query $query
 */
function autonomie_query_format_standard( $query ) {
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

if ( version_compare( get_bloginfo( 'version' ), '5.5', '<' ) ) {
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
}

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
