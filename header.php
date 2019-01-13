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
<html <?php language_attributes(); ?>>
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
	<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'zenpress' ); ?>"><?php _e( 'Skip to content', 'zenpress' ); ?></a></div>
	<?php do_action( 'before' ); ?>
	<header id="site-header" class="site-header">
		<div class="site-branding">
			<?php
			if ( has_custom_logo() ) {
				echo get_custom_logo();
			}

			if ( is_home() ) {
				$site_title_element = 'h1';
			} else {
				$site_title_element = 'div';
			}
			?>
			<<?php echo $site_title_element; ?> id="site-title"<?php zenpress_semantics( 'site-title' ); ?>>
				<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"<?php zenpress_semantics( 'site-url' ); ?>>
				<?php bloginfo( 'name' ); ?>
				</a>
			</<?php echo $site_title_element; ?>>

			<?php get_search_form( true ); ?>
		</div>

		<nav id="site-navigation" class="site-navigation">
			<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'zenpress' ); ?></button>

			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav><!-- #site-navigation -->

		<?php if ( zenpress_show_page_banner() ) : ?>
		<div class="page-banner">
			<?php if ( ! is_singular() ) : ?>
			<div class="page-branding">
				<?php if ( zenpress_get_the_archive_title() ) { ?>
				<h1 id="page-title"<?php zenpress_semantics( 'page-title' ); ?>><?php echo zenpress_get_the_archive_title(); ?></h1>
				<?php } ?>
				<?php if ( zenpress_get_the_archive_description() ) { ?>
				<div id="page-description"<?php zenpress_semantics( 'page-description' ); ?>><?php echo zenpress_get_the_archive_description(); ?></div>
				<?php } ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</header><!-- #site-header -->
