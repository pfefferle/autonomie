<?php
/**
 * Title: Blank
 * Slug: autonomie/blank
 * Inserter: no
 * Description: A random, slightly nerdy "blank" for empty pages using the Blank template.
 *
 * @package Autonomie
 */

$autonomie_blank = wp_rand( 1, 3 );

?>
<?php if ( 1 === $autonomie_blank ) : ?>
<!-- wp:code -->
<pre class="wp-block-code"><code>add_filter( 'the_content', '__return_empty_string' );</code></pre>
<!-- /wp:code -->
<?php elseif ( 2 === $autonomie_blank ) : ?>
<!-- wp:code -->
<pre class="wp-block-code"><code>about:blank</code></pre>
<!-- /wp:code -->
<?php else : ?>
<!-- wp:paragraph -->
<p>
<?php
printf(
	// translators: %s: the link to this page.
	esc_html__( '%s is intentionally left _blank.', 'autonomie' ),
	sprintf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( get_permalink() ), esc_html__( 'This page', 'autonomie' ) )
);
?>
</p>
<!-- /wp:paragraph -->
<?php endif; ?>
