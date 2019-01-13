<?php
/**
 * Adds post-thumbnail support :)
 *
 * @since ZenPress 1.0.0
 */
function zenpress_the_post_thumbnail( $before = '', $after = '' ) {
	if ( zenpress_has_full_width_featured_image() ) {
		return;
	}

	if ( '' != get_the_post_thumbnail() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'post-thumbnail' );
		$class = '';

		if ( $image['1'] < '300' ) {
			$class = 'alignright';
		}

		$class .= ' photo';

		$post_format = get_post_format();

		// use `u-photo` on photo/gallery posts
		if ( in_array( $post_format, array( 'image', 'gallery' ) ) ) {
			$class .= ' u-photo';
		} else { // otherwise use `u-featured`
			$class .= ' u-featured';
		}

		echo $before;
		the_post_thumbnail( 'post-thumbnail', array( 'class' => $class, 'itemprop' => 'image' ) );
		echo $after;
	}
}

/**
 * Add a checkbox for Post Covers to the featured image metabox
 */
function zenpress_featured_image_meta( $content ) {
	// If we don't have a featured image, nothing to do.
	if ( ! has_post_thumbnail() ) {
		return $content;
	}
	global $post;

	// Text for checkbox
	$text = __( 'Use as post cover (full-width)', 'zenpress' );

	// Get the current setting
	$value = esc_attr( get_post_meta( $post->ID, 'full_width_featured_image', '1' ) );
	// Output the checkbox HTML
	$label = '<input type="hidden" name="full_width_featured_image" value="0">';
	$label .= '<label for="full_width_featured_image" class="selectit"><input name="full_width_featured_image" type="checkbox" id="full_width_featured_image" value="1" ' . checked( $value, 1, 0 ) . '> ' . $text . '</label>';

	$label = wp_nonce_field( 'zenpress_full_width_featured_image_meta', 'zenpress_full_width_featured_image_meta_nonce' ) . $label;
	return $content .= $label;
}
add_filter( 'admin_post_thumbnail_html', 'zenpress_featured_image_meta' );

/**
 * Safe the Post Covers
 *
 * @param int $post_id The ID of the post being saved.
 */
function zenpress_save_post( $post_id ) {
	// check if the nonce is set.
	if ( ! isset( $_POST['zenpress_full_width_featured_image_meta_nonce'] ) ) {
		return $post_id;
	}
	$nonce = $_POST['zenpress_full_width_featured_image_meta_nonce'];

	// verify that the nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'zenpress_full_width_featured_image_meta' ) ) {
		return $post_id;
	}

	// if this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// check the user's permissions.
	if ( 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	// sanitize user input.
	$mydata = sanitize_text_field( $_POST['full_width_featured_image'] );
	// update the meta field in the database.
	update_post_meta( $post_id, 'full_width_featured_image', $mydata );
}
add_action( 'save_post', 'zenpress_save_post', 5, 1 );

/**
 * Return true if Auto-Set Featured Image as Post Cover is enabled and it hasn't
 * been disabled for this post.
 *
 * Returns true if the current post has Full Width Featured Image enabled.
 *
 * Returns false if not a Single post type or there is no Featured Image selected
 * or none of the above conditions are true.
 */
function zenpress_has_full_width_featured_image() {
	// If this isn't a Single post type or we don't have a Featured Image set
	if ( ! ( is_single() || is_page() ) || ! has_post_thumbnail() ) {
		return false;
	}

	$full_width_featured_image = get_post_meta( get_the_ID(), 'full_width_featured_image', true );

	// If Use featured image as Post Cover has been checked in the Featured Image meta box, return true.
	if ( '1' == $full_width_featured_image ) {
		return true;
	}

	return false; // Default
}

/**
 * Enqueue theme scripts
 *
 * @uses wp_enqueue_scripts() To enqueue scripts
 *
 * @since ZenPress 1.0.0
 */
function zenpress_enqueue_features_image_scripts() {
	if ( is_singular() && zenpress_has_full_width_featured_image() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

		$css = '.entry-header {
			background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.7)), url(' . $image[0] . ') no-repeat center center scroll;
		}' . PHP_EOL;

		wp_add_inline_style( 'zenpress-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'zenpress_enqueue_features_image_scripts' );

/**
 * Add full-width-featured-image to body class when displaying a post with Full Width Featured Image enabled
 */
function zenpress_full_width_featured_image_post_class( $classes ) {
	if ( is_singular() && zenpress_has_full_width_featured_image() ) {
		$classes[] = 'has-full-width-featured-image';
	}
	return $classes;
}
add_filter( 'post_class', 'zenpress_full_width_featured_image_post_class' );
