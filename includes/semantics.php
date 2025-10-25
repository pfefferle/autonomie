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
	$form = preg_replace( '/<form/i', '<search><form itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction"', $form );
	$form = preg_replace( '/<\/form>/i', '<meta itemprop="target" content="' . home_url( '/?s={s} ' ) . '"/></form></search>', $form );
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

/**
 * Add microformats2 and schema.org classes to blocks for FSE theme.
 *
 * @since Autonomie 2.0.0
 */

/**
 * Add h-entry and hentry classes to post template blocks and convert list to articles.
 */
function autonomie_render_block_post_template( $block_content, $block ) {
	if ( ! is_singular() ) {
		// Convert <ul> to <div> for semantic article container
		$block_content = preg_replace(
			'/<ul\s+class="([^"]*wp-block-post-template[^"]*)"/i',
			'<div class="$1"',
			$block_content,
			1
		);
		$block_content = preg_replace(
			'/<\/ul>/i',
			'</div>',
			$block_content,
			1
		);

		// Convert <li> to <article> for each post
		// Note: h-entry and hentry classes are already added by WordPress post_class() function
		$block_content = preg_replace(
			'/<li\s+class="([^"]*wp-block-post[^"]*)"/i',
			'<article class="$1" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting"',
			$block_content
		);
		$block_content = preg_replace(
			'/<\/li>/i',
			'</article>',
			$block_content
		);
	}
	return $block_content;
}
//add_filter( 'render_block_core/post-template', 'autonomie_render_block_post_template', 10, 2 );

/**
 * Add microformats2 classes to post title block.
 */
function autonomie_render_block_post_title( $block_content, $block ) {
	// Add p-name class to post title
	$block_content = preg_replace(
		'/class="([^"]*wp-block-post-title[^"]*)"/i',
		'class="$1 p-name entry-title" itemprop="name headline"',
		$block_content,
		1
	);

	// Add u-url to the link
	$block_content = preg_replace(
		'/<a([^>]*)href=/i',
		'<a$1 class="u-url url" itemprop="url" href=',
		$block_content,
		1
	);

	return $block_content;
}
add_filter( 'render_block_core/post-title', 'autonomie_render_block_post_title', 10, 2 );

/**
 * Add microformats2 classes to post content block.
 */
function autonomie_render_block_post_content( $block_content, $block ) {
	// Add e-content class
	$block_content = preg_replace(
		'/class="([^"]*wp-block-post-content[^"]*)"/i',
		'class="$1 e-content entry-content" itemprop="articleBody"',
		$block_content,
		1
	);

	return $block_content;
}
add_filter( 'render_block_core/post-content', 'autonomie_render_block_post_content', 10, 2 );

/**
 * Add microformats2 classes to post excerpt block.
 */
function autonomie_render_block_post_excerpt( $block_content, $block ) {
	// Add p-summary class
	$block_content = preg_replace(
		'/class="([^"]*wp-block-post-excerpt[^"]*)"/i',
		'class="$1 p-summary entry-summary" itemprop="description"',
		$block_content,
		1
	);

	return $block_content;
}
add_filter( 'render_block_core/post-excerpt', 'autonomie_render_block_post_excerpt', 10, 2 );

/**
 * Add microformats2 classes to post date block.
 */
function autonomie_render_block_post_date( $block_content, $block ) {
	// Add dt-published class
	$block_content = preg_replace(
		'/<time([^>]*)class="([^"]*)"([^>]*)>/i',
		'<time$1class="$2 dt-published published entry-date" itemprop="datePublished"$3>',
		$block_content,
		1
	);

	return $block_content;
}
add_filter( 'render_block_core/post-date', 'autonomie_render_block_post_date', 10, 2 );

/**
 * Add microformats2 classes to post author block.
 */
function autonomie_render_block_post_author( $block_content, $block ) {
	// Add h-card and p-author classes
	$block_content = preg_replace(
		'/class="([^"]*wp-block-post-author[^"]*)"/i',
		'class="$1 h-card p-author author vcard" itemprop="author" itemscope itemtype="https://schema.org/Person"',
		$block_content,
		1
	);

	// Add p-name to author name
	$block_content = preg_replace(
		'/class="([^"]*wp-block-post-author__name[^"]*)"/i',
		'class="$1 p-name fn" itemprop="name"',
		$block_content,
		1
	);

	// Add u-url to author link
	$block_content = preg_replace(
		'/<a([^>]*class="[^"]*wp-block-post-author__name[^"]*")([^>]*)>/i',
		'<a$1$2 class="u-url url" itemprop="url">',
		$block_content,
		1
	);

	return $block_content;
}
add_filter( 'render_block_core/post-author', 'autonomie_render_block_post_author', 10, 2 );

