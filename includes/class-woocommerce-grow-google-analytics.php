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
class WooCommerce_Grow_Google_Analytics {

	/**
	 * @var null|Google_Service_Analytics
	 */
	private $analytics;

	/**
	 * @var null|Google_Client
	 */
	private $client = null;

	/**
	 * @var null|WooCommerce_Grow_Google_Analytics
	 */
	private static $instance = null;

	public function __construct() {

		if ( is_null( self::$instance ) ) {
			self::$instance = $this;
		}

		add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'catch_ga_authenitication_code' ) );

		$this->load_google_analytics();
	}

	/**
	 * Instantiate the class
	 *
	 * @return WooCommerce_Grow_Google_Analytics
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function load_google_analytics() {
		WooCommerce_Grow::load_google_analytics();

		// We setup the Google Client object
		$this->client = new Google_Client();
		$this->client->setAuthConfigFile( WooCommerce_Grow::get_plugin_path() . '\ga-config.json' );
		$this->client->addScope( Google_Service_Analytics::ANALYTICS_READONLY );

		$this->analytics = new Google_Service_Analytics( $this->client );
	}

	public function catch_ga_authenitication_code() {

	}

	/**
	 * Returns the Google authentication url.
	 * The users are sent to this URL to allow access to their Google account.
	 *
	 * @return string
	 */
	public function get_authentication_url() {
		return $this->client->createAuthUrl();
	}

	/**
	 * Returns Google Sessions for the given month.
	 *
	 * @param $month
	 * @param $year
	 *
	 * @return array|mixed
	 * @throws Exception
	 */
	public function get_sessions_for_month( $month, $year ) {
		if ( $this->is_app_authenticated() ) {
			$rows = array();

			list( $start_date, $end_date ) = WooCommerce_Grow_Helpers::get_first_and_last_of_the_month( $month, $year );
			$id   = $this->get_single_ga_profile();
			$data = $this->get_metrics( $id, 'ga:sessions', $start_date, $end_date );
			$rows = $data->getRows();

			WooCommerce_Grow_Helpers::add_debug_log( 'Month Rows: ' . print_r( $rows, true ) );

			$rows = array_shift( $rows );
			$rows = array_shift( $rows );

			return $rows;
		}
	}

	public function get_sessions_for_time_range( $start_month, $start_year, $end_month, $end_year ) {
		// Get the sessions for a range of time
	}

	/**
	 * Check, if the analytics account is authenticated and has an access token.
	 * Authenticate it, if it is not.
	 *
	 * TODO: have the access token in a transient and check if the token expired before continuing
	 *
	 * @return bool
	 */
	function is_app_authenticated() {
		$access_token = WooCommerce_Grow_Helpers::get_option( 'access_token' );

		// We have an access token already
		if ( ! empty( $access_token ) ) {
			try {
				$this->client->setAccessToken( $access_token );
			}
			catch ( Exception $e ) {
				echo sprintf( __( 'Error: Unable to set Google Analytics access token. Error Code: %s. Error Message: %s  ', 'woocommerce-grow' ), $e->getCode(), $e->getMessage() );

				return false;
			}
		} else {

			$settings  = get_option( 'woocommerce_woocommerce_grow_settings', array() );
			$auth_code = WooCommerce_Grow_Helpers::get_field( 'authorization_token', $settings );

			if ( empty( $auth_code ) ) {
				// TODO: needs behavior
				return false;
			}

			try {
				// The authenticate method gets an access token, sets the access token
				// and returns the access token all at once.
				$access_token = $this->client->authenticate( $auth_code );
				$this->client->setAccessToken( $access_token );
				WooCommerce_Grow_Helpers::update_option( 'access_token', $access_token );
			}
			catch ( Exception $e ) {
				echo sprintf(
					__(
						'Google Analytics was unable to authenticate you.
						Please refresh and try again. If the problem persists, please obtain a new authorizations token.
						Error Code: %s. Error Message: %s  ', 'woocommerce-grow'
					),
					$e->getCode(),
					$e->getMessage()
				);

				return false;
			}
		}

		return true;
	}

	/**
	 * Returns the profile ID from the set Google UID
	 *
	 * TODO: move the use of this method to the UID setting. So we can have the setting value in an easy to get the Profile ID format
	 * TODO: Preferred format: UID:ProfileID
	 *
	 * @since 1.0
	 * @return array
	 * @throws Exception
	 */
	function get_single_ga_profile() {
		$webproperty_id = get_option( 'woocommerce_woocommerce_grow_settings' );
		$ga_uid         = $webproperty_id['ga_api_uid'];

		if ( empty( $ga_uid ) ) {
			// TODO: TEST
			throw new Exception(
				sprintf(
					__( 'The Google Account User ID is not set. Please visit %ssettings page%s to set the Google UID.', 'woocommerce-grow' ),
					'<a href="' . WooCommerce_Grow_Helpers::get_plugin_settings_page() . '" target="_blank">',
					'</a>'
				)
			);
		}

		list( $pre, $account_id, $post ) = explode( '-', $ga_uid );

		try {
			$profiles = $this->analytics->management_profiles->listManagementProfiles( $account_id, $ga_uid );
		}
		catch ( Exception $e ) {
			throw new Exception(
				sprintf(
					__( 'Analytics API error occurred. Error Code: %s. Error Message: %s  ', 'woocommerce-grow' ),
					$e->getCode(),
					$e->getMessage()
				)
			);
		}

		$profile_id = $profiles->items[0]->id;

		// If the profile is empty or it does not match the UID we chose to use.
		if ( empty( $profile_id ) || $ga_uid != $profiles->items[0]->webPropertyId ) {
			throw new Exception(
				sprintf(
					__( 'There is no Google Profile ID found from your Google UID. Please visit %ssettings page%s to set the Google UID.', 'woocommerce-grow' ),
					'<a href="' . WooCommerce_Grow_Helpers::get_plugin_settings_page() . '" target="_blank">',
					'</a>'
				)
			);
		}

		return $profile_id;
	}

	/**
	 * Returns all profiles associated with the Google account
	 *
	 * @return array
	 */
	function get_all_ga_profiles() {
		$profile_array = array();

		try {
			$profiles = $this->analytics->management_webproperties->listManagementWebproperties( '~all' );
		}
		catch ( Exception $e ) {
			echo sprintf( __( 'Analytics API error occurred. Error Code: %s. Error Message: %s  ', 'woocommerce-grow' ), $e->getCode(), $e->getMessage() );
		}

		if ( ! empty( $profiles->items ) ) {
			foreach ( $profiles->items as $profile ) {
				$profile_array[ $profile->id ] = str_replace( 'http://', '', $profile->name );
			}
		}

		return $profile_array;
	}

	/**
	 * Get a specific data metrics
	 *
	 * @since 1.0
	 *
	 * @param        string $account_id - The Account to query against
	 * @param        string $metrics    - The metrics to get
	 * @param        string $start_date - The start date to get
	 * @param        string $end_date   - The end date to get
	 * @param        string $dimensions - The dimensions to grab
	 * @param        string $sort       - The properties to sort on
	 * @param        string $filter     - The property to filter on
	 * @param        string $limit      - The number of items to get
	 *
	 * @throws Exception
	 *
	 * @return the specific metrics in array form
	 **/
	function get_metrics( $account_id, $metrics, $start_date, $end_date, $dimensions = null, $sort = null, $filter = null, $limit = null ) {
		$params = array();

		if ( $dimensions ) {
			$params['dimensions'] = $dimensions;
		}

		if ( $sort ) {
			$params['sort'] = $sort;
		}

		if ( $filter ) {
			$params['filters'] = $filter;
		}

		if ( $limit ) {
			$params['max-results'] = $limit;
		}

		$account_id = str_replace( 'ga:', '', $account_id );

		if ( ! $account_id ) {
			throw new Exception( __( 'GA account ID is not set.', 'lsr_lang' ) );
		}

		return $this->analytics->data_ga->get(
			'ga:' . $account_id,
			$start_date,
			$end_date,
			$metrics,
			$params
		);
	}
}