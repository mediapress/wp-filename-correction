<?php
/**
 * Plugin functions
 *
 * @package wp-filename-correction
 */

// exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get saved settings
 *
 * @param string $option_key Option key.
 *
 * @return string|bool
 */
function wpfnc_get_option( $option_key = '' ) {
	$settings = get_option( 'wpfnc_settings' );

	$settings = wp_parse_args( $settings, array(
		'rule' => '{domain_name}{site_title}{user_name}',
		'selected_case' => 'lower'
	) );

	if ( isset( $settings[ $option_key ] ) ) {
		return $settings[ $option_key ];
	}

	return false;
}

/**
 * Get rule tags
 *
 * @return array
 */
function wpfnc_get_rule_tags() {
	$tags = array(
		'site_title'  => __( 'Site title', 'wp-filename-correction' ),
		'user_name'   => __( 'User name', 'wp-filename-correction' ),
		'domain_name' => __( 'Domain', 'wp-filename-correction' ),
		'file_name'   => __( 'File name', 'wp-filename-correction' ),
	);

	return $tags;
}

/**
 * Return parsed value
 *
 * @param string $rule Rule
 * @param string $file_name File name.
 *
 * @return mixed
 */
function wpfnc_get_parsed_value( $rule, $file_name ) {

	$server_name = $_SERVER['SERVER_NAME'];

	$parsed_value = str_replace( array(
		'{site_title}',
		'{user_name}',
		'{domain_name}',
		'{file_name}'
	), array(
		get_bloginfo( 'name' ),
		wp_get_current_user()->display_name,
		$server_name,
		$file_name
	), $rule );

	return $parsed_value;
}