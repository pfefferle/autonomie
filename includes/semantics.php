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

	if ( is_singular() ) {
		$classes[] = 'h-entry';
		$classes[] = 'hentry';
	}

	// Adds a class of single-author to blogs with only 1 published author
	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( get_header_image() ) {
		$classes[] = 'custom-header';
	}

	return array_values( array_unique( $classes ) );
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
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		// Adds a class for microformats v2.
		return preg_replace( '/(class\s*=\s*[\"|\'])/i', '${1}u-url ', $link );
	}

	$processor = new WP_HTML_Tag_Processor( $link );

	if ( $processor->next_tag( array( 'tag_name' => 'a' ) ) ) {
		$processor->add_class( 'u-url' );
		return $processor->get_updated_html();
	}

	return $link;
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
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return preg_replace( '/<a/i', '<a rel="prev"', $link );
	}

	$processor = new WP_HTML_Tag_Processor( $link );

	if ( $processor->next_tag( array( 'tag_name' => 'a' ) ) ) {
		autonomie_tag_processor_merge_space_attr( $processor, 'rel', array( 'prev' ) );
		return $processor->get_updated_html();
	}

	return $link;
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
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return preg_replace( '/<a/i', '<a rel="next"', $link );
	}

	$processor = new WP_HTML_Tag_Processor( $link );

	if ( $processor->next_tag( array( 'tag_name' => 'a' ) ) ) {
		autonomie_tag_processor_merge_space_attr( $processor, 'rel', array( 'next' ) );
		return $processor->get_updated_html();
	}

	return $link;
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
function autonomie_add_search_form_semantics( $form ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		$form = preg_replace( '/<form/i', '<search><form itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction"', $form );
		$form = preg_replace( '/<\/form>/i', '<meta itemprop="target" content="' . home_url( '/?s={query}' ) . '"/></form></search>', $form );
		$form = preg_replace( '/<input type="search"/i', '<input type="search" enterkeyhint="search" itemprop="query"', $form );

		return $form;
	}

	$processor  = new WP_HTML_Tag_Processor( $form );
	$form_found = false;

	if ( $processor->next_tag( array( 'tag_name' => 'form' ) ) ) {
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'potentialAction' ) );
		$processor->set_attribute( 'itemscope', '' );
		$processor->set_attribute( 'itemtype', 'https://schema.org/SearchAction' );
		$form_found = true;
	}

	while ( $processor->next_tag( array( 'tag_name' => 'input' ) ) ) {
		$type = $processor->get_attribute( 'type' );

		if ( is_string( $type ) && 'search' === strtolower( $type ) ) {
			$processor->set_attribute( 'enterkeyhint', 'search' );
			autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'query' ) );
			break;
		}
	}

	$form = $processor->get_updated_html();

	if ( ! $form_found ) {
		return $form;
	}

	$search_action_target = esc_url( home_url( '/?s={query}' ) );
	$open_form_pos        = stripos( $form, '<form' );

	if ( false !== $open_form_pos ) {
		$form = substr_replace( $form, '<search>', $open_form_pos, 0 );
	}

	$close_form_pos = stripos( $form, '</form>' );

	if ( false !== $close_form_pos ) {
		$form = substr_replace(
			$form,
			'<meta itemprop="target" content="' . $search_action_target . '"/></form></search>',
			$close_form_pos,
			strlen( '</form>' )
		);
	}

	return $form;
}

/**
 *
 *
 */