/**
 * Add microformats2 classes to post featured image block.
 */
function autonomie_render_block_post_featured_image( $block_content, $block ) {
	// Check post format to determine which microformat class to use
	$post_format = get_post_format();

	if ( 'image' === $post_format || 'gallery' === $post_format ) {
		$mf_class = 'u-photo';
	} else {
		$mf_class = 'u-featured';
	}

	// Add microformat class to figure
	$block_content = preg_replace(
		'/class="([^"]*wp-block-post-featured-image[^"]*)"/i',
		'class="$1 ' . $mf_class . '" itemprop="image" itemscope itemtype="https://schema.org/ImageObject"',
		$block_content,
		1
	);

	// Add itemprop to img
	$block_content = preg_replace(
		'/<img([^>]*)>/i',
		'<img$1 itemprop="url contentUrl">',
		$block_content,
		1
	);

	return $block_content;
}
add_filter( 'render_block_core/post-featured-image', 'autonomie_render_block_post_featured_image', 10, 2 );

/**
 * Add microformats2 classes to post terms (categories/tags) block.
 */
function autonomie_render_block_post_terms( $block_content, $block ) {
	// Add p-category class to each term link
	$block_content = preg_replace(
		'/<a([^>]*rel="tag"[^>]*)>/i',
		'<a$1 class="p-category">',
		$block_content
	);

	return $block_content;
}
add_filter( 'render_block_core/post-terms', 'autonomie_render_block_post_terms', 10, 2 );

/**
 * Add microformats2 classes to site title block.
 */
function autonomie_render_block_site_title( $block_content, $block ) {
	if ( is_home() ) {
		// Add p-name and itemprop to site title on home page
		$block_content = preg_replace(
			'/<([h1-6|p|div]+)([^>]*class="[^"]*wp-block-site-title[^"]*")([^>]*)>/i',
			'<$1$2$3 class="p-name" itemprop="name">',
			$block_content,
			1
		);

		// Add u-url to link
		$block_content = preg_replace(
			'/<a([^>]*)class="([^"]*)"/i',
			'<a$1class="$2 u-url url" itemprop="url"',
			$block_content,
			1
		);
	}

	return $block_content;
}
add_filter( 'render_block_core/site-title', 'autonomie_render_block_site_title', 10, 2 );

/**
 * Add microformats2 h-feed class to query block on archive pages.
 */
function autonomie_render_block_query( $block_content, $block ) {
	if ( ! is_singular() ) {
		// Add h-feed class to query block
		$block_content = preg_replace(
			'/class="([^"]*wp-block-query[^"]*)"/i',
			'class="$1 h-feed hfeed"',
			$block_content,
			1
		);
	}

	return $block_content;
}
add_filter( 'render_block_core/query', 'autonomie_render_block_query', 10, 2 );

/**
 * Add microformats2 classes to comment blocks.
 */
function autonomie_render_block_comment_template( $block_content, $block ) {
	// Add h-entry, h-cite classes to comment
	$block_content = preg_replace(
		'/<li([^>]*class="[^"]*comment[^"]*)"/i',
		'<li$1 h-entry h-cite p-comment"',
		$block_content,
		1
	);

	return $block_content;
}
add_filter( 'render_block_core/comment-template', 'autonomie_render_block_comment_template', 10, 2 );

/**
 * Add semantic HTML5 search element to search block.
 */
function autonomie_render_block_search( $block_content, $block ) {
	// Wrap in search element and add schema.org SearchAction
	$block_content = preg_replace(
		'/<form/i',
		'<search><form itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction"',
		$block_content,
		1
	);

	$block_content = preg_replace(
		'/<\/form>/i',
		'<meta itemprop="target" content="' . home_url( '/?s={s}' ) . '"/></form></search>',
		$block_content,
		1
	);

	// Add itemprop to search input
	$block_content = preg_replace(
		'/<input([^>]*)type="search"/i',
		'<input$1type="search" enterkeyhint="search" itemprop="query-input"',
		$block_content,
		1
	);

	return $block_content;
}
add_filter( 'render_block_core/search', 'autonomie_render_block_search', 10, 2 );
