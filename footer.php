<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Autonom
 * @since Autonom 1.0.0
 */
?>
	<footer id="colophon">
		<?php get_sidebar(); ?>

		<div id="site-publisher" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<meta itemprop="name" content="<?php echo get_bloginfo( 'name', 'display' ); ?>" />
			<meta itemprop="url" content="<?php echo home_url( '/' ); ?>" />
			<?php

			if ( has_custom_logo() ) {
				$image = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) );
			?>
				<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
					<meta itemprop="url" content="<?php echo current( $image ); ?>" />
					<meta itemprop="width" content="<?php echo next( $image ); ?>" />
					<meta itemprop="height" content="<?php echo next( $image ); ?>" />
				</div>
			<?php } ?>
		</div>

		<div id="site-generator">
			<?php do_action( 'autonom_credits' ); ?>
			<?php printf( __( 'This site is powered by %1$s and styled with the %2$s theme', 'autonom' ), '<a href="http://wordpress.org/" rel="generator">WordPress</a>', '<a href="https://github.com/pfefferle/Autonom">Autonom</a>' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
