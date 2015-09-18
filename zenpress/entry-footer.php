<footer class="entry-footer entry-meta">
	<?php
		if (in_array(get_post_format(), array("aside", "link", "status", "quote"))) {
			zenpress_posted_on();
	?>
	<span class="sep"> | </span>
	<?php
		}
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'zenpress' ) );
		if ( $categories_list ) :
	?>
	<span class="cat-links">
		<?php printf( __( '%1$s', 'zenpress' ), $categories_list ); ?>
	</span>
	<span class="sep"> | </span>
	<?php endif; // End if categories ?>

	<?php
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'zenpress' ) );
		if ( $tags_list ) :
	?>
	<span class="tag-links" itemprop="keywords">
		<?php printf( __( '%1$s', 'zenpress' ), $tags_list ); ?>
	</span>
	<span class="sep"> | </span>
	<?php endif; // End if $tags_list ?>

	<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
	<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'zenpress' ), __( '1 Comment', 'zenpress' ), __( '% Comments', 'zenpress' ) ); ?></span>
	<span class="sep"> | </span>
	<?php endif; ?>

	<?php edit_post_link( __( 'Edit', 'zenpress' ), '<span class="edit-link">', '</span>' ); ?>
</footer><!-- #entry-meta -->
