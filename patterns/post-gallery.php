<?php
/**
 * Title: Gallery Post Format
 * Slug: autonomie/post-gallery
 * Categories: posts
 * Post Types: post
 * Description: Gallery format - for image galleries
 */
?>

<!-- wp:group {"tagName":"article","className":"format-gallery h-entry hentry"} -->
<article class="wp-block-group format-gallery h-entry hentry">
	<!-- wp:group {"tagName":"header","className":"entry-header"} -->
	<header class="wp-block-group entry-header">
		<!-- wp:post-title {"level":1,"className":"entry-title p-name"} /-->

		<!-- wp:template-part {"slug":"post-meta","className":"entry-meta"} /-->
	</header>
	<!-- /wp:group -->

	<!-- wp:post-content {"className":"entry-content e-content","layout":{"type":"constrained"}} /-->
</article>
<!-- /wp:group -->
