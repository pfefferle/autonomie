<?php
/**
 * Autonomie Websemantics polyfill
 *
 * Some functions to add backwards compatibility to older WordPress versions
 * Adds some awesome websemantics like microformats(2) and microdata
 *
 * @link https://microformats.org/wiki/microformats
 * @link https://microformats.org/wiki/microformats2
 * @link https://schema.org
 * @link https://indieweb.org
 *
 * @package Autonomie
 * @subpackage semantics
 * @since Autonomie 1.5.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Autonomie 1.0.0
 */
function autonomie_body_classes( $classes ) {
	$classes[] = get_theme_mod( 'autonomie_columns', 'multi' ) . '-column';

	if ( ! is_singular() && ! is_404() ) {
		$classes[] = 'hfeed';
		$classes[] = 'h-feed';
		$classes[] = 'feed';
	}

	// Adds a class of single-author to blogs with only 1 published author
	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( get_header_image() ) {
		$classes[] = 'custom-header';
	}

	return $classes;
}
add_filter( 'body_class', 'autonomie_body_classes' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @since Autonomie 1.0.0
 */
function autonomie_post_classes( $classes ) {
	$classes = array_diff( $classes, array( 'hentry' ) );

	if ( ! is_singular() ) {
		return autonomie_get_post_classes( $classes );
	} else {
		return $classes;
	}
}
add_filter( 'post_class', 'autonomie_post_classes', 99 );

/**
 * Adds custom classes to the array of comment classes.
 *
 * @since Autonomie 1.4.0
 */
function autonomie_comment_classes( $classes ) {
	$classes[] = 'h-entry';
	$classes[] = 'h-cite';
	$classes[] = 'p-comment';
	$classes[] = 'comment';

	return array_unique( $classes );
}
add_filter( 'comment_class', 'autonomie_comment_classes', 99 );

/**
 * Encapsulates post-classes to use them on different tags.
 */
function autonomie_get_post_classes( $classes = array() ) {
	// Adds a class for microformats v2
	$classes[] = 'h-entry';

	// add hentry to the same tag as h-entry
	$classes[] = 'hentry';

	return array_unique( $classes );
}

/**
 * Adds microformats v2 support to the comment_author_link.
 *
 * @since Autonomie 1.0.0
 */
function autonomie_author_link( $link ) {
	// Adds a class for microformats v2
	return preg_replace( '/(class\s*=\s*[\"|\'])/i', '${1}u-url ', $link );
}
add_filter( 'get_comment_author_link', 'autonomie_author_link' );

/**
 * Adds microformats v2 support to the get_avatar() method.
 *
 * @since Autonomie 1.0.0
 */
function autonomie_pre_get_avatar_data( $args, $id_or_email ) {
	if ( ! isset( $args['class'] ) ) {
		$args['class'] = array();
	}

	if ( ! is_array( $args['class'] ) ) {
		$args['class'] = array( $args['class'] );
	}

	// Adds a class for microformats v2
	$args['class'] = array_unique( array_merge( $args['class'], array( 'u-photo' ) ) );
	$args['extra_attr'] .= ' itemprop="image" loading="lazy"';

	// Adds default alt attribute
	if ( empty( $args['alt'] ) ) {
		$username = get_the_author_meta( 'display_name', $id_or_email );

		if ( $username ) {
			$args['alt'] = sprintf( __( 'User Avatar of %s' ), $username );
		} else {
			$args['alt'] = __( 'User Avatar' );
		}
	}

	return $args;
}
add_filter( 'pre_get_avatar_data', 'autonomie_pre_get_avatar_data', 99, 2 );

/**
 * Add rel-prev attribute to previous_image_link.
 *
 * @param string a-tag
 *
 * @return string
 */
function autonomie_semantic_previous_image_link( $link ) {
	return preg_replace( '/<a/i', '<a rel="prev"', $link );
}
add_filter( 'previous_image_link', 'autonomie_semantic_previous_image_link' );

/**
 * Add rel-next attribute to next_image_link.
 *
 * @param string a-tag
 *
 * @return string
 */
function autonomie_semantic_next_image_link( $link ) {
	return preg_replace( '/<a/i', '<a rel="next"', $link );
}
add_filter( 'next_image_link', 'autonomie_semantic_next_image_link' );

/**
 * Add rel-prev attribute to next_posts_link_attributes.
 *
 * @param string Attributes
 *
 * @return string
 */
function autonomie_next_posts_link_attributes( $attr ) {
	return $attr . ' rel="prev"';
}
add_filter( 'next_posts_link_attributes', 'autonomie_next_posts_link_attributes' );

/**
 * Add rel-next attribute to previous_posts_link.
 *
 * @param string Attributes
 *
 * @return string
 */
function autonomie_previous_posts_link_attributes( $attr ) {
	return $attr . ' rel="next"';
}
add_filter( 'previous_posts_link_attributes', 'autonomie_previous_posts_link_attributes' );

/**
 *
 *
 */
function autonomie_get_search_form( $form ) {
	$form = preg_replace( '/<form/i', '<form itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction"', $form );
	$form = preg_replace( '/<\/form>/i', '<meta itemprop="target" content="' . home_url( '/?s={s} ' ) . '"/></form>', $form );
	$form = preg_replace( '/<input type="search"/i', '<input type="search" enterkeyhint="search" itemprop="query-input"', $form );

	return $form;
}
add_filter( 'get_search_form', 'autonomie_get_search_form' );

/**
 * Add semantics.
 *
 * @param string $id The class identifier.
 *
 * @return array
 */
function autonomie_get_semantics( $id = null ) {
	$classes = array();

	// add default values
	switch ( $id ) {
		case 'body':
			if ( is_search() ) {
				$classes['itemscope'] = array( '' );
				$classes['itemtype'] = array( 'https://schema.org/Blog', 'https://schema.org/SearchResultsPage' );
			} elseif ( is_author() ) {
				$classes['itemscope'] = array( '' );
				$classes['itemtype'] = array( 'https://schema.org/Blog', 'https://schema.org/ProfilePage' );
			} elseif ( is_single() ) {
				$classes['itemscope'] = array( '' );
				$classes['itemtype'] = array( 'https://schema.org/BlogPosting' );
				$classes['itemref'] = array( 'site-publisher' );
			} elseif ( is_page() ) {
				$classes['itemscope'] = array( '' );
				$classes['itemtype'] = array( 'https://schema.org/WebPage' );
			} elseif ( ! is_singular() ) {
				$classes['itemscope'] = array( '' );
				$classes['itemtype'] = array( 'https://schema.org/Blog', 'https://schema.org/WebPage' );
			}

			$classes['itemid'] = array( get_self_link() );

			break;
		case 'main':
			break;
		case 'site-title':
			if ( is_home() ) {
				$classes['itemprop'] = array( 'name' );
				$classes['class'] = array( 'p-name' );
			}
			break;
		case 'page-title':
			if ( ! is_singular() && ! is_home() ) {
				$classes['itemprop'] = array( 'name' );
				$classes['class'] = array( 'p-name' );
			}
			break;
		case 'page-description':
			if ( ! is_singular() ) {
				$classes['itemprop'] = array( 'description' );
				$classes['class'] = array( 'p-summary', 'e-content' );
			}
			break;
		case 'site-url':
			if ( ! is_singular() ) {
				$classes['itemprop'] = array( 'url' );
				$classes['class'] = array( 'u-url', 'url' );
			}
			break;
		case 'post':
			if ( ! is_singular() ) {
				$classes['itemprop'] = array( 'blogPost' );
				$classes['itemscope'] = array( '' );
				$classes['itemtype'] = array( 'https://schema.org/BlogPosting' );
				$classes['itemref'] = array( 'site-publisher' );
				$classes['itemid'] = array( get_permalink() );
			}
			break;
	}

	$classes = apply_filters( 'autonomie_semantics', $classes, $id );
	$classes = apply_filters( "autonomie_semantics_{$id}", $classes, $id );

	return $classes;
}

/**
 * Echos the semantic classes added via the "autonomie_semantics" filters.
 *
 * @param string $id The class identifier.
 */
function autonomie_get_the_semantics( $id ) {
	$classes = autonomie_get_semantics( $id );

	if ( ! $classes ) {
		return;
	}

	$class = '';

	foreach ( $classes as $key => $value ) {
		$class .= ' ' . esc_attr( $key ) . '="' . esc_attr( join( ' ', $value ) ) . '"';
	}

	return $class;
}

/**
 * Echos the semantic classes added via the "autonomie_semantics" filters.
 *
 * @param string $id The class identifier.
 */
function autonomie_semantics( $id ) {
	$classes = autonomie_get_semantics( $id );

	if ( ! $classes ) {
		return;
	}

	foreach ( $classes as $key => $value ) {
		echo ' ' . esc_attr( $key ) . '="' . esc_attr( join( ' ', $value ) ) . '"';
	}
}

/**
 * Add `p-category` to tags links.
 *
 * @link https://www.webrocker.de/2016/05/13/add-class-attribute-to-wordpress-the_tags-markup/
 *
 * @param  array $links
 *
 * @return array
 */
function autonomie_term_links_tag( $links ) {
	$post = get_post();

	$terms = get_the_terms( $post->ID, 'post_tag' );

	if ( is_wp_error( $terms ) ) {
		return $terms;
	}

	if ( empty( $terms ) ) {
		return false;
	}

	$links = array();

	foreach ( $terms as $term ) {
		$link = get_term_link( $term );
		if ( is_wp_error( $link ) ) {
			return $link;
		}
		$links[] = '<a class="p-category" href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
	}
	return $links;
}
add_filter( 'term_links-post_tag', 'autonomie_term_links_tag' );
