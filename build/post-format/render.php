<?php
/**
 * Post Format Block - Server-side Render
 *
 * @package Autonomie
 * @since 2.0.0
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 */

// Load auto-generated icon paths
require_once __DIR__ . '/icon-paths.php';

// Get post ID from context or global
if ( ! isset( $block->context['postId'] ) ) {
	global $post;
	if ( ! $post ) {
		return '';
	}
	$post_id = $post->ID;
} else {
	$post_id = $block->context['postId'];
}

$format = get_post_format( $post_id );

// Default to standard if no format is set
if ( ! $format ) {
	$format = 'standard';
}

$format_string = get_post_format_string( $format );
$format_link = get_post_format_link( $format );

// For standard format, use custom link function from feed.php
if ( ! $format_link && 'standard' === $format ) {
	if ( function_exists( 'autonomie_get_post_format_link' ) ) {
		$format_link = autonomie_get_post_format_link( $format );
	}
}

// Get icons from auto-generated icon paths
$icons = autonomie_get_post_format_icon_paths();

$icon_path = isset( $icons[ $format ] ) ? $icons[ $format ] : $icons['standard'];
$icon_svg = sprintf(
	'<svg class="format-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="%s"></path></svg>',
	esc_attr( $icon_path )
);

// Build wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'format-' . esc_attr( $format ),
	)
);
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( $format_link ) : ?>
		<a href="<?php echo esc_url( $format_link ); ?>" class="u-url url" itemprop="url">
			<?php echo $icon_svg; ?>
			<span class="format-text"><?php echo esc_html( $format_string ); ?></span>
		</a>
	<?php else : ?>
		<span class="format-display">
			<?php echo $icon_svg; ?>
			<span class="format-text"><?php echo esc_html( $format_string ); ?></span>
		</span>
	<?php endif; ?>
</div>
