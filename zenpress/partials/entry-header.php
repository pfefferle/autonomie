	<header class="entry-header">
		<?php edit_post_link( __( 'Edit', 'zenpress' ), '<div class="edit-link">', '</div>' ); ?>
		<?php if ( get_post_format() !== false ) : ?>
		<div class="entry-meta">
			<div class="post-format">
				<a class="entry-format entry-format-<?php echo get_post_format(); ?>" href="<?php echo esc_url( get_post_format_link( get_post_format() ) ); ?>"><?php echo get_post_format_string( get_post_format() ); ?></a>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( ! in_array( get_post_format(), array( 'aside', 'quote', 'link', 'status' ) ) ) : ?>
		<h2 class="entry-title p-name" itemprop="name headline">
			<a href="<?php the_permalink(); ?>" class="u-url url" title="<?php printf( esc_attr__( 'Permalink to %s', 'zenpress' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" itemprop="url">
				<?php the_title(); ?>
			</a>
		</h2>
		<?php endif; ?>

		<div class="entry-meta">
			<?php zenpress_posted_on(); ?>
		</div>
	</header><!-- .entry-header -->
