<div id="post-nav">
	<?php $prevPost = get_previous_post(true);
		if($prevPost) {
			$args = array(
				'posts_per_page' => 1,
				'include' => $prevPost->ID
			);
			$prevPost = get_posts($args);
			foreach ($prevPost as $post) {
				setup_postdata($post);
	?>
		<div class="previous-post" style="background-image: url( <?php the_post_thumbnail_url( $post->ID, "medium" ); ?>">
			<a class="previous" href="<?php the_permalink(); ?>">&laquo; Previous Story</a>
			<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			<small><?php the_date('F j, Y'); ?></small>
		</div>
	<?php
				wp_reset_postdata();
			} //end foreach
		} // end if

		$nextPost = get_next_post(true);
		if($nextPost) {
			$args = array(
				'posts_per_page' => 1,
				'include' => $nextPost->ID
			);
			$nextPost = get_posts($args);
			foreach ($nextPost as $post) {
				setup_postdata($post);
	?>
		<div class="next-post" style="background-image: url( <?php the_post_thumbnail_url( $post->ID, "medium" ); ?>">
			<a class="next" href="<?php the_permalink(); ?>">Next Story &raquo;</a>
			<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			<small><?php the_date('F j, Y'); ?></small>
		</div>
	<?php
				wp_reset_postdata();
			} //end foreach
		} // end if
	?>
</div>
