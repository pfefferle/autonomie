<footer class="entry-footer entry-meta">
	<?php
	/* translators: used between list items, there is a space after the comma */
	$categories_list = get_the_category_list();
	if ( $categories_list ) :
	?>
	<div class="cat-links">
		<?php printf( __( '%1$s', 'zenpress' ), $categories_list ); ?>
	</div>
	<?php endif; // End if categories ?>

	<?php
	/* translators: used between list items, there is a space after the comma */
	$tags_list = get_the_tag_list( '<ul><li>', '</li><li>', '</li></ul>' );
	if ( $tags_list ) :
	?>
	<div class="tag-links" itemprop="keywords">
		<?php printf( __( '%1$s', 'zenpress' ), $tags_list ); ?>
	</div>
	<?php endif; // End if $tags_list ?>

	<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
	<div class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'zenpress' ), __( '1 Comment', 'zenpress' ), __( '% Comments', 'zenpress' ) ); ?></span>
	<?php endif; ?>
</footer><!-- #entry-meta -->
