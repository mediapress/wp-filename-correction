<?php
/**
 * Admin Settings Pages Helper.
 *
 * @package wp-filename-correction
 */

use \Press_Themes\PT_Settings\Page;

// Exit if file accessed directly over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Settings
 */
class Admin_Settings {

	/**
	 * Admin Menu slug
	 *
	 * @var string
	 */
	private $menu_slug;

	/**
	 * Used to keep a reference of the Page, It will be used in rendering the view.
	 *
	 * @var \Press_Themes\PT_Settings\Page
	 */
	private $page;

	/**
	 * Boot settings
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Setup settings
	 */
	public function setup() {

		$this->menu_slug = 'wp-filename-settings';

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	/**
	 * Show/render the setting page
	 */
	public function render() {
		$this->page->render();
	}

	/**
	 * Is it the setting page?
	 *
	 * @return bool
	 */
	private function needs_loading() {

		global $pagenow;

		// We need to load on options.php otherwise settings won't be reistered.
		if ( 'options.php' === $pagenow ) {
			return true;
		}

		if ( isset( $_GET['page'] ) && $_GET['page'] === $this->menu_slug ) {
			return true;
		}

		return false;
	}

	/**
	 * Initialize the admin settings panel and fields
	 */
	public function init() {

		if ( ! $this->needs_loading() ) {
			return;
		}

		$page = new Page( 'wpfnc_settings', __( 'WP Filename correction', 'wp-filename-correction' ) );

		// General settings tab.
		$general = $page->add_panel( 'general', _x( 'General', 'Admin settings panel title', 'wp-filename-correction' ) );

		$section_general = $general->add_section( 'settings', _x( 'General Settings', 'Admin settings section title', 'wp-filename-correction' ) );

		// add the follow option in future.

		$fields = array(
			array(
				'name'    => 'rule',
				'label'   => _x( 'Rule for filename renaming', 'Admin settings', 'wp-filename-correction' ),
				'type'    => 'text',
				'desc'    => __( '{site_title}, {user_name}, {domain_name} and {file_name} use these tags to create file rename rule.', 'wp-filename-correction' ),
				'default' => '{domain_name}{user_name}{file_name}',
			),
			array(
				'name'    => 'selected_case',
				'label'   => _x( 'Select case', 'Admin settings', 'wp-filename-correction' ),
				'type'    => 'select',
				'options' => array(
					'lower' => __( 'Lower case', 'wp-filename-correction' ),
					'upper' => __( 'Upper case', 'wp-filename-correction' ),
				),
				'default' => 'lower',
			),
		);

		$section_general->add_fields( $fields );

		$this->page = $page;

		// allow enabling options.
		$page->init();
	}

	/**
	 * Add Menu
	 */
	public function add_menu() {

		add_options_page(
			_x( 'WP Filename Correction', 'Admin settings page title', 'wp-filename-correction' ),
			_x( 'WP Filename Correction', 'Admin settings menu label', 'wp-filename-correction' ),
			'manage_options',
			$this->menu_slug,
			array( $this, 'render' )
		);
	}
}
