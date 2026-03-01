<?php
/**
 * Title: Image Post Format
 * Slug: autonomie/post-image
 * Categories: posts
 * Post Types: post
 * Description: Image format - emphasizes the featured image
 */
?>

<!-- wp:group {"tagName":"article","className":"format-image h-entry hentry"} -->
<article class="wp-block-group format-image h-entry hentry">
	<!-- wp:group {"tagName":"header","className":"entry-header"} -->
	<header class="wp-block-group entry-header">
		<!-- wp:post-title {"level":1,"className":"entry-title p-name"} /-->

		<!-- wp:template-part {"slug":"post-meta","className":"entry-meta"} /-->
	</header>
	<!-- /wp:group -->

	<!-- wp:post-featured-image {"className":"u-photo","isLink":true,"style":{"spacing":{"margin":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} /-->

	<!-- wp:post-content {"className":"entry-content e-content","layout":{"type":"constrained"}} /-->
</article>
<!-- /wp:group -->
