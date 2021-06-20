<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$shipment_id 						= $shipment_detail->ID;
$shipment_origin  					= wpcargo_get_postmeta( $shipment_id, 'wpcargo_origin_field' );
$wpcargo_status   					= wpcargo_get_postmeta( $shipment_id, 'wpcargo_status' );
$shipment_destination  				= wpcargo_get_postmeta( $shipment_id, 'wpcargo_destination' );
$type_of_shipment  					= wpcargo_get_postmeta( $shipment_id, 'wpcargo_type_of_shipment' );
$shipment_weight  					= wpcargo_get_postmeta( $shipment_id, 'wpcargo_weight' );
$shipment_courier  					= wpcargo_get_postmeta( $shipment_id, 'wpcargo_courier' );
$shipment_carrier_ref_number  		= wpcargo_get_postmeta( $shipment_id, 'wpcargo_carrier_ref_number' );
$shipment_product  					= wpcargo_get_postmeta( $shipment_id, 'wpcargo_product' );
$shipment_qty  						= wpcargo_get_postmeta( $shipment_id, 'wpcargo_qty' );
$shipment_payment_mode  			= wpcargo_get_postmeta( $shipment_id, 'payment_wpcargo_mode_field' );
$shipment_total_freight  			= wpcargo_get_postmeta( $shipment_id, 'wpcargo_total_freight' );
$shipment_mode  					= wpcargo_get_postmeta( $shipment_id, 'wpcargo_mode_field' );
$shipment_departure_time  			= wpcargo_get_postmeta( $shipment_id, 'wpcargo_departure_time_picker' );
$delivery_date	            		= wpcargo_get_postmeta( $shipment_id, 'wpcargo_expected_delivery_date_picker', 'date' );
$shipment_comments  				= wpcargo_get_postmeta( $shipment_id, 'wpcargo_comments' );
$shipment_packages  				= wpcargo_get_postmeta( $shipment_id, 'wpcargo_packages' );
$shipment_carrier  					= wpcargo_get_postmeta( $shipment_id, 'wpcargo_carrier_field' );
$pickup_date  				      	= wpcargo_get_postmeta( $shipment_id, 'wpcargo_pickup_date_picker', 'date' );
$shipment_pickup_time  				= wpcargo_get_postmeta( $shipment_id, 'wpcargo_pickup_time_picker' );
?>
<div id="print-shipment-info" class="wpcargo-row print-section">
	<p id="print-receiver-header" class="header-title"><strong><?php echo apply_filters('result_shipment_details', esc_html__('Shipment Information', 'wpcargo')); ?></strong></p>
	<div class="one-third first">
		<p class="wpcargo-label"><?php esc_html_e('Origin:', 'wpcargo') . ''; ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_origin; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Package:', 'wpcargo') . ''; ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_packages; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Status:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info">
			<span class="<?php echo str_replace( ' ','_', strtolower( $wpcargo_status ) ); ?>" ><?php  echo $wpcargo_status; ?></span>
		</p>
	</div>
	<div class="one-third first">
		<p class="wpcargo-label"><?php esc_html_e('Destination:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_destination; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Carrier:', 'wpcargo') . ''; ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_carrier; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Type of Shipment:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php  echo $type_of_shipment; ?></p>
	</div>
	<div class="one-third first">
		<p class="wpcargo-label"><?php esc_html_e('Weight:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_weight; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Shipment Mode:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_mode; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Carrier Reference No.:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_carrier_ref_number; ?></p>
	</div>
	<div class="one-third first">
		<p class="wpcargo-label"><?php esc_html_e('Product:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_product; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Qty:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_qty; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Payment Mode:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_payment_mode; ?></p>
	</div>
	<div class="one-third first">
		<p class="wpcargo-label"><?php esc_html_e('Total Freight:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_total_freight; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Expected Delivery Date:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $delivery_date; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Departure Time:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_departure_time; ?></p>
	</div>
	<div class="one-third first">
		<p class="wpcargo-label"><?php esc_html_e('Pick-up Date:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $pickup_date; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Pick-up Time:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_pickup_time; ?></p>
	</div>
	<div class="one-third">
		<p class="wpcargo-label"><?php esc_html_e('Comments:', 'wpcargo'); ?></p>
		<p class="wpcargo-label-info"><?php echo $shipment_comments; ?></p>
	</div>
</div>