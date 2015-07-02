<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Description
 *
 * @since  1.0
 * @author VanboDevelops | Ivan Andreev
 *
 *        Copyright: (c) 2015 VanboDevelops
 *        License: GNU General Public License v3.0
 *        License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
class WooCommerce_Grow_Menu {
	/**
	 * Load hooks to add plugin pages
	 */
	public function hooks() {
		// Add menus
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
		add_action( 'woocommerce_screen_ids', array( $this, 'screen_ids' ) );
	}

	/**
	 * Add menu items
	 */
	public function admin_menu() {
		$page  = add_submenu_page(
			'woocommerce',
			__( 'WooCommerce Grow', 'woocommerce-grow' ),
			__( 'Grow', 'woocommerce-grow' ) ,
			'manage_woocommerce',
			'woocommerce-grow',
			array( $this, 'pages' )
		);

		add_action( 'admin_print_styles-'. $page, array( $this, 'scripts' ) );
	}

	/**
	 * Add Grow pages scripts.
	 */
	public function scripts() {
		// Load scripts for the Grow Pages
		$scripts = apply_filters( 'woocommerce_grow_scripts_to_enqueue', array() );

		if ( empty( $scripts ) ) {
			return;
		}

		foreach( $scripts as $script ) {
			wp_enqueue_script( $script );
		}
	}

	/**
	 * Add screen ID to the WooCommerce screen IDs.
	 * This will allow to add all available scripts to the Grow pages, too.
	 */
	public function screen_ids( $ids ) {
		$screen_id = strtolower( _x( 'WooCommerce', 'Pages screen id', 'woocommerce-grow' ) );
		$ids[]     = $screen_id . '_page_woocommerce-grow';

		return $ids;
	}

	/**
	 * Manage activations
	 */
	public function pages() {
		// Anything you need to do before a page is loaded
		do_action( 'woocommerce_grow_pages_start' );

		// Get current tab
		$current_tab = empty( $_GET['tab'] ) ? 'dashboard' : sanitize_title( $_GET['tab'] );

		$this->add_woocommerce_grow_pages();

		// Save settings if data has been posted
		if ( ! empty( $_POST ) ) {
			$this->save( $current_tab );
		}

		// Add any posted messages
		if ( ! empty( $_GET['wc_grow_error'] ) ) {
			WC_Admin_Settings::add_error( stripslashes( $_GET['wc_error'] ) );
		}

		if ( ! empty( $_GET['wc_grow_message'] ) ) {
			WC_Admin_Settings::add_message( stripslashes( $_GET['wc_message'] ) );
		}

		WC_Admin_Settings::show_messages();

		// Add tabs on the Grow page
		$tabs = apply_filters( 'woocommerce_grow_page_tabs_array', array() );

		include 'views/html-grow-page.php';
	}

	public function save( $current_tab ) {
		// Allow for the settings to be saved against a particular tab
		do_action( 'woocommerce_grow_save_page_settings_'. $current_tab );
	}

	/**
	 *
	 */
	private function add_woocommerce_grow_pages() {
		// Add all settings page Classes
		$settings_pages   = array();
		$settings_pages[] = 'WooCommerce_Grow_Page_Dashboard';
		$settings_pages[] = 'WooCommerce_Grow_Page_Targets';

		$settings_pages = apply_filters( 'woocommerce_grow_settings_page_classes_array', $settings_pages );

		foreach ( $settings_pages as $page ) {
			if ( class_exists( $page ) ) {
				new $page();
			}
		}
	}
}