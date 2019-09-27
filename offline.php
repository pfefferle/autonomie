<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */

get_header(); ?>

		<main id="primary" <?php autonomie_main_class(); ?><?php autonomie_semantics( 'main' ); ?>>

			<article id="post-0" class="post error offline">
				<header class="entry-header">
					<h1 class="entry-title p-name"><?php _e( 'Offline', 'autonomie' ); ?></h1>
				</header>

				<div class="entry-content e-content">
					<p><?php wp_service_worker_error_message_placeholder(); ?></p>
				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		</main><!-- #content -->

<?php get_footer(); ?>