function autonomie_get_search_form( $form ) {
	return autonomie_add_search_form_semantics( $form );
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
				$classes['itemref'] = array( 'site-branding-publisher' );
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
			if ( is_single() ) {
				$classes['itemprop'] = array( 'mainEntity' );
			} elseif ( is_page() ) {
				$classes['itemprop'] = array( 'mainContentOfPage' );
			}
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
				$classes['itemref'] = array( 'site-branding-publisher' );
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
 * Add one or more classes to the current tag in a tag processor.
 *
 * @param WP_HTML_Tag_Processor $processor Tag processor instance.
 * @param array                 $classes   Classes to add.
 */
function autonomie_tag_processor_add_classes( $processor, $classes ) {
	$current_classes = $processor->get_attribute( 'class' );
	$merged_classes  = array();

	if ( is_string( $current_classes ) && '' !== trim( $current_classes ) ) {
		$merged_classes = preg_split( '/\s+/', trim( $current_classes ) );
	}

	foreach ( $classes as $class ) {
		$class = trim( $class );

		if ( '' !== $class && ! in_array( $class, $merged_classes, true ) ) {
			$merged_classes[] = $class;
		}
	}

	if ( ! empty( $merged_classes ) ) {
		$processor->set_attribute( 'class', implode( ' ', $merged_classes ) );
	}
}

/**
 * Merge values into a space-separated attribute on the current tag.
 *
 * @param WP_HTML_Tag_Processor $processor  Tag processor instance.
 * @param string                $attribute  Attribute name.
 * @param array                 $new_values Values to merge.
 */
function autonomie_tag_processor_merge_space_attr( $processor, $attribute, $new_values ) {
	$current = $processor->get_attribute( $attribute );
	$values  = array();

	if ( is_string( $current ) && '' !== trim( $current ) ) {
		$values = preg_split( '/\s+/', trim( $current ) );
	}

	foreach ( $new_values as $value ) {
		$value = trim( $value );

		if ( '' !== $value && ! in_array( $value, $values, true ) ) {
			$values[] = $value;
		}
	}

	if ( ! empty( $values ) ) {
		$processor->set_attribute( $attribute, implode( ' ', $values ) );
	}
}

/**
 * Remove one or more classes from the current tag in a tag processor.
 *
 * @param WP_HTML_Tag_Processor $processor Tag processor instance.
 * @param array                 $classes   Classes to remove.
 */
function autonomie_tag_processor_remove_classes( $processor, $classes ) {
	$current_classes = $processor->get_attribute( 'class' );

	if ( ! is_string( $current_classes ) || '' === trim( $current_classes ) ) {
		return;
	}

	$remove_classes = array_filter( array_map( 'trim', $classes ) );
	$merged_classes = preg_split( '/\s+/', trim( $current_classes ) );

	if ( empty( $remove_classes ) || empty( $merged_classes ) ) {
		return;
	}

	$merged_classes = array_values(
		array_filter(
			$merged_classes,
			function( $class ) use ( $remove_classes ) {
				return ! in_array( $class, $remove_classes, true );
			}
		)
	);

	if ( empty( $merged_classes ) ) {
		$processor->remove_attribute( 'class' );
		return;
	}

	$processor->set_attribute( 'class', implode( ' ', $merged_classes ) );
}

/**
 * Add schema.org attributes to post template items on non-singular views.
 */
function autonomie_render_block_post_template( $block_content, $block ) {
	if ( is_singular() || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	while ( $processor->next_tag( array( 'class_name' => 'wp-block-post' ) ) ) {
		autonomie_tag_processor_add_classes( $processor, array( 'h-entry', 'hentry' ) );
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'blogPost' ) );
		$processor->set_attribute( 'itemscope', '' );
		$processor->set_attribute( 'itemtype', 'https://schema.org/BlogPosting' );
		$processor->set_attribute( 'itemref', 'site-branding-publisher' );

		$classes = $processor->get_attribute( 'class' );

		if ( is_string( $classes ) && preg_match( '/\bpost-(\d+)\b/', $classes, $matches ) ) {
			$permalink = get_permalink( (int) $matches[1] );

			if ( is_string( $permalink ) && '' !== $permalink ) {
				$processor->set_attribute( 'itemid', $permalink );
			}
		}
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-template', 'autonomie_render_block_post_template', 10, 2 );

/**
 * Ensure singular article group wrappers do not duplicate h-entry classes.
 */
function autonomie_render_block_group( $block_content, $block ) {
	if ( ! is_singular() || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	if ( empty( $block['attrs']['tagName'] ) || 'article' !== strtolower( $block['attrs']['tagName'] ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( $processor->next_tag() ) {
		autonomie_tag_processor_remove_classes( $processor, array( 'h-entry', 'hentry' ) );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/group', 'autonomie_render_block_group', 10, 2 );

/**
 * Add page-level schema.org semantics to the main container.
 */
function autonomie_render_block_main_group_semantics( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	if ( empty( $block['attrs']['tagName'] ) || 'main' !== strtolower( $block['attrs']['tagName'] ) ) {
		return $block_content;
	}

	$body_semantics = autonomie_get_semantics( 'body' );

	if ( empty( $body_semantics ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( ! $processor->next_tag() ) {
		return $block_content;
	}

	if ( ! empty( $body_semantics['itemscope'] ) ) {
		$processor->set_attribute( 'itemscope', '' );
	}

	if ( ! empty( $body_semantics['itemtype'] ) ) {
		$processor->set_attribute( 'itemtype', implode( ' ', $body_semantics['itemtype'] ) );
	}

	if ( ! empty( $body_semantics['itemid'] ) ) {
		$processor->set_attribute( 'itemid', $body_semantics['itemid'][0] );
	}

	if ( ! empty( $body_semantics['itemref'] ) ) {
		$processor->set_attribute( 'itemref', implode( ' ', $body_semantics['itemref'] ) );
	} else {
		$processor->set_attribute( 'itemref', 'site-branding-publisher' );
	}

	$main_semantics = autonomie_get_semantics( 'main' );

	if ( ! empty( $main_semantics['itemprop'] ) ) {
		$processor->set_attribute( 'itemprop', implode( ' ', $main_semantics['itemprop'] ) );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/group', 'autonomie_render_block_main_group_semantics', 11, 2 );

/**
 * Re-use the existing header branding (logo + site title) as Blog publisher.
 */
function autonomie_render_block_group_site_branding_publisher( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	if ( empty( $block['attrs']['className'] ) || ! is_string( $block['attrs']['className'] ) ) {
		return $block_content;
	}

	$class_name = ' ' . $block['attrs']['className'] . ' ';

	if ( false === strpos( $class_name, ' site-branding ' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( ! $processor->next_tag() ) {
		return $block_content;
	}

	$processor->set_attribute( 'id', 'site-branding-publisher' );
	autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'publisher' ) );
	$processor->set_attribute( 'itemscope', '' );
	$processor->set_attribute( 'itemtype', 'https://schema.org/Organization' );

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/group', 'autonomie_render_block_group_site_branding_publisher', 12, 2 );

/**
 * Add microformats2 classes to post title block.
 */
function autonomie_render_block_post_title( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor      = new WP_HTML_Tag_Processor( $block_content );
	$title_updated  = false;
	$link_updated   = false;

	while ( $processor->next_tag() ) {
		if ( ! $title_updated && $processor->has_class( 'wp-block-post-title' ) ) {
			autonomie_tag_processor_add_classes( $processor, array( 'p-name', 'entry-title' ) );
			autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'name', 'headline' ) );
			$title_updated = true;
		}

		if ( ! $link_updated && 'A' === $processor->get_tag() ) {
			autonomie_tag_processor_add_classes( $processor, array( 'u-url', 'url' ) );
			autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'url' ) );
			$link_updated = true;
		}

		if ( $title_updated && $link_updated ) {
			break;
		}
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-title', 'autonomie_render_block_post_title', 10, 2 );

/**
 * Add microformats2 classes to post content block.
 */
function autonomie_render_block_post_content( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( $processor->next_tag( array( 'class_name' => 'wp-block-post-content' ) ) ) {
		autonomie_tag_processor_add_classes( $processor, array( 'e-content', 'entry-content' ) );
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'articleBody' ) );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-content', 'autonomie_render_block_post_content', 10, 2 );

/**
 * Add microformats2 classes to post excerpt block.
 */
function autonomie_render_block_post_excerpt( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( $processor->next_tag( array( 'class_name' => 'wp-block-post-excerpt' ) ) ) {
		autonomie_tag_processor_add_classes( $processor, array( 'p-summary', 'entry-summary' ) );
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'description' ) );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-excerpt', 'autonomie_render_block_post_excerpt', 10, 2 );

/**
 * Add microformats2 classes to post date block.
 */
function autonomie_render_block_post_date( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( $processor->next_tag( array( 'tag_name' => 'time' ) ) ) {
		autonomie_tag_processor_add_classes( $processor, array( 'dt-published', 'published', 'entry-date' ) );
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'datePublished' ) );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-date', 'autonomie_render_block_post_date', 10, 2 );

/**
 * Add microformats2 classes to post author block.
 */
function autonomie_render_block_post_author( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor          = new WP_HTML_Tag_Processor( $block_content );
	$author_name_found  = false;

	if ( $processor->next_tag( array( 'class_name' => 'wp-block-post-author' ) ) ) {
		autonomie_tag_processor_add_classes( $processor, array( 'h-card', 'p-author', 'author', 'vcard' ) );
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'author' ) );
		$processor->set_attribute( 'itemscope', '' );
		$processor->set_attribute( 'itemtype', 'https://schema.org/Person' );
	}

	if ( $processor->next_tag( array( 'class_name' => 'wp-block-post-author__name' ) ) ) {
		autonomie_tag_processor_add_classes( $processor, array( 'p-name', 'fn' ) );
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'name' ) );
		$author_name_found = true;
	}

	if ( $author_name_found && $processor->next_tag( array( 'tag_name' => 'a' ) ) ) {
		autonomie_tag_processor_add_classes( $processor, array( 'u-url', 'url' ) );
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'url' ) );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-author', 'autonomie_render_block_post_author', 10, 2 );

/**
 * Add microformats2 classes to post featured image block.
 */
function autonomie_render_block_post_featured_image( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	// Check post format to determine which microformat class to use
	$post_format = get_post_format();

	if ( 'image' === $post_format || 'gallery' === $post_format ) {
		$mf_class = 'u-photo';
	} else {
		$mf_class = 'u-featured';
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	// Add microformat class to figure.
	if ( $processor->next_tag( array( 'class_name' => 'wp-block-post-featured-image' ) ) ) {
		autonomie_tag_processor_add_classes( $processor, array( $mf_class ) );
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'image' ) );
		$processor->set_attribute( 'itemscope', '' );
		$processor->set_attribute( 'itemtype', 'https://schema.org/ImageObject' );
	}

	// Add itemprop to img.
	if ( $processor->next_tag( array( 'tag_name' => 'img' ) ) ) {
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'url', 'contentUrl' ) );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-featured-image', 'autonomie_render_block_post_featured_image', 10, 2 );

/**
 * Add microformats2 classes to post terms (categories/tags) block.
 */
function autonomie_render_block_post_terms( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	// Add p-category class to each term link.
	while ( $processor->next_tag( array( 'tag_name' => 'a' ) ) ) {
		$rel = $processor->get_attribute( 'rel' );

		if ( is_string( $rel ) && preg_match( '/\btag\b/i', $rel ) ) {
			$processor->add_class( 'p-category' );
		}
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-terms', 'autonomie_render_block_post_terms', 10, 2 );

/**
 * Add microformats2 classes to site title block.
 */
function autonomie_render_block_site_title( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( ! is_singular() ) {
		if ( $processor->next_tag( array( 'class_name' => 'wp-block-site-title' ) ) ) {
			autonomie_tag_processor_add_classes( $processor, array( 'p-name' ) );
			autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'name' ) );
		}
	}

	if ( ! is_singular() ) {
		if ( $processor->next_tag( array( 'tag_name' => 'a' ) ) ) {
			autonomie_tag_processor_add_classes( $processor, array( 'u-url', 'url' ) );
			autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'url' ) );
		}
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/site-title', 'autonomie_render_block_site_title', 10, 2 );

/**
 * Add schema properties to the existing site logo for publisher semantics.
 */
function autonomie_render_block_site_logo( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( $processor->next_tag( array( 'class_name' => 'wp-block-site-logo' ) ) ) {
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'logo' ) );
		$processor->set_attribute( 'itemscope', '' );
		$processor->set_attribute( 'itemtype', 'https://schema.org/ImageObject' );
	}

	if ( $processor->next_tag( array( 'tag_name' => 'a' ) ) ) {
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'url' ) );
	}

	if ( $processor->next_tag( array( 'tag_name' => 'img' ) ) ) {
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'contentUrl' ) );
	}

	$updated_content = $processor->get_updated_html();
	$logo_id         = 0;

	if ( ! empty( $block['attrs']['id'] ) ) {
		$logo_id = (int) $block['attrs']['id'];
	}

	if ( ! $logo_id ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
	}

	$logo_url = '';

	if ( $logo_id ) {
		$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
	}

	if ( is_string( $logo_url ) && '' !== $logo_url ) {
		$logo_url_escaped = esc_url( $logo_url );
		$meta_markup      = '<meta itemprop="url" content="' . $logo_url_escaped . '" /><meta itemprop="contentUrl" content="' . $logo_url_escaped . '" />';
		$updated          = preg_replace(
			'/(<[^>]*\bclass=(["\'])[^"\']*\bwp-block-site-logo\b[^"\']*\2[^>]*>)/i',
			'$1' . $meta_markup,
			$updated_content,
			1
		);

		if ( is_string( $updated ) ) {
			$updated_content = $updated;
		}
	}

	return $updated_content;
}
add_filter( 'render_block_core/site-logo', 'autonomie_render_block_site_logo', 10, 2 );

