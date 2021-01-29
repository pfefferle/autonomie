<address class="author p-author vcard hcard h-card" itemprop="author" itemscope="" itemtype="https://schema.org/Person">
	<?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
	<a class="url uid u-url u-uid fn p-name" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
		<span itemprop="name"><?php echo get_the_author(); ?></span>
	</a>
	<div class="note e-note" itemprop="description"><?php echo get_the_author_meta( 'description' ); ?></div>
	<a class="subscribe" href="<?php echo get_author_feed_link( get_the_author_meta( 'ID' ) ); ?>"><i class="openwebicons-feed"></i> <?php _e( 'Subscribe to author feed', 'autonomie' ); ?></a>
</address>
