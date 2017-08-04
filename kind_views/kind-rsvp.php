<?php
/*
 * RSVP Template
 *
 */

$meta = new Kind_Meta( get_the_ID() );
$author = Kind_View::get_hcard( $meta->get_author() );
$cite = $meta->get_cite();
$url = $meta->get_url();
$title = isset( $cite['name'] ) ? $cite['name'] : $url;
$embed = self::get_embed( $url );
$rsvp = $meta->get( 'rsvp' );

?>

<section class="response">
<header>
<?php

if( ! $embed ) {
	if ( $rsvp ) {
		echo '<data class="p-rsvp" value=">' . $rsvp . '">' . sprintf( Kind_View::rsvp_text( $rsvp ), $url, $title ) . '</data>';
	}
}
?>
</header>
<?php
if ( $embed ) {
	echo sprintf( '<blockquote class="e-summary">%1s</blockquote>', $embed );
} else if ( array_key_exists( 'summary', $cite ) ) {
	echo sprintf( '<blockquote class="e-summary">%1s</blockquote>', $cite['summary'] );
}

// Close Response
?>
</section>
