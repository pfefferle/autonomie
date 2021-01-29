<?php
/**
 * Register widgetized area and update sidebar with default widgets.
 */
function autonomie_widgets_init() {
	require( get_template_directory() . '/widgets/class-autonomie-author-widget.php' );
	register_widget( 'Autonomie_Author_Widget' );

	require( get_template_directory() . '/widgets/class-autonomie-taxonomy-widget.php' );
	register_widget( 'Autonomie_Taxonomy_Widget' );

	register_sidebar(
		array(
			'name' => __( 'Sidebar 1', 'autonomie' ),
			'id' => 'sidebar-1',
			'description' => __( 'A sidebar area', 'autonomie' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name' => __( 'Sidebar 2', 'autonomie' ),
			'id' => 'sidebar-2',
			'description' => __( 'A second sidebar area', 'autonomie' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name' => __( 'Sidebar 3', 'autonomie' ),
			'id' => 'sidebar-3',
			'description' => __( 'A third sidebar area', 'autonomie' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name' => __( 'Entry-Meta', 'autonomie' ),
			'id' => 'entry-meta',
			'description' => __( 'Extend the Entry-Meta', 'autonomie' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		)
	);
}
add_action( 'widgets_init', 'autonomie_widgets_init' );

/**
 * Add entry-meta default widgets
 *
 * @param array $content Array of starter content.
 * @param array $config  Array of theme-specific starter content configuration.
 */
function autonomie_starter_content_add_widget( $content, $config ) {
	if ( ! isset( $content['widgets']['entry-meta'] ) ) {
		$content['widgets']['entry-meta'] = array();
	}

	$content['widgets']['entry-meta'][] = array(
		'autonomie-author',
		array(),
	);
	$content['widgets']['entry-meta'][] = array(
		'autonomie-taxonomy',
		array(),
	);

	return $content;
}
add_filter( 'get_theme_starter_content', 'autonomie_starter_content_add_widget', 10, 2 );

function autonomie_activate () {
	// Set up default widgets for default theme.
	update_option(
		'widget_autonomie-author',
		array(
			2              => array( 'title' => '' ),
			'_multiwidget' => 1,
		)
	);

	update_option(
		'widget_autonomie-taxonomy',
		array(
			2              => array( 'title' => '' ),
			'_multiwidget' => 1,
		)
	);

	update_option(
		'sidebars_widgets',
		array(
			'wp_inactive_widgets' => array(),
			'sidebar-1'           => array(
				0 => 'search-2',
				1 => 'recent-posts-2',
				2 => 'recent-comments-2',
			),
			'sidebar-2'           => array(
				0 => 'archives-2',
			),
			'sidebar-3'           => array(
				0 => 'categories-2',
				1 => 'meta-2',
			),
			'entry-meta'          => array(
				0 => 'autonomie-author-2',
				1 => 'autonomie-taxonomy-2',
			),
			'array_version'       => 3,
		)
	);
}

add_action('after_switch_theme', 'autonomie_activate');
