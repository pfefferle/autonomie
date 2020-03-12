<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */

get_header(); ?>

			<main id="primary" <?php autonomie_main_class(); ?><?php autonomie_semantics( 'main' ); ?>>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'templates/content', get_post_format() ); ?>

				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' !== get_comments_number() ) {
					comments_template( '', true );
				}
				?>

			<?php endwhile; // end of the loop. ?>

			</main><!-- #content -->

			<?php autonomie_content_nav( 'nav-below' ); ?>

<?php get_footer(); ?>
