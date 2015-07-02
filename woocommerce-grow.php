<?php
/*
 * Plugin Name: WooCommerce Grow
 * Plugin URI:
 * Description: Grow your WooCommerce business with WooCommerce Grow
 * Version: 1.0
 * Author: Raison | Ivan Andreev
 * Author URI:
 *
 *	Copyright: (c) 2015 Raison | Ivan Andreev
 *	License: GNU General Public License v3.0
 *	License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active
 **/
if ( ! function_exists( 'wc_grow_is_wc_active' ) ) {
	function wc_grow_is_wc_active() {
		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ) {
			return true;
		}

		return false;
	}
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
class WooCommerce_Grow {
	/**
	 * Plugin path
	 * @var string
	 */
	private static $plugin_dir_path = null;

	/**
	 * Plugin url
	 * @var string
	 */
	private static $plugin_url = null;

	const VERSION = '1.0';

	const PREFIX = 'woocommerce_grow_';

	public function __construct() {
		// Check, if WooCommerce is active before initializing the plugin
		if ( ! wc_grow_is_wc_active() ) {
			add_action( 'admin_notices', array( $this, 'notice_activate_wc' ) );
		} else {
			$this->init();
		}
	}

	/**
	 * Initializes the plugin
	 *
	 * @since 1.0
	 */
	public function init() {
		$this->add_autoloader();
		include_once( 'includes/class-woocommerce-grow-compat.php' );

		// Load the gateway text domain
		add_action( 'init', array( $this, 'load_text_domain' ) );

		// Add a 'Settings' link to the plugin action links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'settings_support_link' ), 10, 4 );

		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );

		add_filter( 'woocommerce_integrations', array( $this, 'load_plugin_settings' ) );

		if ( is_admin() ) {
			$this->load_pages();
		}
	}

	/**
	 * Add the plugin autoloader for class files
	 *
	 * @since 1.0
	 */
	public function add_autoloader() {
	    	require_once( 'includes/class-woocommerce-grow-autoloader.php' );
		$loader = new WooCommerce_Grow_Autoloader( self::get_plugin_path() );
		spl_autoload_register( array( $loader, 'load_classes' ) );
	}

	/**
	 * Load Text domains
	 */
	public function load_text_domain() {
		// Add localization on init so WPML to be able to use it.
		load_textdomain( 'woocommerce-grow', WP_LANG_DIR . '/woocommerce-grow/woocommerce-grow-' . get_locale() . '.mo' );
		load_plugin_textdomain( 'woocommerce-grow', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Add Integrations settings page
	 * @return array
	 */
	public function load_plugin_settings() {
		$integrations[] = 'WooCommerce_Grow_Settings';

		return $integrations;
	}

	/**
	 * Load the Google SDK
	 */
	public static function load_google_analytics() {
		require_once( 'includes/vendor/google-api-php-client/src/Google/autoload.php' );
	}

	/**
	 * Load Pages
	 */
	public function load_pages() {
	    	$pages = new WooCommerce_Grow_Menu();
		$pages->hooks();
	}

	/**
	 * Add Gateway admin scripts
	 */
	public function add_scripts() {
		wp_register_script( 'wc-grow-settings', self::get_plugin_url() . '/assets/js/settings.js', array( 'jquery' ), self::VERSION, true );
		wp_register_script( 'wc-grow-admin', self::get_plugin_url() . '/assets/js/admin.js', array( 'jquery' ), self::VERSION, true );

		wp_register_style( 'wc-grow-styles', self::get_plugin_url() . '/assets/css/admin.css', array(), self::VERSION );
		wp_enqueue_style('wc-grow-styles');
	}

	/**
	 * Get plugin url
	 *
	 * @since 1.0
	 * @return string
	 */
	public static function get_plugin_url() {
		if ( null === self::$plugin_url ) {
			self::$plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		return self::$plugin_url;
	}

	/**
	 * Get plugin path
	 *
	 * @since 1.0
	 * @return string
	 */
	public static function get_plugin_path() {
		if ( null === self::$plugin_dir_path ) {
			self::$plugin_dir_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		return self::$plugin_dir_path;
	}

	/** Add 'Settings' link to the plugin actions links
	 *
	 * @since 1.0
	 * @return array associative array of plugin action links
	 */
	public function settings_support_link( $actions, $plugin_file, $plugin_data, $context ) {

		return array_merge(
			$actions
		);
	}

	/**
	 * Display WooCommerce not active notice
	 *
	 * @since  1.0
	 * @access public
	 */
	public function notice_activate_wc() {

		$message = sprintf(
			__( 'WooCommerce Grow plugin requires %1$sWooCommerce%2$s to function.
			 Please Install and Activate %1$sWooCommerce%2$s.', 'woocommerce-grow' ),
			'<a href="' . admin_url( 'plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins' ) . '">',
			'</a>'
		);

		echo sprintf( '<div class="error"><p>%s</p></div>', $message );
	}
}

/**
 * Load the plugin
 */
add_action( 'plugins_loaded', 'load_wc_grow_plugin' );
function load_wc_grow_plugin() {
	new WooCommerce_Grow();
}