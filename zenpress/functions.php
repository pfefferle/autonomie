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
	$content_width = 1000; /* pixels */
}

/**
 * Set a default theme color array for WP.com.
 */
$themecolors = array(
	'bg' => 'f0f0f0',
	'border' => 'cccccc',
	'text' => '555555',
	'shadow' => 'ffffff',
);

if ( ! function_exists( 'zenpress_setup' ) ):
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
	global $themecolors;

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
	set_post_thumbnail_size( 700, 9999 ); // Unlimited height, soft crop

	// Register custom image size for image post formats.
	add_image_size( 'zenpress-image-post', 700, 1288 );

	// Switches default core markup for search form to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'zenpress' ),
	) );

	// Add support for the Aside, Gallery Post Formats...
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'status', 'image', 'video', 'audio', 'quote' ) );
	//add_theme_support( 'structured-post-formats', array( 'image', 'video', 'audio', 'quote' ) );

	/**
	 * This theme supports jetpacks "infinite-scroll"
	 *
	 * @see http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array('container' => 'content', 'footer' => 'colophon') );

	// This theme uses its own gallery styles.
	//add_filter( 'use_default_gallery_style', '__return_false' );

	// Nicer WYSIWYG editor
	add_editor_style( 'css/editor-style.css' );
}
endif; // zenpress_setup

/**
 * Tell WordPress to run zenpress_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'zenpress_setup' );

/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since 1.3.1
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function zenpress_wp_title($title, $sep) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'zenpress' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'zenpress_wp_title', 10, 2 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function zenpress_page_menu_args($args) {
	$args['show_home'] = true;

	return $args;
}
add_filter( 'wp_page_menu_args', 'zenpress_page_menu_args' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function zenpress_widgets_init()
{
	register_sidebar( array(
		'name' => __( 'Sidebar 1', 'zenpress' ),
		'id' => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Sidebar 2', 'zenpress' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional second sidebar area', 'zenpress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'init', 'zenpress_widgets_init' );

if ( ! function_exists( 'zenpress_enqueue_scripts' ) ) :
/**
 * Enqueue theme scripts
 *
 * @uses wp_enqueue_scripts() To enqueue scripts
 *
 * @since ZenPress 1.1.1
 */
