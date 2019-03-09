<?php
/**
 * Register widgetized area and update sidebar with default widgets
 */
function autonom_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar 1', 'autonom' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Sidebar 2', 'autonom' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional second sidebar area', 'autonom' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Sidebar 3', 'autonom' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional second sidebar area', 'autonom' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Entry-Meta', 'autonom' ),
		'id' => 'entry-meta',
		'description' => __( 'Extend the Entry-Meta', 'autonom' ),
		'before_widget' => '',
		'after_widget'  => '',
	) );
}
add_action( 'widgets_init', 'autonom_widgets_init' );
