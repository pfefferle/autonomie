<?php
/**
 * Autonomie FSE Theme Functions
 *
 * Theme setup and functionality for the Full Site Editing version of Autonomie.
 * This version removes classic theme features (widgets, customizer, custom headers)
 * and focuses on block-based editing while preserving semantic HTML and IndieWeb support.
 *
 * @package Autonomie
 * @since Autonomie 2.0.0
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function autonomie_setup() {
	// Content width for embeds and images
	$GLOBALS['content_width'] = 700;

	/**
	 * Make theme available for translation
	 */
	load_theme_textdomain( 'autonomie', get_template_directory() . '/languages' );

	/**
	 * Core WordPress Features
	 */

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Enable post thumbnails
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 700, 9999 ); // Unlimited height, soft crop

	// Register custom image size for image post formats
	add_image_size( 'autonomie-image-post', 700, 1250 );

	// HTML5 support for search form, comment form, comments, gallery, caption
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);

	// Wide and full alignment support
	add_theme_support( 'align-wide' );

	// Responsive embeds
	add_theme_support( 'responsive-embeds' );

	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Custom logo support
	add_theme_support(
		'custom-logo',
		array(
			'height' => 30,
			'width'  => 30,
		)
	);

	/**
	 * Post Format Support
	 * All 9 post formats with corresponding block patterns
	 */
	add_theme_support(
		'post-formats',
		array(
			'aside',
			'gallery',
			'link',
			'status',
			'image',
			'video',
			'audio',
			'quote',
			'chat',
		)
	);

	/**
	 * Block Editor Features
	 * Note: Editor styling is handled by theme.json, no separate editor-style.css needed
	 */

	// Add support for custom line heights
	add_theme_support( 'custom-line-height' );

	// Add support for custom units
	add_theme_support( 'custom-units' );

	// Add support for custom spacing
	add_theme_support( 'custom-spacing' );

	// Add support for appearance tools in theme.json
	add_theme_support( 'appearance-tools' );

	// Add support for link color
	add_theme_support( 'link-color' );

	// Add support for block template parts
	add_theme_support( 'block-template-parts' );

	/**
	 * Semantic Web Support
	 * Draw attention to supported web semantics
	 */
	add_theme_support( 'microformats2' );
	add_theme_support( 'microformats' );
	add_theme_support( 'microdata' );
	add_theme_support( 'indieweb' );

	/**
	 * Service Worker Support
	 * For PWA functionality
	 */
	add_theme_support( 'service_worker', true );
}
add_action( 'after_setup_theme', 'autonomie_setup' );

/**
 * Enqueue theme styles and scripts
 */
function autonomie_enqueue_scripts() {
	// Main stylesheet
	wp_enqueue_style(
		'autonomie-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);

	// Print styles
	wp_enqueue_style(
		'autonomie-print',
		get_template_directory_uri() . '/assets/css/print.css',
		array( 'autonomie-style' ),
		wp_get_theme()->get( 'Version' ),
		'print'
	);

	// Thread comments script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'autonomie_enqueue_scripts' );

/**
 * Enqueue block editor assets
 * Note: Editor styles are handled by theme.json
 * Currently no editor-specific scripts needed for FSE
 */
function autonomie_editor_assets() {
	// Reserved for future editor-specific scripts if needed
}
add_action( 'enqueue_block_editor_assets', 'autonomie_editor_assets' );

/**
 * Register custom blocks
 */
function autonomie_register_blocks() {
	// Register post format block from build directory
	register_block_type( __DIR__ . '/build/post-format' );
}
add_action( 'init', 'autonomie_register_blocks' );

/**
 * Register block patterns category
 */
function autonomie_register_block_pattern_category() {
	register_block_pattern_category(
		'autonomie',
		array(
			'label' => __( 'Autonomie', 'autonomie' ),
		)
	);
}
add_action( 'init', 'autonomie_register_block_pattern_category' );

/**
 * Register block patterns
 * NOTE: Temporarily disabled while patterns are being fixed
 */
function autonomie_register_block_patterns() {
	// TODO: Fix pattern files to return arrays instead of outputting HTML
	// $pattern_files = glob( get_template_directory() . '/patterns/*.php' );
	//
	// if ( ! $pattern_files ) {
	// 	return;
	// }
	//
	// foreach ( $pattern_files as $pattern_file ) {
	// 	register_block_pattern(
	// 		'autonomie/' . basename( $pattern_file, '.php' ),
	// 		require $pattern_file
	// 	);
	// }
}
add_action( 'init', 'autonomie_register_block_patterns' );

/**
 * Add pingback url auto-discovery header for singularly identifiable articles
 */
function autonomie_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s" />', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'autonomie_pingback_header' );

/**
 * Include additional theme files
 */

// Semantic HTML functions (microformats2, schema.org)
require get_template_directory() . '/includes/semantics.php';

// Feed customization
require get_template_directory() . '/includes/feed.php';

// Compatibility functions (comment form enhancements, etc.)
require get_template_directory() . '/includes/compat.php';

// WebActions support (IndieWeb comment interactions)
require get_template_directory() . '/includes/webactions.php';

/**
 * Add theme support for block styles
 */
function autonomie_register_block_styles() {
	// Register custom block styles here if needed
	// Example:
	// register_block_style(
	// 	'core/quote',
	// 	array(
	// 		'name'  => 'fancy-quote',
	// 		'label' => __( 'Fancy Quote', 'autonomie' ),
	// 	)
	// );
}
add_action( 'init', 'autonomie_register_block_styles' );

/**
 * Prevent orphans in post titles and content
 * Replaces the last space in a string with a non-breaking space
 */
function autonomie_prevent_orphans( $text ) {
	$text = rtrim( $text );
	$space = strrpos( $text, ' ' );

	if ( false !== $space ) {
		$text = substr( $text, 0, $space ) . '&nbsp;' . substr( $text, $space + 1 );
	}

	return $text;
}
// Uncomment to enable orphan prevention
// add_filter( 'the_title', 'autonomie_prevent_orphans' );
// add_filter( 'the_content', 'autonomie_prevent_orphans' );

/**
 * FSE Theme Compatibility Check
 * Display admin notice if WordPress version is too old
 */
function autonomie_check_theme_support() {
	global $wp_version;

	if ( version_compare( $wp_version, '6.4', '<' ) ) {
		add_action( 'admin_notices', 'autonomie_upgrade_notice' );
	}
}
add_action( 'after_setup_theme', 'autonomie_check_theme_support' );

/**
 * Display upgrade notice for old WordPress versions
 */
function autonomie_upgrade_notice() {
	?>
	<div class="notice notice-error">
		<p>
			<?php
			printf(
				/* translators: %s: WordPress version number */
				esc_html__( 'Autonomie requires WordPress version 6.4 or higher. You are running version %s. Please upgrade WordPress to use this theme.', 'autonomie' ),
				esc_html( $GLOBALS['wp_version'] )
			);
			?>
		</p>
	</div>
	<?php
}
