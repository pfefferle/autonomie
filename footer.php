<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */
?>
	<footer id="colophon">
		<?php get_sidebar(); ?>

		<div id="site-publisher" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<meta itemprop="name" content="<?php echo get_bloginfo( 'name', 'display' ); ?>" />
			<meta itemprop="url" content="<?php echo esc_url( home_url( '/' ) ); ?>" />
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
			<?php do_action( 'autonomie_credits' ); ?>
			<?php printf( __( 'This site is powered by %1$s and styled with the %2$s theme', 'autonomie' ), '<a href="https://wordpress.org/" rel="generator">WordPress</a>', '<a href="https://notiz.blog/projects/autonomie/">Autonomie</a>' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
