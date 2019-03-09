<?php if ( is_singular() ) : ?>
	<footer class="entry-footer entry-meta">
		<address class="author p-author vcard hcard h-card" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
			<a class="url uid u-url u-uid fn p-name" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
				<span itemprop="name"><?php echo get_the_author(); ?></span>
			</a>
			<div class="note e-note" itemprop="description"><?php echo get_the_author_meta( 'description' ); ?></div>
			<a class="subscribe" href="<?php echo get_author_feed_link( get_the_author_meta( 'ID' ) ); ?>"><i class="openwebicons-feed"></i> <?php _e( 'Subscribe to author feed', 'autonom' ); ?></a>
		</address>

		<?php
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list();
		if ( $categories_list ) :
		?>
		<div class="cat-links">
			<?php echo __( 'Categories', 'autonom' ); ?>
			<?php printf( __( '%1$s', 'autonom' ), $categories_list ); ?>
		</div>
		<?php endif; // End if categories ?>

		<?php
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '<ul><li>', '</li><li>', '</li></ul>' );
		if ( $tags_list ) :
		?>
		<div class="tag-links" itemprop="keywords">
			<?php echo __( 'Tags', 'autonom' ); ?>
			<?php printf( __( '%1$s', 'autonom' ), $tags_list ); ?>
		</div>
		<?php endif; // End if $tags_list ?>

		<?php dynamic_sidebar( 'entry-meta' ); ?>
		<?php do_action( 'autonom-entry-footer' ); ?>

		<?php // get_template_part( 'templates/partials/entry', 'nav' ); ?>

		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<div class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'autonom' ), __( '1 Comment', 'autonom' ), __( '% Comments', 'autonom' ) ); ?></div>
		<?php endif; ?>
	</footer><!-- #entry-meta -->
<?php else : ?>
	<footer class="entry-footer entry-meta">
		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<div class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'autonom' ), __( '1 Comment', 'autonom' ), __( '% Comments', 'autonom' ) ); ?></div>
		<?php endif; ?>
	</footer><!-- #entry-meta -->
<?php endif; ?>
