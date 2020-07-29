<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<link rel="profile" href="http://microformats.org/profile/specs" />
	<link rel="profile" href="http://microformats.org/profile/hatom" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?><?php autonomie_semantics( 'body' ); ?>>
<?php
/**
 * @see: https://make.wordpress.org/core/2019/04/24/miscellaneous-developer-updates-in-5-2/
 */
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
} else {
	do_action( 'wp_body_open' );
}
?>
<div id="page">
	<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'autonomie' ); ?>"><?php _e( 'Skip to content', 'autonomie' ); ?></a></div>
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
			<<?php echo $site_title_element; ?> id="site-title"<?php autonomie_semantics( 'site-title' ); ?>>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"<?php autonomie_semantics( 'site-url' ); ?>>
				<?php bloginfo( 'name' ); ?>
				</a>
			</<?php echo $site_title_element; ?>>

			<?php get_search_form( true ); ?>
		</div>

		<nav id="site-navigation" class="site-navigation">
			<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'autonomie' ); ?></button>

			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav><!-- #site-navigation -->

		<?php get_template_part( 'template-parts/page-banner', autonomie_get_archive_type() ); ?>
	</header><!-- #site-header -->
