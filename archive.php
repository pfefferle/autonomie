<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */

get_header(); ?>

			<main id="primary" <?php autonomie_main_class(); ?><?php autonomie_semantics( 'main' ); ?>>

				<?php if ( have_posts() ) : ?>

					<?php rewind_posts(); ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); // phpcs:ignore ?>

						<?php
							/* Include the Post-Format-specific template for the content.
							* If you want to overload this in a child theme then include a file
							* called content-___.php (where ___ is the Post Format name) and that will be used instead.
							*/
							get_template_part( 'templates/content', get_post_format() );
						?>

					<?php endwhile; ?>

					<?php autonomie_content_nav( 'nav-below' ); ?>

					<?php else : ?>

					<article id="post-0" class="post no-results not-found">
						<header class="entry-header">
							<h2 class="entry-title p-entry-title"><?php _e( 'Nothing Found', 'autonomie' ); ?></h2>
						</header><!-- .entry-header -->

						<div class="entry-content e-entry-content">
							<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'autonomie' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry-content -->
					</article><!-- #post-0 -->

				<?php endif; ?>

			</main><!-- #content -->

<?php get_footer(); ?>
