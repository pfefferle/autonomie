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

	$anchor    = function ( $id ) {
		return '--fn-' . preg_replace( '/[^a-zA-Z0-9_-]/', '', $id );
	};
	$processor = new WP_HTML_Tag_Processor( $block_content );
	$ids       = array();

	// The references in the text.
	while ( $processor->next_tag( 'sup' ) ) {
		$id = $processor->get_attribute( 'data-fn' );

		if ( is_string( $id ) && '' !== $id ) {
			$ids[ $id ] = true;
			$processor->set_attribute( 'style', 'anchor-name:' . $anchor( $id ) );
		}
	}

	if ( ! $ids ) {
		return $block_content;
	}

	// The entries in the footnotes list, which comes after the text. Each one
	// is an anchor too and knows the one before it, so style.css can start a
	// note below the previous one when their references are close together.
	$processor = new WP_HTML_Tag_Processor( $processor->get_updated_html() );
	$previous  = '';

	while ( $processor->next_tag( 'li' ) ) {
		$id = $processor->get_attribute( 'id' );

		if ( is_string( $id ) && isset( $ids[ $id ] ) ) {
			$style = 'position-anchor:' . $anchor( $id ) . ';anchor-name:' . $anchor( 'note-' . $id );

			if ( $previous ) {
				$style .= ';--fn-previous:' . $anchor( 'note-' . $previous );
			}

			$processor->set_attribute( 'style', $style );
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
