<?php
/**
 * Title: Standard Post Format
 * Slug: autonomie/post-standard
 * Categories: posts
 * Post Types: post
 * Description: Standard post format with title, content, and featured image
 */
?>

<!-- wp:group {"tagName":"article","className":"format-standard h-entry hentry"} -->
<article class="wp-block-group format-standard h-entry hentry">
	<!-- wp:group {"tagName":"header","className":"entry-header"} -->
	<header class="wp-block-group entry-header">
		<!-- wp:post-title {"level":1,"className":"entry-title p-name"} /-->

		<!-- wp:template-part {"slug":"post-meta","className":"entry-meta"} /-->
	</header>
	<!-- /wp:group -->

	<!-- wp:post-featured-image {"className":"u-featured","style":{"spacing":{"margin":{"top":"30px","bottom":"30px"}}}} /-->

	<!-- wp:post-content {"className":"entry-content e-content","layout":{"type":"constrained"}} /-->
</article>
<!-- /wp:group -->
