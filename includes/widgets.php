<?php
/**
 * Register widgetized area and update sidebar with default widgets
 */
function zenpress_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar 1', 'zenpress' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Sidebar 2', 'zenpress' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional second sidebar area', 'zenpress' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Sidebar 3', 'zenpress' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional second sidebar area', 'zenpress' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Entry-Meta', 'zenpress' ),
		'id' => 'entry-meta',
		'description' => __( 'Extend the Entry-Meta', 'zenpress' ),
		'before_widget' => '',
		'after_widget'  => '',
	) );
}
add_action( 'init', 'zenpress_widgets_init' );
