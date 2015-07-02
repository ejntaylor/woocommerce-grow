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
class WooCommerce_Grow_Settings extends WC_Integration {

	const GOOGLE_CLIENT_ID = 'ENTER CODE HERE';

	const GOOGLE_CONSOLE = 'https://console.developers.google.com/';

	public function __construct() {
		$this->id                 = 'woocommerce_grow';
		$this->method_title       = __( 'WooCommerce Grow', 'woocommerce-grow' );
		$this->method_description = __(
			'Here you will setup your Google Analytics
			Credentials and allow WooCommerce Grow to use your GA account.', 'woocommerce-grow'
		);

		// Load the settings.
		$this->init_settings();

		// Load admin form
		$this->init_form_fields();

		// Hooks
		add_action( 'woocommerce_update_options_integration_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Init integration form fields
	 */
	public function init_form_fields() {
		$this->form_fields = apply_filters(
			'woocommerce_grow_settings_array',
			array(
				// TODO: Highlight the Code field
				'ga_authentication'  => array(
					'title'       => __( 'Authentication', 'woocommerce-grow' ),
					'description' => __( 'Grant access to your Google account.', 'woocommerce-grow' ),
					'type'        => 'ga_auth_button',
					'class'       => 'button',
					'desc_tip'    => true
				),
				// TODO: Hide the token for security. May be show if it is set, or add remove token, or reset tokens when the Allow access is clicked.
				'authorization_code' => array(
					'title'       => __( 'Authorization Code', 'woocommerce-grow' ),
					'description' => __( 'Enter in the field the Authorization code provided to you, when you allow access to your Google account.', 'woocommerce-grow' ),
					'type'        => 'password',
					'placeholder' => __( 'Paste Code Here', 'woocommerce-grow' )
				),
				'ga_api_uid' => array(
					'title'       => __( 'Google Account UID', 'woocommerce-grow' ),
					'description' => __( 'Choose the account User ID.', 'woocommerce-grow' ),
					'type'        => 'wc_grow_uids',
					'class'	=> 'wc-enhanced-select chosen_select'
				),
			)
		);
	}

	/**
	 * Custom field for the Google Account UID
	 *
	 * @since 1.0
	 *
	 * @param $key
	 * @param $data
	 *
	 * @return string
	 */
	public function generate_wc_grow_uids_html( $key, $data ) {
		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
			'options'           => array()
		);

		$data               = wp_parse_args( $data, $defaults );
		$authorization_code = $this->get_option( 'authorization_code' );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<?php if ( '' != $authorization_code ) :
						$data['options'] = $this->get_ga_account_ids();
						?>
						<legend class="screen-reader-text">
							<span><?php echo wp_kses_post( $data['title'] ); ?></span>
						</legend>
						<select class="select <?php echo esc_attr( $data['class'] ); ?>" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?>>
							<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
								<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, esc_attr( $this->get_option( $key ) ) ); ?>><?php echo esc_attr( $option_value ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo $this->get_description_html( $data ); ?>
					<?php else : ?>
						<p class="description">
							<?php _e( 'Please Save the Autorization Code. You will then be able to set the Google Account UID.', 'woocommerce-grow' ) ?>
						</p>
					<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Custom field for the Google Account Access Button
	 *
	 * @since 1.0
	 *
	 * @param $key
	 * @param $data
	 *
	 * @return string
	 */
	public function generate_ga_auth_button_html( $key, $data ) {
		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'title'             => '',
			'label'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array()
		);

		$data = wp_parse_args( $data, $defaults );
		$ga   = WooCommerce_Grow_Google_Analytics::get_instance();

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text">
						<span><?php echo wp_kses_post( $data['title'] ); ?></span>
					</legend>
					<a href="" <?php disabled( $data['disabled'], true ); ?>
					   id="<?php echo esc_attr( $field ); ?>"
					   class="<?php echo esc_attr( $data['class'] ); ?>"
					   data-redirect-url="<?php echo esc_url( $ga->get_authentication_url() ); ?>"
					   style="<?php echo esc_attr( $data['css'] ); ?>"
						<?php echo $this->get_custom_attribute_html( $data ); ?>>
						<?php echo esc_attr( __( 'Allow Access to your Google account', 'woocommerce-grow' ) ) ?>
					</a>
					<br />
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>
		<?php

		wp_enqueue_script( 'wc-grow-settings' );

		return ob_get_clean();
	}

	/**
	 * Checks if the WordPress API is a valid method for selecting an account
	 *
	 * @return a list of accounts if available, false if none available
	 **/
	function get_ga_account_ids() {
		$accounts = array();

		$ga = WooCommerce_Grow_Google_Analytics::get_instance();

		if ( ! $ga->is_app_authenticated() ) {
			return false;
		}

		// Get user available profiles
		$accounts = $ga->get_all_ga_profiles();

		natcasesort( $accounts );

		# Return the account array if there are accounts
		return count( $accounts ) > 0 ? $accounts : array();
	}
}