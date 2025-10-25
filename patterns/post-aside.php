<?php
/**
 * Title: Aside Post Format
 * Slug: autonomie/post-aside
 * Categories: posts
 * Post Types: post
 * Description: Aside format - short note without title, larger text
 */

return array(
	'title'      => __( 'Aside Post Format', 'autonomie' ),
	'categories' => array( 'posts' ),
	'postTypes'  => array( 'post' ),
	'content'    => '<!-- wp:group {"tagName":"article","className":"format-aside h-entry hentry"} -->
<article class="wp-block-group format-aside h-entry hentry">
	<!-- wp:group {"tagName":"header","className":"entry-header"} -->
	<header class="wp-block-group entry-header">
		<!-- wp:template-part {"slug":"post-meta","className":"entry-meta"} /-->
	</header>
	<!-- /wp:group -->

	<!-- wp:post-content {"className":"entry-content e-content","style":{"typography":{"fontSize":"1.5em"}},"layout":{"type":"constrained"}} /-->
</article>
<!-- /wp:group -->',
);