function zenpress_enqueue_scripts() {
	/*
	 * Adds JavaScript to pages with the comment form to support sites with
	 * threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Add HTML5 support to older versions of IE
	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) &&
		 ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) ) &&
		 ( false === strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 9' ) ) ) {

		wp_enqueue_script('html5', get_template_directory_uri() . '/js/html5.js', false, '3.7.2');
	}

	// Loads our main stylesheet.
	wp_enqueue_style( 'zenpress-style', get_stylesheet_uri() );
}
endif;

add_action( 'wp_enqueue_scripts', 'zenpress_enqueue_scripts' );

if ( ! function_exists( 'zenpress_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since ZenPress 1.0.0
 */
function zenpress_content_nav($nav_id) {
	global $wp_query;

	?>
	<nav id="<?php echo $nav_id; ?>">
		<h1 class="assistive-text section-heading"><?php _e( 'Post navigation', 'zenpress' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'zenpress' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'zenpress' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'zenpress' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'zenpress' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
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
function zenpress_comment($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;

	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		case 'webmention' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment <?php $comment->comment_type; ?>" itemprop="comment" itemscope itemtype="http://schema.org/UserComments">
			<div class="comment-content p-summary p-name" itemprop="commentText name description"><?php comment_text(); ?></div>
			<footer>
				<div class="comment-meta commentmetadata">
					<address class="comment-author p-author author vcard hcard h-card" itemprop="creator" itemscope itemtype="http://schema.org/Person">
						<?php printf( '<cite class="fn p-name" itemprop="name">%s</cite>', get_comment_author_link() ); ?>
					</address>
					<span class="sep">-</span>
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time class="updated published dt-updated dt-published" datetime="<?php comment_time( 'c' ); ?>" itemprop="commentTime">
						<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'zenpress' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( '(Edit)', 'zenpress' ), ' ' ); ?>
				</div>
			</footer>
		</article>
	<?php
				break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment <?php $comment->comment_type; ?>" itemprop="comment" itemscope itemtype="http://schema.org/UserComments">
			<footer>
				<address class="comment-author p-author author vcard hcard h-card" itemprop="creator" itemscope itemtype="http://schema.org/Person">
					<?php echo get_avatar( $comment, 50 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'zenpress' ), sprintf( '<cite class="fn p-name" itemprop="name">%s</cite>', get_comment_author_link() ) ); ?>
				</address><!-- .comment-author .vcard -->
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'zenpress' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time class="updated published dt-updated dt-published" datetime="<?php comment_time( 'c' ); ?>" itemprop="commentTime">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'zenpress' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( '(Edit)', 'zenpress' ), ' ' ); ?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content e-content p-summary p-name" itemprop="commentText name description"><?php comment_text(); ?></div>

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
	printf( __( '<a href="%1$s" title="%2$s" rel="bookmark" class="url u-url"><time class="entry-date updated published dt-updated dt-published" datetime="%3$s" itemprop="dateModified">%4$s</time></a> <span class="sep"> | </span> <address class="byline"><span class="author p-author vcard hcard h-card" itemprop="author" itemscope itemtype="http://schema.org/Person">%5$s <a class="url uid u-url u-uid fn p-name" href="%6$s" title="%7$s" rel="author" itemprop="url"><span itemprop="name">%8$s</span></a></span></address>', 'zenpress' ),
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
 * Adds post-thumbnail support :)
 *
 * @since ZenPress 1.0.0
 */
function zenpress_the_post_thumbnail($before = '', $after = '') {
	if ( '' != get_the_post_thumbnail() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'post-thumbnail' );
		$class = '';

		if ( $image['1'] < '300' ) {
			$class = 'alignright';
		}

		echo $before;
		the_post_thumbnail( 'post-thumbnail', array( 'class' => $class . ' photo u-photo', 'itemprop' => 'image' ) );
		echo $after;
	}
}

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since ZenPress 1.3.0
 */
function zenpress_content_width() {
	if ( is_page_template( 'full-width-page.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 880;
	}

	/*
	if ( has_post_format( 'image' ) || has_post_format( 'video' ) || is_attachment() ) {
		global $content_width;
		$content_width = 668;
	}
	*/
}
add_action( 'template_redirect', 'zenpress_content_width' );

/**
 * replace post-title with id when empty
 *
 * @since ZenPress 1.4.6
 *
 * @param string $title the post-title
 * @param int $id the post-id
 * @return string the filtered post-title
 */
function zenpress_the_title($title, $id) {
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
function zenpress_enhanced_image_navigation($url) {
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
function zenpress_post_id($post_id = null) {
	if ( $post_id ) {
		echo 'id="' . $post_id	. '"';
	} else {
		echo 'id="' . zenpress_get_post_id()	. '"';
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

function zenpress_ptags( $content ) {
	// do a regular expression replace...
	// find all p tags that have just
	// <p>maybe some white space<img all stuff up to /> then maybe whitespace </p>
	// replace it with just the image tag...
	$content = preg_replace( '/<p>(\s*)(<img .*\/>)(\s*)<\/p>/iU', '\2', $content );
	$content = preg_replace( '/<p>(\s*)(<iframe.*>.*<\/iframe>)(\s*)<\/p>/iU', '\2', $content );

	return $content;
}

// we want it to be run after the autop stuff... 10 is default.
add_filter( 'the_content', 'zenpress_ptags', 99 );

/**
 * Adds some awesome websemantics like microformats(2) and microdata
 */
require( get_template_directory() . '/inc/semantics.php' );

/**
 * Adds back compat handling for older WP versions
 */
require( get_template_directory() . '/inc/compat.php' );

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and ZenPress.
 */
