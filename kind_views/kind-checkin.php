<?php
/*
 * Checkin Template
 *
 */

$meta = new Kind_Meta( get_the_ID() );
$cite = $meta->get_cite();
$url = $meta->get_url();
$embed = self::get_embed( $meta->get_url() );

?>

<section class="response p-checkin">
<header>
<?php
if( ! $embed ) {
	if ( ! array_key_exists( 'name', $cite ) ) {
		$cite['name'] = self::get_post_type_string( $url );
	}
	if ( isset( $url ) ) {
		echo sprintf( '<a href="%1s" class="p-name u-url">%2s</a>', $url, $cite['name'] );
	} else {
		echo sprintf( '<span class="p-name">%1s</span>', $cite['name'] );
	}
}
?>
</header>
<?php
if ( $cite ) {
	if ( $embed ) {
		echo sprintf( '<blockquote class="e-summary">%1s</blockquote>', $embed );
	}
}

// Close Response
?>
</section>
