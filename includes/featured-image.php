<?php
/**
 * Adds post-thumbnail support :)
 *
 * @since Autonomie 1.0.0
 */
function autonomie_the_post_thumbnail( $before = '', $after = '' ) {
	if ( autonomie_has_full_width_featured_image() ) {
		return;
	}

	if ( '' !== get_the_post_thumbnail() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'post-thumbnail' );

		if ( $image['1'] <= '400' ) {
			return;
		}

		$class = 'photo';

		$post_format = get_post_format();

		// use `u-photo` on photo/gallery posts
		if ( in_array( $post_format, array( 'image', 'gallery' ), true ) ) {
			$class .= ' u-photo';
		} else { // otherwise use `u-featured`
			$class .= ' u-featured';
		}

		echo $before;

		the_post_thumbnail(
			'post-thumbnail',
			array(
				'class' => $class,
				'itemprop' => 'image',
				'loading' => 'lazy',
			)
		);

		echo $after;
	}
}

/**
 * Adds post-thumbnail support :)
 *
 * @since Autonomie 1.0.0
 */
function autonomie_content_post_thumbnail( $content ) {
	if ( '' !== get_the_post_thumbnail() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'post-thumbnail' );

		if ( $image['1'] > '400' ) {
			return $content;
		}

		$class = 'alignright photo';

		$post_format = get_post_format();

		// use `u-photo` on photo/gallery posts
		if ( in_array( $post_format, array( 'image', 'gallery' ), true ) ) {
			$class .= ' u-photo';
		} else { // otherwise use `u-featured`
			$class .= ' u-featured';
		}

		$thumbnail = get_the_post_thumbnail(
			null,
			'post-thumbnail',
			array(
				'class' => $class,
				'itemprop' => 'image',
				'loading' => 'lazy',
			)
		);

		return sprintf( '<p>%s</p>%s', $thumbnail, $content );
	}

	return $content;
}
add_filter( 'the_content', 'autonomie_content_post_thumbnail' );

/**
 * Add a checkbox for Post Covers to the featured image metabox
 */
function autonomie_featured_image_meta( $content, $post_id ) {
	// Text for checkbox
	$text = __( 'Use as post cover (full-width)', 'autonomie' );

	// Get the current setting
	$value = esc_attr( get_post_meta( $post_id, 'full_width_featured_image', '1' ) );
	// Output the checkbox HTML
	$label = '<input type="hidden" name="full_width_featured_image" value="0">';
	$label .= '<label for="full_width_featured_image" class="selectit"><input name="full_width_featured_image" type="checkbox" id="full_width_featured_image" value="1" ' . checked( $value, 1, 0 ) . '> ' . $text . '</label>';

	return $content . $label;
}
add_filter( 'admin_post_thumbnail_html', 'autonomie_featured_image_meta', 10, 2 );

/**
 * Safe the Post Covers
 *
 * @param int $post_id The ID of the post being saved.
 */
function autonomie_save_post( $post_id ) {
	// if this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	if ( ! array_key_exists( 'full_width_featured_image', $_POST ) ) {
		return $post_id;
	}

	if ( ! array_key_exists( 'post_type', $_POST ) ) {
		return $post_id;
	}

	// check the user's permissions.
	if ( 'page' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	// sanitize user input.
	$full_width_featured_image = sanitize_text_field( $_POST['full_width_featured_image'] );

	// update the meta field in the database.
	update_post_meta( $post_id, 'full_width_featured_image', $full_width_featured_image );
}
add_action( 'save_post', 'autonomie_save_post', 5, 1 );

/**
 * Return true if Auto-Set Featured Image as Post Cover is enabled and it hasn't
 * been disabled for this post.
 *
 * Returns true if the current post has Full Width Featured Image enabled.
 *
 * Returns false if not a Single post type or there is no Featured Image selected
 * or none of the above conditions are true.
 */
function autonomie_has_full_width_featured_image() {
	// If this isn't a Single post type or we don't have a Featured Image set
	if ( ! ( is_single() || is_page() ) || ! has_post_thumbnail() ) {
		return false;
	}

	$full_width_featured_image = get_post_meta( get_the_ID(), 'full_width_featured_image', true );

	// If Use featured image as Post Cover has been checked in the Featured Image meta box, return true.
	if ( '1' === $full_width_featured_image ) {
		return true;
	}

	return false; // Default
}

/**
 * Enqueue theme scripts
 *
 * @uses wp_enqueue_scripts() To enqueue scripts
 *
 * @since Autonomie 1.0.0
 */
function autonomie_enqueue_featured_image_scripts() {
	if ( is_singular() && autonomie_has_full_width_featured_image() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

		$css = '.entry-header {
			background: linear-gradient(190deg, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.7)), url(' . $image[0] . ') no-repeat center center scroll;
		}' . PHP_EOL;

		wp_add_inline_style( 'autonomie-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'autonomie_enqueue_featured_image_scripts' );

/**
 * Add full-width-featured-image to body class when displaying a post with Full Width Featured Image enabled
 */
function autonomie_full_width_featured_image_post_class( $classes ) {
	if ( is_singular() && autonomie_has_full_width_featured_image() ) {
		$classes[] = 'has-full-width-featured-image';
	}
	return $classes;
}
add_filter( 'post_class', 'autonomie_full_width_featured_image_post_class' );

/**
 * Register the `full_width_featured_image` meta
 *
 * @return void
 */
function autonomie_register_meta() {
	register_meta(
		'post',
		'full_width_featured_image',
		array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'boolean',
		)
	);
}
add_action( 'init', 'autonomie_register_meta' );

/**
 * Enqueue the required block editor assets/JS files
 *
 * @return void
 */
function autonomie_enqueue_block_editor_assets() {
	wp_enqueue_script(
		'autonomie-block-editor',
		get_template_directory_uri() . '/assets/js/block-editor.js',
		array( 'wp-editor', 'wp-i18n', 'wp-element', 'wp-compose', 'wp-components' ),
		'1.0.0',
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'autonomie_enqueue_block_editor_assets', 9 );
