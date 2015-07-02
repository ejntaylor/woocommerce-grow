<?php
/**
 * PhpStorm
 * 
 * @since 
 * @author VanboDevelops
 */
?>

<h1><?php _e( 'WooCommerce Grow', 'woocommerce-grow' ); ?> <?php echo __( 'by' ); ?> <a href="#" class="wc-grow-logo"><?php echo __( 'Raison' ) ?></a></h1>
<div class="page-content-wrapper">
	<div class="wc-grow-cards-wrapper">
		<div class="wc-grow-cards-current-view">
			<div class="wc-grow-month-card">
				<div class="wc-grow-panel wc-grow-month-card-panel-top wc-grow-background-color-green">
					<div class="wc-grow-float-left wc-grow-color-green">
						<span class="dashicons dashicons-yes"></span>
						<span class="wc-grow-followed-target"><?php echo __( 'On Target', 'woocommerce-grow' ) ?></span>
					</div>
					<div class="wc-grow-float-right">
						<span class="wc-grow-month-name">5 days left</span>
						<span class="dashicons dashicons-calendar-alt"></span>
					</div>
				</div>
				<div class="wc-grow-panel wc-grow-panel wc-grow-month-card-panel-bottom">
					<div class="wc-grow-table wc-grow-revenue">
						<div style="" class="wc-grow-row">
							<div style="" class="wc-grow-cell-left">
								<p class="wc-grow-title">Revenue</p>
								<p class="wc-grow-revenue-percentage">28%</p>
							</div>
							<div style="" class="wc-grow-cell-right wc-grow-cell-span-2">
								<div class="wc-grow-target-bar clearfix wc-grow-background-color-purple" data-percent="28%">
									<div class="wc-grow-target-bar-title"><span>28%</span></div>
									<div class="wc-grow-target-bar-bar"></div>
								</div>
								<p class="wc-grow-target-revenue-result">
									<span class="wc-grow-actual-result wc-grow-color-purple">
										$2520
									</span>
									/ $43750
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="wc-grow-panel wc-grow-panel wc-grow-month-card-panel-bottom">
					<div class="wc-grow-table wc-grow-orders">
						<div style="" class="wc-grow-row">
							<div style="" class="wc-grow-cell-left">
								<p class="wc-grow-title">Orders</p>
								<p class="wc-grow-revenue-percentage">45%</p>
							</div>
							<div style="" class="wc-grow-cell-right wc-grow-cell-span-2">
								<div class="wc-grow-target-bar clearfix wc-grow-background-color-purple" data-percent="45%">
									<div class="wc-grow-target-bar-title"><span>45%</span></div>
									<div class="wc-grow-target-bar-bar"></div>
								</div>
								<p class="wc-grow-target-revenue-result">
									<span class="wc-grow-actual-result wc-grow-color-purple">
										320
									</span>
									/ 500
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="wc-grow-panel wc-grow-panel wc-grow-month-card-panel-bottom">
					<div class="wc-grow-table wc-grow-details">
						<div style="" class="wc-grow-row">
							<div class="wc-grow-cell-left" style="">
								<p class="wc-grow-title">Sessions</p>

							</div>
							<div class="wc-grow-cell-middle" style="">
								<p class="wc-grow-percentage wc-grow-color-green">+8%</p>
							</div>
							<div style="" class="wc-grow-cell-right">
								<p class="wc-grow-targets">
									4500 / 5000
								</p>
							</div>
						</div>
						<div style="" class="wc-grow-row">
							<div class="wc-grow-cell-left" style="">
								<p class="wc-grow-title">CR</p>

							</div>
							<div class="wc-grow-cell-middle" style="">
								<p class="wc-grow-percentage wc-grow-color-green">+3%</p>
							</div>
							<div style="" class="wc-grow-cell-right">
								<p class="wc-grow-targets">
									2.8% / 2.4%
								</p>
							</div>
						</div>
						<div style="" class="wc-grow-row">
							<div class="wc-grow-cell-left" style="">
								<p class="wc-grow-title">AOV</p>

							</div>
							<div class="wc-grow-cell-middle" style="">
								<p class="wc-grow-percentage wc-grow-color-red">-4%</p>
							</div>
							<div style="" class="wc-grow-cell-right">
								<p class="wc-grow-targets">
									$36 / $45
								</p>
							</div>
						</div>
					</div>
				</div>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('.wc-grow-target-bar').each(function(){
							jQuery(this).find('.wc-grow-target-bar-bar').animate({
								width:jQuery(this).attr('data-percent')
							},3000);
						});
					});
				</script>
			</div>
			<div class="clear"></div>
		</div>
		<div>
			<h2 class="wc-grow-sub-title"><?php _e( 'Growth History', 'woocommerce-grow' ); ?></h2>
			<button class="button wc-grow-view-all-history"><?php echo __( 'View All History', 'woocommerce-grow' ); ?></button>
			<div class="clear"></div>
		</div>
		<?php
		$months = array(
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
		);

		foreach( $months as $month ) {
			?>
			<div class="wc-grow-month-card">
				<div class="wc-grow-panel wc-grow-month-card-panel-top wc-grow-background-color-blue">
					<div class="wc-grow-float-left wc-grow-color-red">
						<span class="dashicons dashicons-no-alt"></span>
						<span class="wc-grow-followed-target"><?php echo __( 'Missed Target', 'woocommerce-grow' ) ?></span>
					</div>
					<div class="wc-grow-float-right">
						<span class="wc-grow-month-name"><?php echo esc_html( $month ); ?></span>
						<span class="dashicons dashicons-calendar-alt"></span>
					</div>
				</div>
				<div class="wc-grow-panel wc-grow-month-card-panel-bottom">
					<div class="wc-grow-table wc-grow-revenue">
						<div style="" class="wc-grow-row">
							<div style="" class="wc-grow-cell-left">
								<p class="wc-grow-title">Revenue</p>
								<p class="wc-grow-revenue-percentage">28%</p>
							</div>
							<div style="" class="wc-grow-cell-right wc-grow-cell-span-2">
								<div class="wc-grow-target-bar clearfix wc-grow-background-color-purple" data-percent="28%">
									<div class="wc-grow-target-bar-title"><span>28%</span></div>
									<div class="wc-grow-target-bar-bar"></div>
								</div>
								<p class="wc-grow-target-revenue-result">
									<span class="wc-grow-actual-result wc-grow-color-purple">
										$2520
									</span>
									/ $43750
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="wc-grow-panel wc-grow-month-card-panel-bottom">
					<div class="wc-grow-table wc-grow-orders">
						<div style="" class="wc-grow-row">
							<div style="" class="wc-grow-cell-left">
								<p class="wc-grow-title">Orders</p>
								<p class="wc-grow-revenue-percentage">45%</p>
							</div>
							<div style="" class="wc-grow-cell-right wc-grow-cell-span-2">
								<div class="wc-grow-target-bar clearfix wc-grow-background-color-purple" data-percent="45%">
									<div class="wc-grow-target-bar-title"><span>45%</span></div>
									<div class="wc-grow-target-bar-bar"></div>
								</div>
								<p class="wc-grow-target-revenue-result">
									<span class="wc-grow-actual-result wc-grow-color-purple">
										320
									</span>
									/ 500
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="wc-grow-panel wc-grow-month-card-panel-bottom">
					<div class="wc-grow-table wc-grow-details">
						<div style="" class="wc-grow-row">
							<div class="wc-grow-cell-left" style="">
								<p class="wc-grow-title">Sessions</p>

							</div>
							<div class="wc-grow-cell-middle" style="">
								<p class="wc-grow-percentage wc-grow-color-green">+8%</p>
							</div>
							<div style="" class="wc-grow-cell-right">
								<p class="wc-grow-targets">
									4500 / 5000
								</p>
							</div>
						</div>
						<div style="" class="wc-grow-row">
							<div class="wc-grow-cell-left" style="">
								<p class="wc-grow-title">CR</p>

							</div>
							<div class="wc-grow-cell-middle" style="">
								<p class="wc-grow-percentage wc-grow-color-green">+3%</p>
							</div>
							<div style="" class="wc-grow-cell-right">
								<p class="wc-grow-targets">
									2.8% / 2.4%
								</p>
							</div>
						</div>
						<div style="" class="wc-grow-row">
							<div class="wc-grow-cell-left" style="">
								<p class="wc-grow-title">AOV</p>

							</div>
							<div class="wc-grow-cell-middle" style="">
								<p class="wc-grow-percentage wc-grow-color-red">-4%</p>
							</div>
							<div style="" class="wc-grow-cell-right">
								<p class="wc-grow-targets">
									$36 / $45
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
		<div class="wc-grow-panel">
			<h2>Monthly Grow Summary</h2>
			<p>This month you need to increase:</p>

			<ul>
				<li>Traffic by 2500 per month</li>
				<li>Conversion Rate by 1.25%</li>
				<li>Average order value by $25</li>
			</ul>

			<h4>Recent Notifications</h4>
			<div class="wc-grow-table">
				<div class="wc-grow-row">
					<div class="wc-grow-cell-left">
						15.1
					</div>
					<div class="wc-grow-cell-right wc-grow-cell-span-2">
						We've added PayPal to our payment processors.
					</div>
				</div>
				<div class="wc-grow-row">
					<div class="wc-grow-cell-left">
						15.1
					</div>
					<div class="wc-grow-cell-right wc-grow-cell-span-2">
						This month's payments will be delayed by 24th
					</div>
				</div>
				<div class="wc-grow-row">
					<div class="wc-grow-cell-left">
						15.1
					</div>
					<div class="wc-grow-cell-right wc-grow-cell-span-2">
						Our servcers are down!
					</div>
				</div>
			</div>
		</div>
	</div>
</div>