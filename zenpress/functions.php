<?php


function zenpress_setup() {
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