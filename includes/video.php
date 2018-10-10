<?php
function zenpress_video_shortcode( $output, $atts, $video, $post_id, $library ) {
	global $content_width;
	static $instance = 0;
  $instance++;

	$html_atts = array(
		'class'    => str_replace('wp-video-shortcode','',$atts['class']),
		'id'       => sprintf( 'video-%d-%d', $post_id, $instance ),
		'width'    => absint( $atts['width'] ),
		'height'   => absint( $atts['height'] ),
		'poster'   => esc_url( $atts['poster'] ),
		'loop'     => wp_validate_boolean( $atts['loop'] ),
		'autoplay' => wp_validate_boolean( $atts['autoplay'] ),
		'preload'  => $atts['preload'],
	);

	// These ones should just be omitted altogether if they are blank
	foreach ( array( 'poster', 'loop', 'autoplay', 'preload' ) as $a ) {
			if ( empty( $html_atts[$a] ) ) {
					unset( $html_atts[$a] );
			}
	}

	$attr_strings = array('control');
	foreach ( $html_atts as $k => $v ) {
			$attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
	}

?>
<div class="video-wrapper">
    <video width="<?php echo $content_width; ?>" <?php echo join( ' ', $attr_strings )." " ?>>
        <source src="<?php echo $atts['src']; ?>"></source>
        Your browser does not support the <code>video</code> tag.
    </video>
    <div class="video-controls">
        <button data-media="play-pause"></button>
        <button data-media="mute-unmute"><span></span></button>
    </div>
</div>
<?php
}

function zenpress_enqueue_video_js() {
  wp_enqueue_script('zenpress-video',get_template_directory_uri().'/js/video.js');
}

add_filter( 'wp_video_shortcode', 'zenpress_video_shortcode', 10, 5 );
add_action( 'wp_enqueue_scripts', 'zenpress_enqueue_video_js');
