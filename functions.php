<?php
/**
 * ZenPress functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package ZenPress
 * @since ZenPress 1.0.0
 */

if ( ! function_exists( 'zenpress_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 * To override zenpress_setup() in a child theme, add your own zenpress_setup to your child theme's
	 * functions.php file.
	 */
	function zenpress_setup() {
		$content_width = 900;

		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on zenpress, use a find and replace
		 * to change 'zenpress' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'zenpress', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// This theme uses post thumbnails
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( $content_width, 9999 ); // Unlimited height, soft crop

		// Register custom image size for image post formats.
		add_image_size( 'zenpress-image-post', $content_width, 1250 );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'widgets',
			)
		);

		add_theme_support( 'align-wide' );

		add_theme_support( 'editor-color-palette', array(
			array(
				'name'  => __( 'Blue', 'zenpress' ),
				'slug'  => 'blue',
				'color' => '#0073aa',
			),
			array(
				'name'  => __( 'Lighter blue', 'zenpress' ),
				'slug'  => 'lighter-blue',
				'color' => '#229fd8',
			),
			array(
				'name'  => __( 'Blue jeans', 'zenpress' ),
				'slug'  => 'blue-jeans',
				'color' => '#5bc0eb',
			),
			array(
				'name'  => __( 'Orioles orange', 'zenpress' ),
				'slug'  => 'orioles-orange',
				'color' => '#fa5b0f',
			),
			array(
				'name'  => __( 'USC gold', 'zenpress' ),
				'slug'  => 'usc-gold',
				'color' => '#ffcc00',
			),
			array(
				'name'  => __( 'Gargoyle gas', 'zenpress' ),
				'slug'  => 'gargoyle-gas',
				'color' => '#fde74c',
			),
			array(
				'name'  => __( 'Yellow', 'zenpress' ),
				'slug'  => 'yellow',
				'color' => '#fff9c0',
			),
			array(
				'name'  => __( 'Android green', 'zenpress' ),
				'slug'  => 'android-green',
				'color' => '#9bc53d',
			),
			array(
				'name'  => __( 'White', 'zenpress' ),
				'slug'  => 'white',
				'color' => '#fff',
			),
			array(
				'name'  => __( 'Very light gray', 'zenpress' ),
				'slug'  => 'very-light-gray',
				'color' => '#eee',
			),
			array(
				'name'  => __( 'Very dark gray', 'zenpress' ),
				'slug'  => 'very-dark-gray',
				'color' => '#444',
			)
		) );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'zenpress' ),
		) );

		// Add support for the Aside, Gallery Post Formats...
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
			)
		);

		// Nicer WYSIWYG editor
		add_theme_support( 'editor-styles' );
		add_editor_style( 'css/editor-style.css' );

		add_theme_support( 'responsive-embeds' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// custom logo support
		add_theme_support(
			'custom-logo',
			array(
				'height' => 30,
				'width'  => 30,
			)
		);

		// This theme supports a custom header
		$custom_header_args = array(
			'width'       => 1250,
			'height'      => 600,
			'header-text' => true,
		);
		add_theme_support( 'custom-header', $custom_header_args );

		/**
		 * Draw attention to supported WebSemantics
		 */
		add_theme_support( 'microformats2' );
		add_theme_support( 'microformats' );
		add_theme_support( 'microdata' );
		add_theme_support( 'indieweb' );

		//add_theme_support( 'amp' );
	}
endif; // zenpress_setup

/**
 * Tell WordPress to run zenpress_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'zenpress_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function zenpress_content_width() {
	$content_width = 900;

	$GLOBALS['content_width'] = apply_filters( 'zenpress_content_width', $content_width );
}
add_action( 'after_setup_theme', 'zenpress_content_width', 0 );

/**
 * Set the default maxwith for the embeds
 */
function zenpress_embed_defaults() {
	return array(
		'width'  => 900,
		'height' => 600,
	);
}
add_filter( 'embed_defaults', 'zenpress_embed_defaults' );

