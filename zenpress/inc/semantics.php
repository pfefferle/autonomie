<?php
/**
 * ZenPress websemantics polyfill
 *
 * Some functions to add backwards compatibility to older WordPress versions
 * Adds some awesome websemantics like microformats(2) and microdata
 *
 * @link http://microformats.org/wiki/microformats
 * @link http://microformats.org/wiki/microformats2
 * @link http://schema.org
 * @link http://indiewebcamp.com
 *
 * @package ZenPress
 * @subpackage semantics
 * @since ZenPress 1.5.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @since ZenPress 1.0.0
 */
function zenpress_body_classes( $classes ) {
	$classes[] = get_theme_mod( 'zenpress_columns', 'multi' ) . '-column';

	// Adds a class of single-author to blogs with only 1 published author
	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( get_header_image() ) {
		$classes[] = 'custom-header';
	}

	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
		$classes[] = 'h-feed';
		$classes[] = 'feed';
	} else {
		$classes = zenpress_get_post_classes( $classes );
	}

	return $classes;
}
add_filter( 'body_class', 'zenpress_body_classes' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @since ZenPress 1.0.0
 */
function zenpress_post_classes( $classes ) {
	$classes = array_diff( $classes, array( 'hentry' ) );

	if ( ! is_singular() ) {
		return zenpress_get_post_classes( $classes );
	} else {
		return $classes;
	}
}
add_filter( 'post_class', 'zenpress_post_classes', 99 );

/**
 * Adds custom classes to the array of comment classes.
 *
 * @since ZenPress 1.4.0
 */
function zenpress_comment_classes( $classes ) {
	$classes[] = 'h-as-comment';
	$classes[] = 'p-comment';
	$classes[] = 'h-entry';
	$classes[] = 'comment';

	return array_unique( $classes );
}
add_filter( 'comment_class', 'zenpress_comment_classes', 99 );

/**
 * encapsulates post-classes to use them on different tags
 */
function zenpress_get_post_classes( $classes = array() ) {
	// Adds a class for microformats v2
	$classes[] = 'h-entry';

	// add hentry to the same tag as h-entry
	$classes[] = 'hentry';

	// adds microformats 2 activity-stream support
	// for pages and articles
	if ( get_post_type() == 'page' ) {
		$classes[] = 'h-as-page';
	}
	if ( ! get_post_format() && get_post_type() == 'post' ) {
		$classes[] = 'h-as-article';
	}

	// adds some more microformats 2 classes based on the
	// posts "format"
	switch ( get_post_format() ) {
		case 'aside':
		case 'status':
			$classes[] = 'h-as-note';
			break;
		case 'audio':
			$classes[] = 'h-as-audio';
			break;
		case 'video':
			$classes[] = 'h-as-video';
			break;
		case 'gallery':
		case 'image':
			$classes[] = 'h-as-image';
			break;
		case 'link':
			$classes[] = 'h-as-bookmark';
			break;
	}

	return array_unique( $classes );
}

/**
 * Adds microformats v2 support to the comment_author_link.
 *
 * @since ZenPress 1.0.0
 */
function zenpress_author_link( $link ) {
	// Adds a class for microformats v2
	return preg_replace( '/(class\s*=\s*[\"|\'])/i', '${1}u-url ', $link );
}
add_filter( 'get_comment_author_link', 'zenpress_author_link' );

/**
 * Adds microformats v2 support to the get_avatar() method.
 *
 * @since ZenPress 1.0.0
 */
function zenpress_get_avatar( $tag ) {
	// Adds a class for microformats v2
	return preg_replace( '/(class\s*=\s*[\"|\'])/i', '${1}u-photo ', $tag );
}
add_filter( 'get_avatar', 'zenpress_get_avatar' );

/**
 * add rel-prev attribute to previous_image_link
 *
 * @param string a-tag
 * @return string
 */
function zenpress_semantic_previous_image_link( $link ) {
	return preg_replace( '/<a/i', '<a rel="prev"', $link );
}
add_filter( 'previous_image_link', 'zenpress_semantic_previous_image_link' );

/**
 * add rel-next attribute to next_image_link
 *
 * @param string a-tag
 * @return string
 */
function zenpress_semantic_next_image_link($link) {
	return preg_replace( '/<a/i', '<a rel="next"', $link );
}
add_filter( 'next_image_link', 'zenpress_semantic_next_image_link' );

/**
 * add rel-prev attribute to next_posts_link_attributes
 *
 * @param string attributes
 * @return string
 */
function zenpress_next_posts_link_attributes( $attr ) {
	return $attr.' rel="prev"';
}
add_filter( 'next_posts_link_attributes', 'zenpress_next_posts_link_attributes' );

/**
 * add rel-next attribute to previous_posts_link
 *
 * @param string attributes
 * @return string
 */
function zenpress_previous_posts_link_attributes( $attr ) {
	return $attr.' rel="next"';
}
add_filter( 'previous_posts_link_attributes', 'zenpress_previous_posts_link_attributes' );

/**
 * add semantics
 *
 * @param string $id the class identifier
 * @return array
 */
function zenpress_get_semantics( $id = null ) {
	$classes = array();

	// add default values
	switch ( $id ) {
		case 'body':
			if ( ! is_singular() ) {
				$classes['itemscope'] = array( '' );
				$classes['itemtype'] = array( 'http://schema.org/Blog' );
			} elseif ( is_single() ) {
				$classes['itemscope'] = array( '' );
				$classes['itemtype'] = array( 'http://schema.org/BlogPosting' );
			} elseif ( is_page() ) {
				$classes['itemscope'] = array( '' );
				$classes['itemtype'] = array( 'http://schema.org/WebPage' );
			}
			break;
		case 'site-title':
			if ( ! is_singular() ) {
				$classes['itemprop'] = array( 'name' );
				$classes['class'] = array( 'p-name' );
			}
			break;
		case 'site-description':
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
				$classes['itemtype'] = array( 'http://schema.org/BlogPosting' );
			}
			break;
	}

	$classes = apply_filters( 'zenpress_semantics', $classes, $id );
	$classes = apply_filters( "zenpress_semantics_{$id}", $classes, $id );

	return $classes;
}

/**
 * echos the semantic classes added via
 * the "zenpress_semantics" filters
 *
 * @param string $id the class identifier
 */
function zenpress_semantics( $id ) {
	$classes = zenpress_get_semantics( $id );

	if ( ! $classes ) {
		return;
	}

	foreach ( $classes as $key => $value ) {
		echo ' ' . esc_attr( $key ) . '="' . esc_attr( join( ' ', $value ) ) . '"';
	}
}
