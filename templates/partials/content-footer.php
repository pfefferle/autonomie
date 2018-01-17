<?php if ( is_singular() ) : ?>
	<footer class="entry-footer entry-meta">
		<?php zenpress_syndication_links(); ?>
		<address class="author p-author vcard hcard h-card" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
			<a class="url uid u-url u-uid fn p-name" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
				<span itemprop="name"><?php echo get_the_author(); ?></span>
			</a>
			<div class="note e-note" itemprop="description"><?php echo get_the_author_meta( 'description' ); ?></div>
		</address>

		<?php
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list();
		if ( $categories_list ) :
		?>
		<div class="cat-links">
			<?php echo __( 'Categories', 'zenpress' ); ?>
			<?php printf( __( '%1$s', 'zenpress' ), $categories_list ); ?>
		</div>
		<?php endif; // End if categories ?>

		<?php
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '<ul><li>', '</li><li>', '</li></ul>' );
		if ( $tags_list ) :
		?>
		<div class="tag-links" itemprop="keywords">
			<?php echo __( 'Tags', 'zenpress' ); ?>
			<?php printf( __( '%1$s', 'zenpress' ), $tags_list ); ?>
		</div>
		<?php endif; // End if $tags_list ?>

		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<div class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'zenpress' ), __( '1 Comment', 'zenpress' ), __( '% Comments', 'zenpress' ) ); ?></div>
		<?php endif; ?>
	</footer><!-- #entry-meta -->
<?php else : ?>
	<?php zenpress_syndication_links(); ?>
	<footer class="entry-footer entry-meta">
		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<div class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'zenpress' ), __( '1 Comment', 'zenpress' ), __( '% Comments', 'zenpress' ) ); ?></div>
		<?php endif; ?>
	</footer><!-- #entry-meta -->
<?php endif; ?>
