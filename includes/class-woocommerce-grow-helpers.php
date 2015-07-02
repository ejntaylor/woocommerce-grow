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
class WooCommerce_Grow_Helpers {

	/**
	 * WC Logger object
	 * @var object
	 */
	private static $log = null;

	/**
	 * Do we have debug mode enabled
	 * @var bool
	 */
	private static $is_debug_enabled = null;

	/**
	 * Returns an array of the next 12 months in the format [M, Y] Month, Year
	 *
	 * @return array
	 */
	public static function get_twelve_months_ahead() {
		$current_month = date( 'n' );
		for ( $x = 0; $x < 12; $x ++ ) {
			$months[] = array(
				'month' => date( 'm', mktime( 0, 0, 0, $current_month + $x, 1 ) ),
				'year'  => date( 'Y', mktime( 0, 0, 0, $current_month + $x, 1 ) )
			);
		}

		return $months;
	}

	/**
	 * Return the value of a given array key
	 *
	 * @since 1.0
	 *
	 * @param string $name
	 * @param array  $array
	 *
	 * @return null
	 */
	public static function get_field( $name, array $array ) {
		return isset( $array[ $name ] ) ? $array[ $name ] : null;
	}

	/**
	 * Add debug log message
	 *
	 * @since 1.0
	 *
	 * @param string $message
	 */
	public static function add_debug_log( $message ) {
		if ( ! is_object( self::$log ) ) {
			self::$log = WooCommerce_Grow_Compat::get_wc_logger();
		}

		if ( self::is_debug_enabled() ) {
			self::$log->add( 'wc_grow', $message );
		}
	}

	/**
	 * Check, if debug logging is enabled
	 *
	 * @since 1.0
	 * @return bool
	 */
	public static function is_debug_enabled() {
		if ( null === self::$is_debug_enabled ) {
			$condition              = true;
			self::$is_debug_enabled = $condition;
		}

		return self::$is_debug_enabled;
	}

	public static function update_option( $name, $value, $autoload = null ) {
		return update_option( WooCommerce_Grow::PREFIX . $name, $value, $autoload );
	}

	public static function get_option( $name, $default = false ) {
		return get_option( WooCommerce_Grow::PREFIX . $name, $default );
	}

	public static function get_plugin_settings_page() {
		return admin_url( 'admin.php?page=wc-settings&tab=integration&section=woocommerce_grow' );
	}

	public static function get_wc_total_sales( WC_Report_Sales_By_Date $reports ) {
		$report_data = $reports->get_report_data();

		return wc_format_decimal( array_sum( wp_list_pluck( $report_data->orders, 'total_sales' ) ), 2 );
	}

	public static function get_wc_total_orders( WC_Report_Sales_By_Date $reports ) {
		$report_data = $reports->get_report_data();

		return absint( array_sum( wp_list_pluck( $report_data->order_counts, 'count' ) ) );;
	}

	public static function calculate_cr( $orders, $sessions ) {
		$orders   = 0 == $orders ? 1 : $orders;
		$sessions = 0 == $sessions ? 1 : $sessions;

		return wc_format_decimal( $orders / $sessions, 2 );
	}

	public static function calculate_aov( $revenue, $orders ) {
		$orders  = 0 == $orders ? 1 : $orders;
		$revenue = 0.00 == $revenue ? 1 : $revenue;

		return wc_format_decimal( $revenue / $orders, 2 );
	}

	/**
	 * Setup and return the WC_Report_Sales_By_Date object for a set period of time
	 *
	 * @param $filter
	 *
	 * @return WC_Report_Sales_By_Date
	 */
	public static function setup_wc_reports( $filter ) {
		include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );
		include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-report-sales-by-date.php' );

		$report = new WC_Report_Sales_By_Date();

		$default_args = array(
			'period'   => '',
			'date_min' => date( 'Y-m-d', strtotime( '-1 week' ) ),
			'date_max' => date( 'Y-m-d', time() )
		);

		$filter = wp_parse_args( $filter, $default_args );

		if ( empty( $filter['period'] ) ) {

			// custom date range
			$filter['period'] = 'custom';

			if ( ! empty( $filter['date_min'] ) || ! empty( $filter['date_max'] ) ) {
				// overwrite _GET to make use of WC_Admin_Report::calculate_current_range() for custom date ranges
				$_GET['start_date'] = isset( $filter['date_min'] ) ? $filter['date_min'] : null;
				$_GET['end_date']   = isset( $filter['date_max'] ) ? $filter['date_max'] : null;

			} else {
				// default custom range to today
				$_GET['start_date'] = $_GET['end_date'] = date( 'Y-m-d', current_time( 'timestamp' ) );
			}

		} else {

			// Ensure period is valid
			if ( ! in_array( $filter['period'], array( 'week', 'month', 'last_month', 'year' ) ) ) {
				$filter['period'] = 'week';
			}

			// TODO: change WC_Admin_Report class to use "week" instead, as it's more consistent with other periods
			// allow "week" for period instead of "7day"
			if ( 'week' === $filter['period'] ) {
				$filter['period'] = '7day';
			}
		}

		$report->calculate_current_range( $filter['period'] );

		return $report;
	}

	/**
	 * Returns the first and last date of the month
	 *
	 * @since 1.0
	 *
	 * @param string $month
	 * @param string $year
	 *
	 * @return array
	 */
	public static function get_first_and_last_of_the_month( $month, $year ) {
		$start_date = date( 'Y-m-01', mktime( 0, 0, 0, $month, 1, $year ) );
		$end_date   = date( 'Y-m-t', mktime( 0, 0, 0, $month, 1, $year ) );

		return array( $start_date, $end_date );
	}

	/**
	 * Calculates the increase by $rate percentage
	 *
	 * @param float $base The base amount that we are going to increase
	 * @param float $rate Percentage number to increase the base by. Should be given as the real percentage 5 for 5%, 10 for 10% increase.
	 *
	 * @return mixed
	 */
	public static function calculate_growth( $base, $rate ) {
		return wc_format_decimal( $base + ( $base * ( $rate / 100 ) ), 2 );
	}

}