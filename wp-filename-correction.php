<?php
/**
 * Plugin Name: WordPress Filename Correction
 * Plugin URI: https://buddydev.com/plugins/wp-filename-correction
 * Description: This is an plugin that enable to correct filenames on the site.
 * Version: 1.0.0
 * Author: BuddyDev Team
 * Author URI: https://buddydev.com/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wp-filename-correction
 * Domain Path:  /languages
 *
 * @package wp-filename-correction
 * @contributor: Ravi Sharma
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WP_Filename_Correction
 */
class WP_Filename_Correction {

	/**
	 * Class instance
	 *
	 * @var WP_Filename_Correction
	 */
	private static $instance = null;

	/**
	 * Plugin absolute path
	 *
	 * @var string
	 */
	private $path = '';

	/**
	 * WP_Filename_Correction constructor.
	 */
	private function __construct() {
		$this->path = plugin_dir_path( __FILE__ );
		$this->setup();
	}

	/**
	 * Return class instance
	 *
	 * @return WP_Filename_Correction
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Setup callbacks
	 */
	private function setup() {
		add_action( 'plugins_loaded', array( $this, 'plugin_init' ) );
	}

	/**
	 * Initialize plugin functionality
	 */
	public function plugin_init() {
		$files = array(
			'core/wpfnc-functions.php',
			'core/class-wpfnc-action-handler.php',
		);

		foreach ( $files as $file ) {
			require_once $this->path . $file;
		}

		WPFNC_Action_Handler::boot();
	}

	public function get_path() {
		return $this->path;
	}
}

WP_Filename_Correction::get_instance();
