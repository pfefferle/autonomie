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
		<?php block_template_part( 'footer' ); ?>

		<div id="site-generator">
			<?php do_action( 'autonomie_credits' ); ?>
			<?php printf( __( 'This site is powered by %1$s and styled with the %2$s theme', 'autonomie' ), '<a href="https://wordpress.org/" rel="generator">WordPress</a>', '<a href="https://notiz.blog/projects/autonomie/">Autonomie</a>' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