/**
 * Set the default with for the embeds
 * Fixes issues with Vimeo
 */
function zenpress_oembed_fetch_url( $provider ) {
	$provider = add_query_arg( 'width', 900, $provider );
	$provider = add_query_arg( 'height', 600, $provider );

	return $provider;
}
add_filter( 'oembed_fetch_url', 'zenpress_oembed_fetch_url', 99 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function zenpress_page_menu_args( $args ) {
	$args['show_home'] = true;

	return $args;
}
add_filter( 'wp_page_menu_args', 'zenpress_page_menu_args' );

if ( ! function_exists( 'zenpress_enqueue_scripts' ) ) :
	/**
	 * Enqueue theme scripts
	 *
	 * @uses wp_enqueue_scripts() To enqueue scripts
	 *
	 * @since ZenPress 1.0.0
	 */
	function zenpress_enqueue_scripts() {
		/*
		 * Adds JavaScript to pages with the comment form to support sites with
		 * threaded comments (when in use).
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Add  support to older versions of IE
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) &&
			( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) ) &&
			( false === strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 9' ) ) ) {

			wp_enqueue_script( '', get_template_directory_uri() . '/js/html5shiv.min.js', false, '3.7.3' );
		}

		wp_enqueue_script( 'zenpress-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0.0', true );
		wp_enqueue_script( 'zenpress-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '1.0.0', true );

		// Loads our main stylesheet.
		wp_enqueue_style( 'zenpress-style', get_stylesheet_uri() );
		wp_enqueue_style( 'zenpress-print-style', get_stylesheet_directory_uri() . '/css/print.css', array( 'zenpress-style' ), '1.0.0', 'print' );
		wp_enqueue_style( 'zenpress-narrow-style', get_stylesheet_directory_uri() . '/css/narrow-width.css', array( 'zenpress-style' ), '1.0.0', '(max-width: 800px)' );
		wp_enqueue_style( 'zenpress-default-style', get_stylesheet_directory_uri() . '/css/default-width.css', array( 'zenpress-style' ), '1.0.0', '(min-width: 800px)' );
		wp_enqueue_style( 'zenpress-wide-style', get_stylesheet_directory_uri() . '/css/wide-width.css', array( 'zenpress-style' ), '1.0.0', '(min-width: 1000px)' );

		wp_localize_script(
			'zenpress',
			'vars',
			array(
				'template_url' => get_template_directory_uri(),
			)
		);

		if ( has_header_image() ) {
			$css = '.page-banner {
				background: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.7)), url(' . get_header_image() . ') no-repeat center center scroll;
			}' . PHP_EOL;

			wp_add_inline_style( 'zenpress-style', $css );
		}
	}
endif;

add_action( 'wp_enqueue_scripts', 'zenpress_enqueue_scripts' );

if ( ! function_exists( 'zenpress_content_nav' ) ) :
	/**
	 * Display navigation to next/previous pages when applicable
	 *
	 * @since ZenPress 1.0.0
	 */
	function zenpress_content_nav( $nav_id ) {
		global $wp_query;
		?>
		<?php if ( is_home() || is_archive() || is_search() ) : // navigation links for home, archive, and search pages ?>
		<nav id="archive-nav">
			<h1 class="assistive-text section-heading"><?php _e( 'Post navigation', 'zenpress' ); ?></h1>
			<?php echo paginate_links(); ?>
		</nav><!-- #<?php echo $nav_id; ?> -->
		<?php endif; ?>
		<?php
	}
endif; // zenpress_content_nav

if ( ! function_exists( 'zenpress_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own zenpress_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since ZenPress 1.0.0
	 */
	function zenpress_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		switch ( $comment->comment_type ):
			case 'pingback':
			case 'trackback':
			case 'webmention':
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment <?php $comment->comment_type; ?>" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
				<div class="edit-link"><?php edit_comment_link( __( 'Edit', 'zenpress' ), ' ' ); ?></div>
				<div class="comment-content p-summary p-name" itemprop="text name description"><?php comment_text(); ?></div>
				<footer class="comment-meta commentmetadata">
					<address class="comment-author p-author author vcard hcard h-card" itemprop="creator" itemscope itemtype="http://schema.org/Person">
						<?php printf( '<cite class="fn p-name" itemprop="name">%s</cite>', get_comment_author_link() ); ?>
					</address>
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time class="updated published dt-updated dt-published" datetime="<?php comment_time( 'c' ); ?>" itemprop="dateCreated">
						<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'zenpress' ), get_comment_date(), get_comment_time() );
						?>
					</time></a>
				</footer>
			</article>
		<?php
				break;
			default :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment <?php $comment->comment_type; ?>" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
				<div class="edit-link"><?php edit_comment_link( __( 'Edit', 'zenpress' ), ' ' ); ?></div>
				<footer class="comment-meta commentmetadata">
					<address class="comment-author p-author author vcard hcard h-card" itemprop="creator" itemscope itemtype="http://schema.org/Person">
						<?php echo get_avatar( $comment, 40 ); ?>
						<?php printf( '<cite class="fn p-name" itemprop="name">%s</cite>', get_comment_author_link() ); ?>
					</address><!-- .comment-author .vcard -->
					<?php if ( '0' == $comment->comment_approved ) : ?>
						<em><?php _e( 'Your comment is awaiting moderation.', 'zenpress' ); ?></em>
					<?php endif; ?>

					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time class="updated published dt-updated dt-published" datetime="<?php comment_time( 'c' ); ?>" itemprop="dateCreated">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'zenpress' ), get_comment_date(), get_comment_time() );
					?>
					</time></a>
				</footer>

				<div class="comment-content e-content p-summary p-name" itemprop="text name description"><?php comment_text(); ?></div>

				<div class="reply">
					<?php
					comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
					?>
				</div><!-- .reply -->
			</article><!-- #comment-## -->
		<?php
				break;
		endswitch;
	}
