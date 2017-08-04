<?php
/*
  Default Template
 *	The Goal of this Template is to be a general all-purpose model that will be replaced by customization in other templates
 */

$kind = get_post_kind_slug( get_the_ID() );
$meta = new Kind_Meta( get_the_ID() );
$author = Kind_View::get_hcard( $meta->get_author() );
$cite = $meta->get_cite();
$url = $meta->get_url();
$site_name = Kind_View::get_site_name( $meta->get_cite(), $url );
$title = Kind_View::get_cite_title( $meta->get_cite(), $url );
$embed = self::get_embed( $url );
$rsvp = $meta->get( 'rsvp' );

if ( ! $kind ) {
	return;
}

// Add in the appropriate type
switch ( $kind ) {
	case 'like':
		$type = 'p-like-of';
		break;
	case 'favorite':
		$type = 'p-favorite-of';
		break;
	case 'repost':
		$type = 'p-repost-of';
		break;
	case 'reply':
	case 'rsvp':
		$type = 'p-in-reply-to';
		break;
	case 'tag':
		$type = 'p-tag-of';
		break;
	case 'bookmark':
		$type = 'p-bookmark-of';
		break;
	case 'listen':
		$type = 'p-listen';
		break;
	case 'watch':
		$type = 'p-watch';
		break;
	case 'game':
		$type = 'p-play';
		break;
	case 'wish':
		$type = 'p-wish';
		break;
	case 'read':
		$type = 'p-read-of';
		break;
	case 'quote':
		$type = 'u-quotation-of';
		break;
	case 'checkin':
		$type = '';
	default:
		return;
}
?>

<section class="h-cite response <?php echo $type; ?> ">
<header>
<?php
if( ! $embed ) {
	if ( $title ) {
		echo $title;
	}
	if ( $author ) {
		echo ' ' . __( 'by', 'indieweb-post-kinds' ) . ' ' . $author;
	}
	if ( $site_name ) {
		echo '<em> (' . $site_name . ')</em>';
	}
	if ( in_array( $kind, array( 'jam', 'listen', 'play', 'read', 'watch', 'audio', 'video' ) ) ) {
		$duration = $meta->get_duration();
		if ( $duration ) {
			echo '(<data class="p-duration" value="' . $duration . '">' . Kind_View::display_duration( $duration )  . '</data>)';
		}
	}
}
?>
</header>
<?php
if ( $cite ) {
	if ( $embed ) {
		echo sprintf( '<blockquote class="e-summary">%1s</blockquote>', $embed );
	} else if ( array_key_exists( 'summary', $cite ) ) {
		echo sprintf( '<blockquote class="e-summary">%1s</blockquote>', $cite['summary'] );
	}
}

if ( $rsvp ) {
	echo 'RSVP <span class="p-rsvp">' . $rsvp . '</span>';
}

// Close Response
?>
</section>

<?php
