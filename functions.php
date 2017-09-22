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

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 900; /* pixels */
}

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
		global $content_width;

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
		add_image_size( 'zenpress-image-post', $content_width, 1288 );

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

		add_theme_support( 'gutenberg', array(
			'wide-images' => true,
			'colors' => array(
				'#0073aa',
				'#229fd8',
				'#eee',
				'#444',
			),
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
		add_editor_style( 'css/editor-style.css' );

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
				'height'      => 30,
				'width'       => 30,
			)
		);

		// This theme supports a custom header
		$custom_header_args = array(
			'width'		 	=> 1250,
			'height'		=> 600,
			'header-text'   => true,
		);
		add_theme_support( 'custom-header', $custom_header_args );
	}
endif; // zenpress_setup

/**
 * Tell WordPress to run zenpress_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'zenpress_setup' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function zenpress_page_menu_args( $args ) {
	$args['show_home'] = true;

	return $args;
}
add_filter( 'wp_page_menu_args', 'zenpress_page_menu_args' );

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
}
add_action( 'init', 'zenpress_widgets_init' );

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

		wp_dequeue_style( 'gutenbergthemeblocks-style' );
		wp_dequeue_style( 'gutenbergtheme-fonts' );

		wp_localize_script(
			'zenpress',
			'vars',
			array(
				'template_url' => get_template_directory_uri(),
			)
		);

		if ( zenpress_has_full_width_featured_image() ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

			$css = '.site-header .page-banner {
				background: url(' . $image[0] . ') no-repeat center center scroll;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;

			}' . PHP_EOL;

			wp_add_inline_style( 'zenpress-style', $css );
		} elseif ( get_header_image() ) {
			$css = '.site-header .page-banner {
				background: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.7)), url(' . get_header_image() . ') no-repeat center center scroll;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
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
		<?php if ( is_single() ) : // navigation links for single posts ?>
		<nav id="<?php echo $nav_id; ?>">
			<h1 class="assistive-text section-heading"><?php _e( 'Post navigation', 'zenpress' ); ?></h1>
			<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">&laquo;</span>' ); ?>
			<?php next_post_link( '<div class="nav-next">%link</div>', '<span class="meta-nav">&raquo;</span>' ); ?>
		</nav><!-- #<?php echo $nav_id; ?> -->
		<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
		<nav id="<?php echo $nav_id; ?>">
			<h1 class="assistive-text section-heading"><?php _e( 'Post navigation', 'zenpress' ); ?></h1>
			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span>', 'zenpress' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( '<span class="meta-nav">&raquo;</span>', 'zenpress' ) ); ?></div>
			<?php endif; ?>
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

		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			case 'webmention' :
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
						printf( __( '%1$s at %2$s', 'zenpress' ), get_comment_date(), get_comment_time() ); ?>
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
						printf( __( '%1$s at %2$s', 'zenpress' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
				</footer>

				<div class="comment-content e-content p-summary p-name" itemprop="text name description"><?php comment_text(); ?></div>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
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
 * replace post-title with id when empty
 *
 * @since ZenPress 1.4.6
 *
 * @param string $title the post-title
 * @param int $id the post-id
 * @return string the filtered post-title
 */
function zenpress_the_title( $title, $id ) {
	// if title is empty, return the id
	if ( empty( $title ) ) {
		return "#$id";
	}

	return $title;
}
add_filter( 'the_title', 'zenpress_the_title', 10, 2 );

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

	$id = (int) $post->ID;
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
 * This theme was built with PHP, Semantic HTML, CSS, love, and ZenPress.
 */
