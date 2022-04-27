<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */

get_header(); ?>

		<main id="primary" <?php autonomie_main_class(); ?><?php autonomie_semantics( 'main' ); ?>>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'templates/content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #content -->

<?php get_footer(); ?>
