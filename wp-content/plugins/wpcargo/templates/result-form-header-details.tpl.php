<div id="wpcargo-track-header" class="wpcargo-col-md-12 text-center detail-section">
    <div class="comp_logo">
        <?php $options = get_option('wpcargo_option_settings');  ?>
        <img src="<?php echo !empty($options['settings_shipment_ship_logo']) ? $options['settings_shipment_ship_logo'] : ''; ?>" style="margin: 0 auto;">
    </div><!-- comp_logo -->
	<?php
		$options = get_option('wpcargo_option_settings');
		$barcode_settings = !empty($options['settings_barcode_checkbox']) ? $options['settings_barcode_checkbox'] : '';
		if(!empty($barcode_settings)) {
			?>
		    <div class="b_code">
		        <img src="<?php echo $url_barcode; ?>" alt="<?php echo $tracknumber; ?>" style="margin: 0 auto;" />
		    </div><!-- b_code -->
			<?php
		}
	?>
	<div class="shipment-number">
        <span class="wpcargo-title" style="display: block; font-size: 18px!important;"><?php echo apply_filters('wpcargo_track_result_shipment_number', $tracknumber ); ?></span>
    </div><!-- Track_Num -->
</div>