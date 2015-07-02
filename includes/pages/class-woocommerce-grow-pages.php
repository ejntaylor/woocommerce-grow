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
abstract class WooCommerce_Grow_Pages {

	protected $id;
	protected $title;

	public function __construct() {
		add_filter( 'woocommerce_grow_page_tabs_array', array( $this, 'add_page' ), 10 );
		add_action( 'woocommerce_grow_save_page_settings_' . $this->id, array( $this, 'save_page_settings' ) );
		add_action( 'woocommerce_grow_page_content_' . $this->id, array( $this, 'page_content' ) );
	}

	public function add_page( $pages ) {
		$pages[ $this->id ] = $this->title;

		return $pages;
	}

	public function page_content() {
		echo '<p>'. __( 'Generic page content', 'woocommerce-grow' ) .'</p>';
	}

	public function save_page_settings() {}

	/**
	 * Verify Request origins
	 *
	 * @param $wpnonce
	 * @param $action
	 */
	public function verify_request( $wpnonce, $action ) {
		if ( ! wp_verify_nonce( $wpnonce, $action ) ) {
			die( __( 'Action failed. Please refresh the page and retry.', 'woocommerce-grow' ) );
		}
	}

}