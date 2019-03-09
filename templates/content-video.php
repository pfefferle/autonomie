<?php
/**
 * The template for displaying posts in the Video Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package Autonom
 * @since Autonom 1.0.0
 */
?>

<article <?php autonom_post_id(); ?> <?php post_class(); ?><?php autonom_semantics( 'post' ); ?>>
	<?php get_template_part( 'templates/partials/entry-header' ); ?>

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary p-summary" itemprop="description articleBody">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<?php autonom_the_post_thumbnail( '<div class="entry-media">', '</div>' ); ?>
	<div class="entry-content e-content" itemprop="description articleBody">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'autonom' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'autonom' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php get_template_part( 'templates/partials/entry-footer' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->
