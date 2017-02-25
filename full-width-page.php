<?php
/**
 * Template Name: Full-width, no sidebar
 * Description: A full-width template with no sidebar
 *
 * @package ZenPress
 * @since ZenPress 1.0.0
 */

get_header(); ?>

			<main id="primary">

				<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</main><!-- #content -->

<?php get_footer(); ?>
