<?php
/**
 * Title: Status Post Format
 * Slug: autonomie/post-status
 * Categories: posts
 * Post Types: post
 * Description: Status update format - short update without title
 */
?>

<!-- wp:group {"tagName":"article","className":"format-status h-entry hentry"} -->
<article class="wp-block-group format-status h-entry hentry">
	<!-- wp:group {"tagName":"header","className":"entry-header"} -->
	<header class="wp-block-group entry-header">
		<!-- wp:template-part {"slug":"post-meta","className":"entry-meta"} /-->
	</header>
	<!-- /wp:group -->

	<!-- wp:post-content {"className":"entry-content e-content","fontSize":"large","layout":{"type":"constrained"}} /-->
</article>
<!-- /wp:group -->
