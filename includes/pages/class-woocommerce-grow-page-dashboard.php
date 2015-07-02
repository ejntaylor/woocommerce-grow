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
class WooCommerce_Grow_Page_Dashboard extends WooCommerce_Grow_Pages {
	public function __construct() {
		$this->id = 'dashboard';
		$this->title = 'Dashboard';

		add_filter( 'woocommerce_grow_page_tabs_array', array( $this, 'add_page' ), 10 );
		add_action( 'woocommerce_grow_page_content_' . $this->id, array( $this, 'page_content' ) );
	}

	public function page_content() {
		include 'views/html-page-dashboard.php';
	}
}