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
			'description' => __( 'Enable/Disable some advanced Autonomie features.', 'autonomie' ), //Descriptive tooltip
			'priority' => 35,
		)
	);
}
// add_action( 'customize_register', 'autonomie_customize_register' );
