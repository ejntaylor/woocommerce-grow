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
class WooCommerce_Grow_Page_Targets extends WooCommerce_Grow_Pages {
	public function __construct() {
		$this->id    = 'targets';
		$this->title = 'Targets';

		add_filter( 'woocommerce_grow_page_tabs_array', array( $this, 'add_page' ), 10 );
		add_action( 'woocommerce_grow_page_content_' . $this->id, array( $this, 'page_content' ) );
		add_action( 'woocommerce_grow_save_page_settings_' . $this->id, array( $this, 'save' ) );
	}

	public function page_content() {
		include 'views/html-page-targets.php';
	}

	/**
	 * Save the Targets settings
	 */
	public function save() {
		try {
			$this->verify_request( WooCommerce_Grow_Helpers::get_field( '_wpnonce', $_REQUEST ), 'woocommerce-grow-targets' );

			$is_calculate_growth = null !== WooCommerce_Grow_Helpers::get_field( 'calculate_growth', $_POST ) ? true : false;

			// Calculate and Growth settings
			if ( $is_calculate_growth ) {
				$initial_revenue_number  = WooCommerce_Grow_Helpers::get_field( 'initial_revenue_number', $_POST );
				$initial_orders_number   = WooCommerce_Grow_Helpers::get_field( 'initial_orders_number', $_POST );
				$initial_sessions_number = WooCommerce_Grow_Helpers::get_field( 'initial_sessions_number', $_POST );
				$initial_cr_number       = WooCommerce_Grow_Helpers::get_field( 'initial_cr_number', $_POST );
				$initial_aov_number      = WooCommerce_Grow_Helpers::get_field( 'initial_aov_number', $_POST );

				// Call to GA to get sessions for the last month
				$ga    = WooCommerce_Grow_Google_Analytics::get_instance();
				$month = date( 'm', strtotime( 'first day of previous month' ) );
				$year  = date( 'Y' );
				list( $start_date, $end_date ) = WooCommerce_Grow_Helpers::get_first_and_last_of_the_month( $month, $year );
				$initial_sessions = $ga->get_sessions_for_month( $month, $year );

				$filters = array(
					'date_min' => $start_date,
					'date_max' => $end_date
				);

				$reports = WooCommerce_Grow_Helpers::setup_wc_reports( $filters );

				$initial_revenue = WooCommerce_Grow_Helpers::get_wc_total_sales( $reports );
				$initial_orders  = WooCommerce_Grow_Helpers::get_wc_total_orders( $reports );

				WooCommerce_Grow_Helpers::add_debug_log( 'Revenue: '. $initial_revenue );
				WooCommerce_Grow_Helpers::add_debug_log( 'Orders: '. $initial_orders );

				$initial_cr      = WooCommerce_Grow_Helpers::calculate_cr( $initial_orders, $initial_sessions );
				$initial_aov     = WooCommerce_Grow_Helpers::calculate_aov( $initial_revenue, $initial_orders );
				$growth_rate     = WooCommerce_Grow_Helpers::get_field( 'growth_rate', $_POST );

				// Save the initial options
				WooCommerce_Grow_Helpers::update_option( 'initial_revenue_number', $initial_revenue );
				WooCommerce_Grow_Helpers::update_option( 'initial_orders_number', $initial_orders );
				WooCommerce_Grow_Helpers::update_option( 'initial_sessions_number', $initial_sessions );
				WooCommerce_Grow_Helpers::update_option( 'initial_cr_number', $initial_cr );
				WooCommerce_Grow_Helpers::update_option( 'initial_aov_number', $initial_aov );
				WooCommerce_Grow_Helpers::update_option( 'growth_rate', $growth_rate );

				$months = WooCommerce_Grow_Helpers::get_twelve_months_ahead();

				foreach ( $months as $month ) {
					$target_sessions = WooCommerce_Grow_Helpers::calculate_growth( $initial_sessions, $growth_rate );
					$target_cr       = WooCommerce_Grow_Helpers::calculate_growth( $initial_cr, $growth_rate );
					$target_aov      = WooCommerce_Grow_Helpers::calculate_growth( $initial_aov, $growth_rate );

					$targets['sessions_percentage'][ $month['year'] ][ $month['month'] ] = ceil( $target_sessions );
					$targets['cr_percentage'][ $month['year'] ][ $month['month'] ]       = $target_cr;
					$targets['aov_percentage'][ $month['year'] ][ $month['month'] ]      = $target_aov;
					$targets['revenue_percentage'][ $month['year'] ][ $month['month'] ]  = wc_format_decimal( $target_sessions * $target_cr * $target_aov, 2 );
					$targets['orders_percentage'][ $month['year'] ][ $month['month'] ]   = ceil( $target_sessions * $target_cr );
				}

				WooCommerce_Grow_Helpers::update_option( 'monthly_targets', $targets );
			}
		}
		catch ( Exception $e ) {
			WC_Admin_Settings::add_error( $e->getMessage() );
		}

	}
}