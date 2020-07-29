<?php
/**
 * Adds "custom-color" support
 *
 * @since 1.3.0
 */
function autonomie_customize_register( $wp_customize ) {

	$wp_customize->add_section(
		'autonomie_settings_section',
		array(
			'title' => __( 'Advanced Settings', 'autonomie' ),
			'description' => __( 'Enable/Discable some advanced Autonomie features.', 'autonomie' ), //Descriptive tooltip
			'priority' => 35,
		)
	);

	$wp_customize->add_setting(
		'autonomie_feed_templates',
		array(
			'default' => '0',
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_key',
		)
	);

	$wp_customize->add_control(
		'autonomie_feed_templates',
		array(
			'label' => __( 'Feed Templates', 'autonomie' ),
			'description' => __( 'Enable/Discable XSL Templates for Feeds, similar to the old Feedburner Templates.', 'autonomie' ),
			'section' => 'autonomie_settings_section',
			'settings' => 'autonomie_feed_templates',
			'type' => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'autonomie_customize_register' );
