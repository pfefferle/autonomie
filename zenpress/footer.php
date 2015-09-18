<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package ZenPress
 * @since ZenPress 1.0.0
 */
?>
	<footer id="colophon">
		<?php get_sidebar(); ?>

		<div id="site-generator">
			<?php do_action( 'zenpress_credits' ); ?>
			<?php printf( __( 'This site is powered by %1$s and styled with %2$s', 'zenpress' ), '<a href="http://wordpress.org/" rel="generator">WordPress</a>', '<a href="http://notizblog.org/projects/zenpress/">ZenPress</a>' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
