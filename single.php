<?php
/**
 * The Template for displaying all single posts.
 *
 * @package ZenPress
 * @since ZenPress 1.0.0
 */

get_header(); ?>

			<main id="primary">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>

				<?php zenpress_content_nav( 'nav-below' ); ?>

				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ) {
					comments_template( '', true );
				}
				?>

			<?php endwhile; // end of the loop. ?>

			</main><!-- #content -->

<?php get_footer(); ?>
