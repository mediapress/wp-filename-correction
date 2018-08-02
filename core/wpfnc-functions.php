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
	$settings = get_option( 'spfnc_settings' );

	$settings = wp_parse_args( $settings, array(
		'rule' => '{domain_name}{user_name}',
		'rule_separator' => '-',
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
		'post_title'  => __( 'Post title', 'wp-filename-correction' ),
		'user_name'   => __( 'User name', 'wp-filename-correction' ),
		'domain_name' => __( 'Domain', 'wp-filename-correction' ),
		'file_name'   => __( 'File name', 'wp-filename-correction' ),
	);

	return $tags;
}

/**
 * Return parsed rule
 *
 * @param string $rule Rule.
 * @param string $file_name File name.
 *
 * @return string
 */
function wpfnc_get_parsed_rule( $rule, $file_name ) {

	$parsed_rule = array();

	if ( strpos( $rule, '{site_title}' ) !== false ) {
		$parsed_rule[] = str_replace( '{site_title}', get_bloginfo( 'name' ), $rule );
	}

	// Not sure here
	if ( strpos( $rule, '{post_title}' ) !== false ) {
		$parsed_rule[] = str_replace( '{post_title}', get_the_title( get_queried_object_id() ), $rule );
	}

	if ( strpos( $rule, '{user_name}' ) !== false ) {
		$current_user = wp_get_current_user();
		$parsed_rule[] = str_replace( '{user_name}', $current_user->display_name, $rule );
	}

	if ( strpos( $rule, '{domain_name}' ) !== false ) {
		$parsed_rule[] = str_replace( '{domain_name}', $_SERVER['server_name'], $rule );
	}

	if ( strpos( $rule, '{file_name}' ) !== false ) {
		$parsed_rule[] = str_replace( '{file_name}', $file_name, $rule );
	}

	return $parsed_rule;
}