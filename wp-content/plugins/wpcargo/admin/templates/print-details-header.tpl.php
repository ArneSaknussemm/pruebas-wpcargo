<?php
$shipment_id	= $shipment_detail->ID;
$tracknumber	= $shipment_detail->post_title;
$url_barcode	= WPCARGO_PLUGIN_URL."/includes/barcode.php?codetype=Code128&size=60&text=" . $tracknumber . "";
?>
<div id="wpcargo-print-layout" style="overflow: hidden;">
	<div class="print-tn one-half first">
		<img src="<?php echo $url_barcode; ?>" alt="<?php echo $tracknumber;?>" />
		<span style="display: block; font-size: 18px!important;"><?php echo $tracknumber; ?></span>
	</div>
	<div class="print-logo one-half">
		<?php $options = get_option('wpcargo_option_settings');  ?>
		<img src="<?php echo $options['settings_shipment_ship_logo']; ?>">
	</div>
</div>