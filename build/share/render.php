<?php
/**
 * Share Block - Server-side Render
 *
 * A share button that opens a dialog with the permalink, the shortlink and a
 * citation to copy, like the share options of the classic theme. Browsers with
 * the Web Share API open their native share sheet instead.
 *
 * @package Autonomie
 *
 * @var array    $attributes Block attributes.
 * @var WP_Block $block      Block instance.
 */

$share_post = get_post( $block->context['postId'] ?? null );

if ( ! $share_post ) {
	return;
}

$share_id        = wp_unique_id( 'autonomie-share-' );
$share_permalink = get_permalink( $share_post );
$share_shortlink = wp_get_shortlink( $share_post->ID );
$share_title     = get_the_title( $share_post );
$share_author    = get_the_author_meta( 'display_name', $share_post->post_author );

// Posts without a title are quoted, posts with a title are cited.
if ( $share_title ) {
	$share_cite = sprintf(
		'<cite class="h-cite"><a class="u-url p-name" href="%1$s">%2$s</a> (<span class="p-author h-card">%3$s</span> <time class="dt-published" datetime="%4$s">%5$s</time>)</cite>',
		esc_url( $share_permalink ),
		esc_html( wp_strip_all_tags( $share_title ) ),
		esc_html( $share_author ),
		esc_attr( get_the_date( 'c', $share_post ) ),
		esc_html( get_the_date( '', $share_post ) )
	);
} else {
	$share_cite = sprintf(
		'<blockquote class="h-cite"><p class="p-content">%1$s</p><small>— <a class="u-url p-author h-card" href="%2$s">%3$s</a></small></blockquote>',
		esc_html( get_the_excerpt( $share_post ) ),
		esc_url( $share_permalink ),
		esc_html( $share_author )
	);
}

$share_fields = array(
	'permalink' => array(
		'label'       => __( 'Link', 'autonomie' ),
		'description' => __( 'Paste the link wherever you want to share this post.', 'autonomie' ),
		'copy'        => __( 'Copy link to clipboard', 'autonomie' ),
		'value'       => $share_permalink,
	),
);

// Without a shortlink provider core falls back to ?p=ID, still shorter than most permalinks.
if ( $share_shortlink && $share_shortlink !== $share_permalink ) {
	$share_fields['shortlink'] = array(
		'label'       => __( 'Shortlink', 'autonomie' ),
		'description' => __( 'A shorter link, for places where space is tight.', 'autonomie' ),
		'copy'        => __( 'Copy shortlink to clipboard', 'autonomie' ),
		'value'       => $share_shortlink,
	);
}

$share_fields['cite'] = array(
	'label'       => __( 'Cite', 'autonomie' ),
	'description' => __( 'Paste the HTML into your own post to cite this one.', 'autonomie' ),
	'copy'        => __( 'Copy HTML to clipboard', 'autonomie' ),
	'value'       => $share_cite,
);
?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<indie-action do="post" with="<?php echo esc_url( $share_permalink ); ?>">
		<button
			type="button"
			class="wp-block-autonomie-share__button"
			commandfor="<?php echo esc_attr( $share_id ); ?>"
			command="show-modal"
			aria-haspopup="dialog"
		>
			<svg viewBox="0 -864 1000 1000" width="1em" height="1em" aria-hidden="true" focusable="false"><path transform="scale(1 -1)" fill="currentColor" d="M500 732q-77 0 -148 -30q-68 -30 -119.5 -84t-77.5 -123h-155v-119h200q19 1 34.5 12.5t21.5 28.5l1 7q13 54 48.5 97.5t86 68t108.5 24.5q77 0 140 -43v0q15 -10 33 -10q24 0 41.5 17.5t17.5 42.5q0 15 -7.5 29t-21.5 21q-92 61 -203 61zM500 507q-39 0 -71.5 -19.5 t-52 -52t-19.5 -71t19.5 -71.5t52 -52t71.5 -19t71.5 19t52 52t19.5 71.5t-19.5 71t-52 52t-71.5 19.5zM801 353v0q-20 0 -36 -12.5t-21 -29.5l-1 -6v0q-13 -54 -48.5 -97.5t-86 -68t-108.5 -24.5q-75 0 -137 41q-15 10 -34 10q-24 0 -41.5 -17t-17.5 -42q0 -15 7 -28 t19 -21v0q93 -61 204 -61q77 0 148 30q68 30 119.5 84t77.5 123h155v119h-199z"/></svg>
			<?php esc_html_e( 'Share', 'autonomie' ); ?>
		</button>
	</indie-action>

	<dialog
		id="<?php echo esc_attr( $share_id ); ?>"
		class="wp-block-autonomie-share__modal"
		aria-labelledby="<?php echo esc_attr( $share_id . '-title' ); ?>"
		closedby="any"
		data-share-title="<?php echo esc_attr( wp_strip_all_tags( $share_title ) ); ?>"
		data-share-url="<?php echo esc_url( $share_permalink ); ?>"
	>
		<div class="wp-block-autonomie-share__header">
			<h2 class="wp-block-autonomie-share__title" id="<?php echo esc_attr( $share_id . '-title' ); ?>"><?php esc_html_e( 'Sharing is caring ♥️', 'autonomie' ); ?></h2>
			<button
				type="button"
				class="wp-block-autonomie-share__close wp-element-button"
				commandfor="<?php echo esc_attr( $share_id ); ?>"
				command="close"
				aria-label="<?php esc_attr_e( 'Close dialog', 'autonomie' ); ?>"
			>
				<svg fill="currentColor" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
					<path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path>
				</svg>
			</button>
		</div>
		<div class="wp-block-autonomie-share__content">
			<?php foreach ( $share_fields as $share_key => $share_field ) : ?>
				<div class="wp-block-autonomie-share__section">
					<h4><?php echo esc_html( $share_field['label'] ); ?></h4>
					<div class="wp-block-autonomie-share__description">
						<?php echo esc_html( $share_field['description'] ); ?>
					</div>
					<div class="wp-block-autonomie-share__button-group">
						<label for="<?php echo esc_attr( $share_id . '-' . $share_key ); ?>" class="screen-reader-text">
							<?php echo esc_html( $share_field['label'] ); ?>
						</label>
						<?php if ( 'cite' === $share_key ) : ?>
							<textarea
								aria-readonly="true"
								id="<?php echo esc_attr( $share_id . '-' . $share_key ); ?>"
								readonly
								rows="4"
							><?php echo esc_textarea( $share_field['value'] ); ?></textarea>
						<?php else : ?>
							<input
								aria-readonly="true"
								class="wp-block-search__input"
								id="<?php echo esc_attr( $share_id . '-' . $share_key ); ?>"
								readonly
								type="text"
								value="<?php echo esc_attr( $share_field['value'] ); ?>"
							/>
						<?php endif; ?>
						<button
							aria-label="<?php echo esc_attr( $share_field['copy'] ); ?>"
							class="wp-element-button"
							data-share-copy="<?php echo esc_attr( $share_id . '-' . $share_key ); ?>"
							data-share-copied="<?php esc_attr_e( 'Copied!', 'autonomie' ); ?>"
							type="button"
							hidden
						><?php esc_html_e( 'Copy', 'autonomie' ); ?></button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</dialog>
</div>
