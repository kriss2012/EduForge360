<?php
/**
 * EduForge Customizer Controls
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function eduforge_customize_register( $wp_customize ) {
	// EduForge Brand Section
	$wp_customize->add_section( 'eduforge_branding_section', array(
		'title'    => __( 'EduForge360 Platform Branding', 'eduforge360' ),
		'priority' => 30,
	) );

	// Platform Slogan Setting
	$wp_customize->add_setting( 'eduforge_slogan', array(
		'default'           => 'Build Skills. Track Growth. Shape Careers.',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'eduforge_slogan', array(
		'label'   => __( 'Hero Slogan', 'eduforge360' ),
		'section' => 'eduforge_branding_section',
		'type'    => 'text',
	) );

	// Contact Phone Setting
	$wp_customize->add_setting( 'eduforge_phone', array(
		'default'           => '+91 (80) 4123-4567',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'eduforge_phone', array(
		'label'   => __( 'Support Contact Phone', 'eduforge360' ),
		'section' => 'eduforge_branding_section',
		'type'    => 'text',
	) );
}
add_action( 'customize_register', 'eduforge_customize_register' );
