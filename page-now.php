<?php
/**
 * Template Name: "/now" Template
 *
 * The template for displaying "/now" pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */

set_query_var( 'is_now', true );

get_header(); ?>

		<main id="primary" <?php autonomie_main_class( 'h-now' ); ?><?php autonomie_semantics( 'main' ); ?>>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'templates/content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #content -->

<?php get_footer(); ?>
