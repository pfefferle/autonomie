<?php
/**
 * Template functions: changes to the output of core blocks and lists that
 * are about presentation, not about semantics (see semantics.php) or
 * backwards compatibility (see compat.php).
 *
 * @package Autonomie
 */

/**
 * Sidenotes, as in Tufte's books: give each footnote reference and its entry
 * in the footnotes list a matching CSS anchor name. On wide screens style.css
 * moves the list entry into the right margin, next to its reference, with
 * CSS anchor positioning. The note stays in the list, once: links, screen
 * readers and microformats keep working, and the editor shows the plain list.
 *
 * @param string $block_content The rendered post content.
 * @return string The post content with anchor names.
 */
function autonomie_render_block_post_content_sidenotes( $block_content ) {
	if ( false === strpos( $block_content, 'data-fn=' ) ) {
		return $block_content;
	}

	$anchor     = function ( $id ) {
		return '--fn-' . preg_replace( '/[^a-zA-Z0-9_-]/', '', $id );
	};
	$containers = array( 'P', 'LI', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'BLOCKQUOTE', 'FIGCAPTION', 'TD', 'TH', 'DT', 'DD' );

	// The references in the text, and the paragraph (or list item, heading,
	// cell) each one is in. That one is an anchor too: Firefox doesn't accept a
	// reference inside a paragraph styled with ::first-line (the "New thought"
	// style) as an anchor, then the note starts next to the paragraph instead.
	$processor = new WP_HTML_Tag_Processor( $block_content );
	$ids       = array();
	$blocks    = array();
	$container = 0;

	while ( $processor->next_tag() ) {
		$tag = $processor->get_tag();

		if ( in_array( $tag, $containers, true ) ) {
			++$container;
			continue;
		}

		if ( 'SUP' !== $tag ) {
			continue;
		}

		$id = $processor->get_attribute( 'data-fn' );

		if ( is_string( $id ) && '' !== $id ) {
			$ids[ $id ]              = $container;
			$blocks[ $container ][] = $anchor( 'p-' . $id );
			$processor->set_attribute( 'style', 'anchor-name:' . $anchor( $id ) );
		}
	}

	if ( ! $ids ) {
		return $block_content;
	}

	// The containers of the references, and the entries in the footnotes list,
	// which comes after the text. Each entry is an anchor too and knows the one
	// before it, so style.css can start a note below the previous one when
	// their references are close together. The number is written out too:
	// browsers don't agree on the list counter of absolutely positioned list
	// items (Firefox keeps counting across lists).
	$processor = new WP_HTML_Tag_Processor( $processor->get_updated_html() );
	$container = 0;
	$previous  = '';
	$number    = 0;

	while ( $processor->next_tag() ) {
		$tag = $processor->get_tag();

		if ( ! in_array( $tag, $containers, true ) ) {
			continue;
		}

		++$container;

		if ( isset( $blocks[ $container ] ) ) {
			$style = trim( (string) $processor->get_attribute( 'style' ), '; ' );
			$style = ( $style ? $style . ';' : '' ) . 'anchor-name:' . implode( ',', $blocks[ $container ] );
			$processor->set_attribute( 'style', $style );
		}

		$id = 'LI' === $tag ? $processor->get_attribute( 'id' ) : null;

		if ( is_string( $id ) && isset( $ids[ $id ] ) ) {
			$style = 'position-anchor:' . $anchor( $id ) . ';anchor-name:' . $anchor( 'note-' . $id ) . ';--fn-block:' . $anchor( 'p-' . $id );

			if ( $previous ) {
				$style .= ';--fn-previous:' . $anchor( 'note-' . $previous );
			}

			$processor->set_attribute( 'style', $style );
			$processor->set_attribute( 'data-fn-number', (string) ++$number );
			$previous = $id;
		}
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-content', 'autonomie_render_block_post_content_sidenotes' );

/**
 * Featured images at their own size: small images float right, next to the
 * text, and no image is scaled up beyond its original width. Skipped for
 * images sized in the editor and for full-width images (post cover).
 *
 * @param string   $block_content The rendered featured image.
 * @param array    $block         The parsed block.
 * @param WP_Block $instance      The block instance.
 * @return string The featured image.
 */
function autonomie_render_block_post_featured_image_size( $block_content, $block, $instance = null ) {
	$attrs = $block['attrs'];

	if ( ! empty( $attrs['aspectRatio'] ) || ! empty( $attrs['width'] ) || ! empty( $attrs['height'] ) || 'full' === ( $attrs['align'] ?? '' ) ) {
		return $block_content;
	}

	$post_id  = isset( $instance->context['postId'] ) ? $instance->context['postId'] : get_the_ID();
	$metadata = wp_get_attachment_metadata( get_post_thumbnail_id( $post_id ) );
	$width    = isset( $metadata['width'] ) ? (int) $metadata['width'] : 0;

	if ( ! $width ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	// Float small images right, next to the text.
	if ( $processor->next_tag( array( 'class_name' => 'wp-block-post-featured-image' ) ) && $width <= 400 ) {
		autonomie_tag_processor_remove_classes( $processor, array( 'alignwide' ) );
		autonomie_tag_processor_add_classes( $processor, array( 'alignright' ) );
	}

	// Do not upscale images that are smaller than the container.
	if ( $processor->next_tag( array( 'tag_name' => 'img' ) ) ) {
		$style = trim( (string) $processor->get_attribute( 'style' ), '; ' );
		$style = ( $style ? $style . ';' : '' ) . 'max-width:' . $width . 'px;';
		$processor->set_attribute( 'style', $style );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/post-featured-image', 'autonomie_render_block_post_featured_image_size', 10, 3 );

/**
 * Post counts in category lists without brackets, in their own element, so
 * style.css can set them apart as a quiet number instead of "(12)".
 *
 * @param string $output The HTML list of categories.
 * @return string The list with wrapped counts.
 */
function autonomie_list_categories_count( $output ) {
	return preg_replace( '#</a>\s*\(([^()<]+)\)#', '</a> <span class="count">$1</span>', $output );
}
add_filter( 'wp_list_categories', 'autonomie_list_categories_count' );

/**
 * Post counts in year and month lists without brackets, like the categories.
 * Only for lists, the dropdown keeps the brackets.
 *
 * @param string $link_html The archive link HTML.
 * @param string $url       The archive URL.
 * @param string $text      The archive text.
 * @param string $format    The link format.
 * @return string The link with a wrapped count.
 */
function autonomie_archives_link_count( $link_html, $url, $text, $format ) {
	if ( 'html' !== $format ) {
		return $link_html;
	}

	return preg_replace( '#</a>(?:&nbsp;|\s)*\(([^()<]+)\)#', '</a> <span class="count">$1</span>', $link_html );
}
add_filter( 'get_archives_link', 'autonomie_archives_link_count', 10, 4 );