endif; // ends check for zenpress_comment()

if ( ! function_exists( 'zenpress_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 * Create your own zenpress_posted_on to override in a child theme
	 *
	 * @since ZenPress 1.0.0
	 */
	function zenpress_posted_on() {
		printf( __( '<address class="byline"><span class="author p-author vcard hcard h-card" itemprop="author" itemscope itemtype="http://schema.org/Person">%5$s <a class="url uid u-url u-uid fn p-name" href="%6$s" title="%7$s" rel="author" itemprop="url"><span itemprop="name">%8$s</span></a></span></address> <span class="sep"> | </span> <a href="%1$s" title="%2$s" rel="bookmark" class="url u-url"><time class="entry-date updated published dt-updated dt-published" datetime="%3$s" itemprop="dateModified datePublished">%4$s</time></a>', 'zenpress' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			get_avatar( get_the_author_meta( 'ID' ), 40 ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'zenpress' ), get_the_author() ) ),
			esc_html( get_the_author() )
		);
	}
endif;

/**
 * Filter in a link to a content ID attribute for the next/previous image links on
 * image attachment pages
 *
 * @param string $url
 * @return string
 */
function zenpress_enhanced_image_navigation( $url ) {
	if ( is_admin() ) {
		return $url;
	}

	global $post, $wp_rewrite;

	$id     = (int) $post->ID;
	$object = get_post( $id );
	if ( wp_attachment_is_image( $post->ID ) && ( $wp_rewrite->using_permalinks() && ( $object->post_parent > 0 ) && ( $object->post_parent != $id ) ) ) {
		$url = $url . '#main';
	}

	return $url;
}
add_filter( 'attachment_link', 'zenpress_enhanced_image_navigation' );

/**
 * Display the id for the post div.
 *
 * @param string $id.
 */
function zenpress_post_id( $post_id = null ) {
	if ( $post_id ) {
		echo 'id="' . $post_id . '"';
	} else {
		echo 'id="' . zenpress_get_post_id() . '"';
	}
}

/**
 * Retrieve the id for the post div.
 *
 * @return string The post-id.
 */
function zenpress_get_post_id() {
	$post_id = 'post-' . get_the_ID();

	return apply_filters( 'zenpress_post_id', $post_id, get_the_ID() );
}

function zenpress_get_the_archive_title() {
	if ( is_archive() ) {
		return get_the_archive_title();
	} elseif ( is_search() ) {
		return sprintf( __( 'Search Results for: %s', 'zenpress' ), '<span>' . get_search_query() . '</span>' );
	}
}

/**
 * Returns the page description
 *
 * @return string The page description
 */
function zenpress_get_the_archive_description() {
	if ( is_home() ) {
		return get_bloginfo( 'description' );
	} elseif ( is_author() ) {
		return get_the_author_meta( 'description' );
	} elseif ( is_archive() ) {
		return get_the_archive_description();
	} elseif ( is_search() ) {
		// @see https://github.com/raamdev/independent-publisher/blob/513e7ff71312f585f13eb1460b4d9bc74d0b59bd/inc/template-tags.php#L674
		global $wp_query;
		$total = $wp_query->found_posts;
		$stats_text = sprintf( _n( 'Found one search result for <strong>%2$s</strong>.', 'Found %1$s search results for <strong>%2$s</strong>.', $total, 'zenpress' ), number_format_i18n( $total ), get_search_query() );

		return wpautop( $stats_text );
	}
}

function zenpress_show_page_banner() {
	if ( is_home() && ! display_header_text() ) {
		return false;
	}

	if ( is_home() || is_archive() || is_search() ) {
		return true;
	}

	return false;
}

function zenpress_get_post_format() {
	return get_post_format() ? : 'standard';
}

function zenpress_get_post_format_string() {
	if ( get_post_format() ) {
		return get_post_format_link( get_post_format() );
	} else {
		return __( 'Article', 'zenpress' );
	}
}

function zenpress_get_archive_type() {
	$type = null;

	if ( is_author() ) {
		$type = 'author';
	}

	return apply_filters( 'zenpress_archive_type', $type );
}

function zenpress_get_author_meta() {
	$meta = array();

	$meta[] = sprintf( __( '%s Posts', 'zenpress' ), count_user_posts( get_the_author_meta( 'ID' ) ) );
	$meta[] = sprintf( '<a rel="alternate" class="feed u-feed" href="%s">%s</a>', get_author_feed_link( get_the_author_meta( 'ID' ) ), __( 'Subscribe', 'zenpress' ) );

	$meta = apply_filters( 'zenpress_author_meta', $meta, get_the_author_meta( 'ID' ) );

	return implode( ' | ', $meta );
}

/**
 * Widget handling
 */
require( get_template_directory() . '/includes/widgets.php' );

/**
 * Adds the featured image functionality
 */
require( get_template_directory() . '/includes/featured-image.php' );

/**
 * Adds some awesome websemantics like microformats(2) and microdata
 */
require( get_template_directory() . '/includes/semantics.php' );

/**
 * Adds back compat handling for older WP versions
 */
require( get_template_directory() . '/includes/compat.php' );

/**
 * Compatibility with other plugins, mostly IndieWeb related
 */

if ( defined( 'SYNDICATION_LINKS_VERSION' ) ) {
	/**
	 * Adds Indieweb Syndcation Links
	 * if github.com/dshanske/syndication-links is activated
	 */
	require( get_template_directory() . '/integrations/syndication-links.php' );
}

if ( class_exists('Post_Kinds_Plugin') ) {
	require( get_template_directory() . '/integrations/post-kinds.php' );
}

if ( class_exists('ActivityPub') ) {
	require( get_template_directory() . '/integrations/activitypub.php' );
}

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and ZenPress.
 */
