	<header class="entry-header">
		<?php edit_post_link( __( 'Edit', 'zenpress' ), '<div class="edit-link">', '</div>' ); ?>
		<?php if ( get_post_format() !== false ) { ?>
		<div class="entry-meta post-format">
			<a class="entry-format entry-format-<?php echo get_post_format(); ?>" href="<?php echo esc_url( get_post_format_link( get_post_format() ) ); ?>"><?php echo get_post_format_string( get_post_format() ); ?></a>
		</div>
		<?php } elseif ( ! is_page() ) { ?>
		<div class="entry-meta post-format">
			<span class="entry-format entry-format-standard"><?php echo __( 'Article', 'ZenPress' ); ?></span>
		</div>
		<?php } ?>

		<?php if ( ! in_array( get_post_format(), array( 'aside', 'quote', 'status' ) ) && ! empty( get_the_title() ) ) : ?>
		<h2 class="entry-title p-name" itemprop="name headline">
			<a href="<?php the_permalink(); ?>" class="u-url url" title="<?php printf( esc_attr__( 'Permalink to %s', 'zenpress' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" itemprop="url">
				<?php the_title(); ?>
			</a>
		</h2>
		<?php endif; ?>

		<?php // if ( ! is_singular() ) : ?>
		<div class="entry-meta">
			<?php zenpress_posted_on(); ?>
		</div>
		<?php // endif; ?>
	</header><!-- .entry-header -->
