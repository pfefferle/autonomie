<?php
/*
 * Video Template
 *
 */

$meta = new Kind_Meta( get_the_ID() );
$videos = get_attached_media( 'video', get_the_ID() );
$photos = get_attached_media( 'image', get_the_ID() );
$first_photo = array_pop(array_reverse($photos));
$cite = $meta->get_cite();
$url = $meta->get_url();
$embed = self::get_embed( $meta->get_url() );

if(isset($cite['name'])) : ?>
	<section class="response">
		<?php if ( isset( $cite['name'] ) ) : ?>
		<header>
		<?php echo sprintf( '<span class="p-name">%1s</a>', $cite['name'] ); ?>
		</header>
		<?php endif; ?>
	</section>
<?php
endif;

if ( $videos && ! has_post_thumbnail( get_the_ID() ) ) {
	$poster = wp_get_attachment_image_src($first_photo->ID,'full')[0];
	echo wp_video_shortcode(array(
		'poster' => $poster
	));
}
else {
	if ( $embed ) {
		echo sprintf( '<blockquote class="e-summary">%1s</blockquote>', $embed );
	}
}
?>
