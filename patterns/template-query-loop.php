<?php
/**
 * Title: Query Loop
 * Slug: autonomie/template-query-loop
 * Categories: query
 * Block Types: core/query
 * Description: Standard post list used by index, archive, and search templates.
 *
 * @package Autonomie
 */

?>
<!-- wp:query {"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
<div class="wp-block-query">
	<!-- wp:post-template -->
		<!-- wp:autonomie/post-format {"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}} /-->

		<!-- wp:post-title {"isLink":true,"linkTarget":"_self"} /-->

		<!-- wp:template-part {"slug":"post-meta","className":"entry-meta"} /-->

		<!-- wp:post-featured-image {"isLink":true} /-->

		<!-- wp:post-content /-->

		<!-- wp:post-comments-link {"className":"post-reactions","spacing":{"margin":{"top":"var:preset|spacing|40"}}} /-->
	<!-- /wp:post-template -->

	<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"center"}} -->
		<!-- wp:query-pagination-previous /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next /-->
	<!-- /wp:query-pagination -->

	<!-- wp:query-no-results -->
		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php echo esc_html_x( 'No posts found. Please try another search.', 'Message when no posts are found in a query loop.', 'autonomie' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search...","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true} /-->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
