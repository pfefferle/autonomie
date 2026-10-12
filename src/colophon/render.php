<?php
/**
 * Colophon Block - Server-side Render
 *
 * Lists how the site is made, from core data only. Plugins are listed without
 * versions, and PHP and server versions are left out on purpose.
 *
 * @package Autonomie
 *
 * @var array $attributes Block attributes.
 */

if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$colophon_theme = wp_get_theme( get_template() );
// WP_Theme does not read the license headers.
$colophon_license = get_file_data(
	get_template_directory() . '/style.css',
	array(
		'name' => 'License',
		'url'  => 'License URI',
	)
);
$colophon_plugins = array();
$colophon_active  = (array) get_option( 'active_plugins', array() );

if ( is_multisite() ) {
	$colophon_active = array_merge( $colophon_active, array_keys( (array) get_site_option( 'active_sitewide_plugins', array() ) ) );
}

foreach ( get_plugins() as $colophon_file => $colophon_data ) {
	if ( in_array( $colophon_file, $colophon_active, true ) && ! empty( $colophon_data['Name'] ) ) {
		$colophon_plugins[ $colophon_data['Name'] ] = $colophon_data['PluginURI'];
	}
}

ksort( $colophon_plugins, SORT_NATURAL | SORT_FLAG_CASE );

$colophon_fonts = array();
foreach ( (array) wp_get_global_settings( array( 'typography', 'fontFamilies', 'theme' ) ) as $colophon_font ) {
	// Only fonts that ship with the theme, not system font stacks.
	if ( ! empty( $colophon_font['fontFace'] ) ) {
		$colophon_fonts[] = $colophon_font['name'];
	}
}

$colophon_link = function ( $url, $text ) {
	return $url ? sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $text ) ) : esc_html( $text );
};
?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<h2 class="wp-block-heading"><?php esc_html_e( 'Software', 'autonomie' ); ?></h2>
	<p>
		<?php
		printf(
			/* translators: 1: Linked "WordPress", 2: Linked theme name, 3: Linked theme author, 4: Linked theme license. */
			esc_html__( 'This site runs on %1$s with the %2$s theme by %3$s, licensed under %4$s.', 'autonomie' ),
			$colophon_link( 'https://wordpress.org/', 'WordPress' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$colophon_link( $colophon_theme->get( 'ThemeURI' ), $colophon_theme->get( 'Name' ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$colophon_link( $colophon_theme->get( 'AuthorURI' ), $colophon_theme->get( 'Author' ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$colophon_link( $colophon_license['url'], $colophon_license['name'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
		?>
	</p>

	<?php if ( $colophon_plugins ) : ?>
		<h2 class="wp-block-heading"><?php esc_html_e( 'Plugins', 'autonomie' ); ?></h2>
		<ul>
			<?php foreach ( $colophon_plugins as $colophon_name => $colophon_url ) : ?>
				<li><?php echo $colophon_link( $colophon_url, $colophon_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ( $colophon_fonts ) : ?>
		<h2 class="wp-block-heading"><?php esc_html_e( 'Typography', 'autonomie' ); ?></h2>
		<p>
			<?php
			printf(
				/* translators: %s: List of font names. */
				esc_html__( 'Set in %s, served from this site.', 'autonomie' ),
				esc_html( wp_sprintf( '%l', $colophon_fonts ) )
			);
			?>
		</p>
	<?php endif; ?>

	<h2 class="wp-block-heading"><?php esc_html_e( 'Standards', 'autonomie' ); ?></h2>
	<ul>
		<li><?php echo $colophon_link( 'https://microformats.org/wiki/microformats2', 'microformats2' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
		<li><?php echo $colophon_link( 'https://schema.org/', 'Schema.org' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
		<li><?php echo $colophon_link( 'https://indieweb.org/', 'IndieWeb' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
	</ul>

	<h2 class="wp-block-heading"><?php esc_html_e( 'Feeds', 'autonomie' ); ?></h2>
	<ul>
		<li><?php echo $colophon_link( get_feed_link(), __( 'Posts', 'autonomie' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
		<li><?php echo $colophon_link( get_feed_link( 'comments_' . get_default_feed() ), __( 'Comments', 'autonomie' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
	</ul>
</div>
