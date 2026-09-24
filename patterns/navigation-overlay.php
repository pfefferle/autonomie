<?php
/**
 * Title: Navigation Overlay
 * Slug: autonomie/navigation-overlay
 * Categories: header
 * Block Types: core/template-part/navigation-overlay
 * Inserter: no
 * Description: Full-screen mobile navigation overlay with a close button, large centered page list, and search.
 *
 * @package Autonomie
 */

?>
<!-- wp:group {"backgroundColor":"base","textColor":"contrast","className":"navigation-overlay-content","layout":{"type":"flex","orientation":"vertical","justifyContent":"right"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|78","left":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|60"},"dimensions":{"minHeight":"100vh"}}} -->
<div class="wp-block-group navigation-overlay-content has-contrast-color has-base-background-color has-text-color has-background" style="min-height:100vh;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--78);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:group {"className":"navigation-overlay-bar","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"center"}} -->
	<div class="wp-block-group navigation-overlay-bar">
		<!-- wp:navigation-overlay-close /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:search {"label":"<?php esc_attr_e( 'Search', 'autonomie' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search…', 'autonomie' ); ?>","buttonText":"<?php esc_attr_e( 'Search', 'autonomie' ); ?>","buttonPosition":"button-inside","buttonUseIcon":true,"className":"site-header-search navigation-overlay-search"} /-->

	<!-- wp:page-list {"fontSize":"x-large","className":"navigation-overlay-menu"} /-->
</div>
<!-- /wp:group -->
