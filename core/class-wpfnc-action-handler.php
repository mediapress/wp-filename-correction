<?php
/**
 * Class handler various action
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
		add_filter( 'wp_handle_upload_prefilter', array( 'WPFNC_Action_Handler', 'filter_filename' ) );

		// Add support to MediaPress
		add_filter( 'mpp_use_processed_file_name_as_media_title', array( $this, 'filter_media_title' ) );
		add_filter( 'mpp_upload_prefilter', array( 'WPFNC_Action_Handler', 'filter_filename' ) );
	}

	/**
	 * Filter filename form file meta info
	 *
	 * @param array $meta File meta info.
	 *
	 * @return array
	 */
	public static function filter_filename( $meta ) {
		$self = new self();

		$ext          = pathinfo( $meta['name'], PATHINFO_EXTENSION );
		$meta['name'] = str_replace( '.' . $ext, '', $meta['name'] );

		if ( wpfnc_get_option( 'encode_filename' ) ) {
			$meta['name'] = md5( $meta['name'] );
		} else {
			$meta['name'] = $self->apply_rule( $meta['name'] );
			$meta['name'] = $self->clean_cases( $meta['name'] );
			$meta['name'] = sanitize_file_name( $meta['name'] );
		}

		$meta['name'] = $meta['name'] . '.' . $ext;

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

		$file_name = wpfnc_get_parsed_value( $rule, $file_name );

		return $file_name;
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
		$file_name     = remove_accents( $file_name );

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