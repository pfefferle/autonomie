<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package ZenPress
 * @since ZenPress 1.0.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)	]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<link rel="profile" href="http://microformats.org/profile/specs" />
<link rel="profile" href="http://microformats.org/profile/hatom" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?><?php zenpress_semantics( 'body' ); ?>>
<div id="page">
<?php do_action( 'before' ); ?>
	<header id="branding">
		<nav id="access">
			<h1 id="site-title"<?php zenpress_semantics( 'site-title' ); ?>>
				<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"<?php zenpress_semantics( 'site-url' ); ?>>
				<?php if ( get_theme_mod( 'website_logo' ) ) { ?>
					<img src="<?php echo get_theme_mod( 'website_logo' ); ?>" height="60" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
				<?php } else { ?>
					<?php bloginfo( 'name' ); ?>
				<?php } ?>
				</a>
			</h1>

			<?php get_search_form( true ); ?>

			<div class="assistive-text"><a href="#access" title="<?php esc_attr_e( 'Main menu', 'zenpress' ); ?>"><?php _e( 'Main menu', 'zenpress' ); ?></a></div>
			<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'zenpress' ); ?>"><?php _e( 'Skip to content', 'zenpress' ); ?></a></div>

			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav><!-- #access -->

		<div class="hero">
			<h2 id="site-description"<?php zenpress_semantics( 'site-description' ); ?>><?php bloginfo( 'description' ); ?></h2>
		</div>
	</header><!-- #branding -->
