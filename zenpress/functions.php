<?php
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
  $content_width = 900; /* pixels */

function zenpress_setup() {
  // This theme uses post thumbnails
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 900, 1288 ); // Unlimited height, soft crop

  // Register custom image size for image post formats.
  add_image_size( 'sempress-image-post', 900, 1288 );

  // remove some theme supports from SemPress
  remove_theme_support( 'custom-background' );
  remove_theme_support( 'custom-header' );

  // remove some unneded actions
  remove_action( 'wp_head', 'sempress_customize_css');
  remove_action( 'customize_register', 'sempress_customize_register' );
}
/**
 * Tell WordPress to run sempress_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'zenpress_setup', 12 );