/**
 * Add semantics to query title block.
 */
function autonomie_render_block_query_title( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	if ( ! is_singular() && ! is_home() ) {
		$processor = new WP_HTML_Tag_Processor( $block_content );

		if ( $processor->next_tag( array( 'class_name' => 'wp-block-query-title' ) ) ) {
			autonomie_tag_processor_add_classes( $processor, array( 'p-name' ) );
			autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'name' ) );
		}

		return $processor->get_updated_html();
	}

	return $block_content;
}
add_filter( 'render_block_core/query-title', 'autonomie_render_block_query_title', 10, 2 );

/**
 * Add semantics to term description block.
 */
function autonomie_render_block_term_description( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	if ( ! is_singular() ) {
		$processor = new WP_HTML_Tag_Processor( $block_content );

		if ( $processor->next_tag( array( 'class_name' => 'wp-block-term-description' ) ) ) {
			autonomie_tag_processor_add_classes( $processor, array( 'p-summary', 'e-content' ) );
			autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'description' ) );
		}

		return $processor->get_updated_html();
	}

	return $block_content;
}
add_filter( 'render_block_core/term-description', 'autonomie_render_block_term_description', 10, 2 );

/**
 * Add microformats2 classes to comment blocks.
 */
function autonomie_render_block_comment_template( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );
	$classes   = array( 'h-cite', 'p-comment' );

	// Add semantic classes to comment items and author wrappers.
	while ( $processor->next_tag() ) {
		if ( 'LI' === $processor->get_tag() && $processor->has_class( 'comment' ) ) {
			autonomie_tag_processor_add_classes( $processor, $classes );
		}

		if ( $processor->has_class( 'comment-author' ) ) {
			autonomie_tag_processor_add_classes( $processor, array( 'p-author', 'author', 'vcard', 'hcard', 'h-card' ) );
			autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'creator' ) );
			$processor->set_attribute( 'itemscope', '' );
			$processor->set_attribute( 'itemtype', 'https://schema.org/Person' );
		}
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/comment-template', 'autonomie_render_block_comment_template', 10, 2 );

/**
 * Add author h-card classes to rendered post comments markup.
 */
function autonomie_render_block_post_comments( $block_content, $block ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	while ( $processor->next_tag( array( 'class_name' => 'comment-author' ) ) ) {
		autonomie_tag_processor_add_classes( $processor, array( 'p-author', 'author', 'vcard', 'hcard', 'h-card' ) );
		autonomie_tag_processor_merge_space_attr( $processor, 'itemprop', array( 'creator' ) );
		$processor->set_attribute( 'itemscope', '' );
		$processor->set_attribute( 'itemtype', 'https://schema.org/Person' );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-comments', 'autonomie_render_block_post_comments', 10, 2 );

/**
 * Add semantic HTML5 search element to search block.
 */
function autonomie_render_block_search( $block_content, $block ) {
	return autonomie_add_search_form_semantics( $block_content );
}
add_filter( 'render_block_core/search', 'autonomie_render_block_search', 10, 2 );
