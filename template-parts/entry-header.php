	<header class="entry-header">
		<div class="entry-header-wrapper">
			<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta post-format">
				<?php echo apply_filters( 'autonomie_post_format', sprintf(
					'<a class="entry-format entry-format-%s" href="%s">%s</a>',
					autonomie_get_post_format(),
					esc_url( autonomie_get_post_format_link( autonomie_get_post_format() ) ),
					autonomie_get_post_format_string()
				) ); ?>
			</div>
			<?php endif; ?>

			<?php if ( ! in_array( get_post_format(), array( 'aside', 'quote', 'status' ) ) && ! empty( get_the_title() ) ) : ?>
			<h1 class="entry-title p-name" itemprop="name headline">
				<a href="<?php the_permalink(); ?>" class="u-url url" title="<?php printf( esc_attr__( 'Permalink to %s', 'autonomie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" itemprop="url">
					<?php the_title(); ?>
				</a>
			</h1>
			<?php endif; ?>

			<?php // if ( ! is_singular() ) : ?>
			<div class="entry-meta">
				<?php autonomie_posted_on(); ?>
			</div>
			<?php // endif; ?>
		</div>
	</header><!-- .entry-header -->

	<?php do_action( 'autonomie_before_entry_content' ); ?>
