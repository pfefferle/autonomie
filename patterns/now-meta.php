<?php
/**
 * Title: /now Meta
 * Slug: autonomie/now-meta
 * Inserter: no
 * Description: Author and last updated date for the "/now" page.
 *
 * @package Autonomie
 */

?>
<!-- wp:group {"className":"entry-meta","style":{"typography":{"fontSize":"var:custom|meta|fontSize","lineHeight":"var:custom|meta|lineHeight"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"},"spacing":{"blockGap":"0","margin":{"top":"var:custom|meta|marginBlock","bottom":"var:custom|meta|marginBlock"}}} -->
<div class="wp-block-group entry-meta" style="font-size:var(--wp--custom--meta--font-size);line-height:var(--wp--custom--meta--line-height)">
	<!-- wp:post-author {"showAvatar":true,"avatarSize":32,"showBio":false,"byline":"by","isLink":true} /-->

	<!-- wp:group {"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'Last updated:', 'autonomie' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:post-date {"displayType":"modified","isLink":true} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
