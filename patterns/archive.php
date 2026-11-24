<?php
/**
 * Title: Archive
 * Slug: autonomie/archive
 * Categories: text
 * Keywords: archive, postgraph, years, months, categories, tags
 * Description: A posts per day graph and lists of all years, months, categories and tags.
 *
 * @package Autonomie
 */

?>
<!-- wp:autonomie/postgraph /-->

<!-- wp:autonomie/poststats /-->

<!-- wp:columns {"style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'Categories', 'autonomie' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:categories {"showPostCounts":true} /-->
	</div>
	<!-- /wp:column -->

	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'Years', 'autonomie' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:archives {"type":"yearly","showPostCounts":true} /-->
	</div>
	<!-- /wp:column -->

	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'Months', 'autonomie' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:archives {"showPostCounts":true} /-->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Tags', 'autonomie' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:tag-cloud /-->
