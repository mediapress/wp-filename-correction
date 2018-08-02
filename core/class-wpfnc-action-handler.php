<?php
/**
 * Class handle various action
 *
 * @package wp-filname-correction
 */

// Exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPFNC_Action_Handler
 */
class WPFNC_Action_Handler {

	/**
	 * Boot class
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Setup callbacks for various actions
	 */
	private function setup() {
		$self = new self();
		add_filter( 'wp_handle_upload_prefilter', array( $self, 'filter_filename' ) );

		// Add support to MediaPress
		add_filter( 'mpp_use_processed_file_name_as_media_title', array( $self, 'filter_media_title' ) );
		add_filter( 'mpp_upload_prefilter', array( $self, 'filter_filename' ) );
	}

	/**
	 * Filter filename form file meta info
	 *
	 * @param array $meta File meta info.
	 *
	 * @return
	 */
	public static function filter_filename( $meta ) {
		$self = new self();

		$meta['name'] = $self->apply_rule( $meta['name'] );
		$meta['name'] = $self->clean_cases( $meta['name'] );
		$meta['name'] = sanitize_file_name( $meta['name'] );

		return $meta;
	}

	/**
	 * Apply rules on filename
	 *
	 * @param string $file_name File name.
	 *
	 * @return string
	 */
	public function apply_rule( $file_name ) {

		$rule = wpfnc_get_option( 'rule' );

		if ( empty( $rule ) ) {
			return $file_name;
		}

		$rule_sep = wpfnc_get_option( 'rule_separator' );
		$rule     = wpfnc_get_parsed_value( $rule, $file_name );

		$prefix = join( $rule_sep, $rule );

		return $prefix . $rule_sep .$file_name;
	}

	/**
	 * Clean cases
	 *
	 * @param string $file_name File name.
	 *
	 * @return string
	 */
	private function clean_cases( $file_name ) {

		$selected_case = wpfnc_get_option( 'selected_case' );

		if ( 'lower' == $selected_case ) {
			$file_name = strtolower( $file_name );
		} elseif ( 'upper' == $selected_case ) {
			$file_name = strtoupper( $file_name );
		}

		return $file_name;
	}

	/**
	 * Check weather to filter media title
	 *
	 * @return bool
	 */
	public function filter_media_title() {
		return true;
	}
}