<?php
/**
 * PhpStorm
 *
 * @since
 * @author VanboDevelops
 */

?>

<h1><?php _e( 'WooCommerce Grow Targets', 'woocommerce-grow' ); ?> <?php echo __( 'by' ); ?>
	<a href="#" class="wc-grow-logo"><?php echo __( 'Raison' ) ?></a></h1>
<div class="page-content-wrapper">
	<form method="post" enctype="multipart/form-data">
		<div class="wc-grow-cards-wrapper">
			<?php
			// TODO: Add months to plugin activation hook
			$months = ''; //get_option( WooCommerce_Grow::PREFIX . 'target_months', '' );

			if ( '' == $months ) {
				$months = WooCommerce_Grow_Helpers::get_twelve_months_ahead();
				//			update_option( WooCommerce_Grow::PREFIX . 'target_months', $months );
			}

			$monthly_targets  = WooCommerce_Grow_Helpers::get_option( 'monthly_targets', array() );
			$growth_rate      = WooCommerce_Grow_Helpers::get_option( 'growth_rate' );
			$initial_revenue  = WooCommerce_Grow_Helpers::get_option( 'initial_revenue_number' );
			$initial_orders   = WooCommerce_Grow_Helpers::get_option( 'initial_orders_number' );
			$initial_sessions = WooCommerce_Grow_Helpers::get_option( 'initial_sessions_number' );
			$initial_cr       = WooCommerce_Grow_Helpers::get_option( 'initial_cr_number' );
			$initial_aov      = WooCommerce_Grow_Helpers::get_option( 'initial_aov_number' );
			$currency_symbol = get_woocommerce_currency_symbol();
			foreach ( $months as $target_month ) {
				$month         = $target_month['month'];
				$year          = $target_month['year'];
				$month_in_text = date( 'F', strtotime( $year . '-' . $month ) );

		$revenue_percentage  = isset( $monthly_targets['revenue_percentage'][ $year ][ $month ] ) ? $monthly_targets['revenue_percentage'][ $year ][ $month ] : 'N/A';
		$orders_percentage   = isset( $monthly_targets['orders_percentage'][ $year ][ $month ] ) ? $monthly_targets['orders_percentage'][ $year ][ $month ] : 'N/A';
		$sessions_percentage = isset( $monthly_targets['sessions_percentage'][ $year ][ $month ] ) ? $monthly_targets['sessions_percentage'][ $year ][ $month ] : 'N/A';
		$cr_percentage       = isset( $monthly_targets['cr_percentage'][ $year ][ $month ] ) ? $monthly_targets['cr_percentage'][ $year ][ $month ] : 'N/A';
		$aov_percentage      = isset( $monthly_targets['aov_percentage'][ $year ][ $month ] ) ? $monthly_targets['aov_percentage'][ $year ][ $month ] : 'N/A';
				?>
				<div class="wc-grow-month-card">
					<div class="wc-grow-panel wc-grow-month-card-panel-top wc-grow-background-color-blue">
						<div class="wc-grow-float-right">
							<span class="wc-grow-month-name"><?php echo esc_html( $month_in_text . ', ' . $year ); ?></span>
							<span class="dashicons dashicons-calendar-alt"></span>
						</div>
					</div>
					<div class="wc-grow-panel wc-grow-month-card-panel-bottom">
						<div class="wc-grow-table wc-grow-revenue">
							<div style="" class="wc-grow-row">
								<div style="" class="wc-grow-cell-left">
									<p class="wc-grow-title"><?php echo __( 'Revenue', 'woocommerce-grow' ) ?></p>

									<p class="wc-grow-revenue-percentage"><?php echo esc_attr( $revenue_percentage ); ?>%</p>
									<input
										class="wc-grow-input-sessions wc-grow-input"
										type="hidden"
										name="revenue_percentage[<?php echo esc_attr( $year ) ?>][<?php echo esc_attr( $month ) ?>]"
										value="<?php echo esc_attr( $revenue_percentage ); ?>" />
								</div>
								<div style="" class="wc-grow-cell-right wc-grow-cell-span-2">
									<div class="wc-grow-target-bar clearfix wc-grow-background-color-purple" data-percent="<?php echo esc_attr( $revenue_percentage ); ?>%">
										<div class="wc-grow-target-bar-title">
											<span><?php echo esc_html( $revenue_percentage ); ?>%</span>
										</div>
										<div class="wc-grow-target-bar-bar"></div>
									</div>
									<p class="wc-grow-target-revenue-result">
									<span class="wc-grow-actual-result wc-grow-color-purple">
										<?php echo $currency_symbol; ?>2520
									</span>
										/ <?php echo $currency_symbol; ?>43750
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="wc-grow-panel wc-grow-month-card-panel-bottom">
						<div class="wc-grow-table wc-grow-orders">
							<div style="" class="wc-grow-row">
								<div style="" class="wc-grow-cell-left">
									<p class="wc-grow-title"><?php echo __( 'Orders', 'woocommerce-grow' ) ?></p>

									<p class="wc-grow-orders-percentage"><?php echo esc_attr( $orders_percentage ); ?>%</p>
									<input
										class="wc-grow-input-sessions wc-grow-input"
										type="hidden"
										name="orders_percentage[<?php echo esc_attr( $year ) ?>][<?php echo esc_attr( $month ) ?>]"
										value="<?php echo esc_attr( $orders_percentage ); ?>" />
								</div>
								<div style="" class="wc-grow-cell-right wc-grow-cell-span-2">
									<div class="wc-grow-target-bar clearfix wc-grow-background-color-purple" data-percent="<?php echo esc_attr( $orders_percentage ); ?>%">
										<div class="wc-grow-target-bar-title">
											<span><?php echo esc_html( $orders_percentage ); ?>%</span>
										</div>
										<div class="wc-grow-target-bar-bar"></div>
									</div>
									<p class="wc-grow-target-orders-result">
									<span class="wc-grow-actual-result wc-grow-color-purple">
										320
									</span>
										/ 1250
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="wc-grow-panel wc-grow-month-card-panel-bottom">
						<div class="wc-grow-table wc-grow-details">
							<div style="" class="wc-grow-row">
								<div class="wc-grow-cell-left" style="">
									<p class="wc-grow-title"><?php echo __( 'Sessions', 'woocommerce-grow' ) ?></p>

								</div>
								<div class="wc-grow-cell-middle" style="">
									<p class="wc-grow-percentage wc-grow-color-green" data-percentage="<?php echo esc_attr( $growth_rate ); ?>">
										<span class="wc-grow-percentage-value"><?php echo esc_attr( $growth_rate ); ?></span>%
										<input
											class="wc-grow-input-sessions wc-grow-input"
											type="hidden"
											name="sessions_percentage[growth_rate][<?php echo esc_attr( $year ) ?>][<?php echo esc_attr( $month ) ?>]"
											value="<?php echo esc_attr( $growth_rate ); ?>" />
										<span class="wc-grow-slider"></span>
									</p>
								</div>
								<div style="" class="wc-grow-cell-right">
									<p class="wc-grow-targets">
										<?php echo esc_html( $sessions_percentage ); ?>
										<input
											class="wc-grow-input-sessions wc-grow-input"
											type="hidden"
											name="sessions_percentage[target][<?php echo esc_attr( $year ) ?>][<?php echo esc_attr( $month ) ?>]"
											value="<?php echo esc_attr( $sessions_percentage ); ?>" />
									</p>
								</div>
							</div>
							<div style="" class="wc-grow-row">
								<div class="wc-grow-cell-left" style="">
									<p class="wc-grow-title"><?php echo __( 'CR', 'woocommerce-grow' ) ?></p>

								</div>
								<div class="wc-grow-cell-middle">
									<p class="wc-grow-percentage wc-grow-color-green" data-percentage="<?php echo esc_attr( $growth_rate ); ?>">
										<span class="wc-grow-percentage-value"><?php echo esc_attr( $growth_rate ); ?></span>%
										<input
											class="wc-grow-input-cr wc-grow-input"
											type="hidden"
											name="cr_percentage[growth_rate][<?php echo esc_attr( $year ) ?>][<?php echo esc_attr( $month ) ?>]"
											value="<?php echo esc_attr( $growth_rate ); ?>" />
										<span class="wc-grow-slider"></span>
									</p>
								</div>
								<div style="" class="wc-grow-cell-right">
									<p class="wc-grow-targets">
										<?php echo esc_html( $cr_percentage ); ?>%
										<input
											class="wc-grow-input-cr wc-grow-input"
											type="hidden"
											name="cr_percentage[target][<?php echo esc_attr( $year ) ?>][<?php echo esc_attr( $month ) ?>]"
											value="<?php echo esc_attr( $cr_percentage ); ?>" />
									</p>
								</div>
							</div>
							<div style="" class="wc-grow-row">
								<div class="wc-grow-cell-left" style="">
									<p class="wc-grow-title"><?php echo __( 'AOV', 'woocommerce-grow' ) ?></p>

								</div>
								<div class="wc-grow-cell-middle">
									<p class="wc-grow-percentage wc-grow-color-red" data-percentage="<?php echo esc_attr( $growth_rate ); ?>">
										<span class="wc-grow-percentage-value"><?php echo esc_attr( $growth_rate ); ?></span>%
										<input
											class="wc-grow-input-aov wc-grow-input"
											type="hidden"
											name="aov_percentage[growth_rate][<?php echo esc_attr( $year ) ?>][<?php echo esc_attr( $month ) ?>]"
											value="<?php echo esc_attr( $growth_rate ); ?>" />
										<span class="wc-grow-slider"></span>
									</p>
								</div>
								<div style="" class="wc-grow-cell-right">
									<p class="wc-grow-targets">
										<?php echo $currency_symbol; ?><?php echo esc_html( $aov_percentage ); ?>
										<input
											class="wc-grow-input-aov wc-grow-input"
											type="hidden"
											name="aov_percentage[target][<?php echo esc_attr( $year ) ?>][<?php echo esc_attr( $month ) ?>]"
											value="<?php echo esc_attr( $aov_percentage ); ?>" />
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>
			<div class="clear"></div>
		</div>
		<div class="wc-grow-side">
			<div class="wc-grow-panel wc-grow-background-color-blue">
				<div class="wc-grow-float-left">
					<span class="wc-grow-month-name"><?php echo __( 'Comparison Month', 'woocommerce-grow' ) ?></span>
				</div>
				<div class="wc-grow-float-right">
					<span class="dashicons dashicons-calendar-alt"></span>
				</div>
			</div>
			<div class="wc-grow-panel wc-grow-background-color-white">
				<p class="wc-grow-title"><?php echo __( 'Revenue', 'woocommerce-grow' ) ?></p>

				<p class="wc-grow-revenue-percentage">
					<span class=""><?php echo $currency_symbol; ?></span>
					<input
						type="hidden"
						class="wc-grow-initial-revenue-number wc-grow-clean-input-field"
						name="initial_revenue_number"
						value="43750" />
					43750
				</p>
			</div>
			<div class="wc-grow-panel wc-grow-background-color-white">
				<p class="wc-grow-title"><?php echo __( 'Orders', 'woocommerce-grow' ) ?></p>

				<p class="wc-grow-revenue-percentage">
					<input
						type="hidden"
						class="wc-grow-initial-orders-number wc-grow-clean-input-field"
						name="initial_orders_number"
						value="1250" />
					1250
				</p>
			</div>
			<div class="wc-grow-panel wc-grow-background-color-white">
				<div class="wc-grow-table wc-grow-details">
					<div style="" class="wc-grow-row">
						<div class="wc-grow-cell-left" style="">
							<p class="wc-grow-title"><?php echo __( 'Sessions', 'woocommerce-grow' ) ?></p>
						</div>
						<div style="" class="wc-grow-cell-right wc-grow-cell-span-2">
							<p class="wc-grow-targets">
								<input
									type="hidden"
									class="wc-grow-initial-sessions-number wc-grow-clean-input-field"
									name="initial_sessions_number"
									value="5000" />
								5000
							</p>
						</div>
					</div>
					<div style="" class="wc-grow-row">
						<div class="wc-grow-cell-left" style="">
							<p class="wc-grow-title"><?php echo __( 'CR', 'woocommerce-grow' ) ?></p>
						</div>
						<div style="" class="wc-grow-cell-right wc-grow-cell-span-2">
							<p class="wc-grow-targets">
								<input
									type="hidden"
									class="wc-grow-initial-cr-number wc-grow-clean-input-field"
									name="initial_cr_number"
									value="2.5" />
								2.5
								<span class="">%</span>
							</p>
						</div>
					</div>
					<div style="" class="wc-grow-row">
						<div class="wc-grow-cell-left" style="">
							<p class="wc-grow-title"><?php echo __( 'AOV', 'woocommerce-grow' ) ?></p>
						</div>
						<div style="" class="wc-grow-cell-right wc-grow-cell-span-2">
							<p class="wc-grow-targets">
								<span class=""><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>
								<input
									type="hidden"
									class="wc-grow-initial-aov-number wc-grow-clean-input-field"
									name="initial_aov_number"
									value="35" />
								<?php echo $currency_symbol; ?>35
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="wc-grow-panel wc-grow-background-color-white">
				<table class="wc-grow-table">
					<tbody>
					<tr>
						<td colspan="2">
							<h2><?php echo __( 'Growth Rate', 'woocommerce-grow' ) ?></h2>
							<i><?php echo __( 'This will overwrite all targets', 'woocommerce-grow' ) ?></i>
						</td>
					</tr>
					<tr>
						<td class="wc-grow-cell-span-1">
							<input
								type="text"
								name="growth_rate"
								class="wc-grow-growth-rate"
								value="5" />
							<span class="wc-growth-rate-percentage">%</span>
						</td>
						<td>
							<button type="submit" class="button wc-grow-button" name="calculate_growth"><?php echo __( 'Calculate Growth', 'woocommerce-grow' ); ?></button>
						</td>
					</tr>
					<tr>
						<td class="wc-grow-cell-span-1"></td>
						<td>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="wc-grow-panel wc-grow-background-color-white">
				<button type="submit" class="button wc-grow-button" name="save_calculations"><?php echo __( 'Save', 'woocommerce-grow' ); ?></button>
			</div>
			<?php wp_nonce_field( 'woocommerce-grow-targets' ); ?>
			<div class="wc-grow-panel wc-grow-background-color-white"></div>
		</div>
	</form>
</div>