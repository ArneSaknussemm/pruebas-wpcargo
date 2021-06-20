<?php do_action( 'wpcfe_before_invoice_content', $shipmentDetails ); ?>
<?php $rtl 		  = is_rtl() ? 'right' : 'left';  ?>
<style>
	table#admin-print-invoice, #package-table table { width: 100%; border-collapse: collapse; }
	#package-table tr:nth-of-type(odd) { background: #eee; }
	#package-table th { background: #333; color: white; font-weight: bold; }
	#package-table th { background: #333; color: white; font-weight: bold; }
	#package-table td, #package-table th, #admin-print-invoice td, #admin-print-invoice th { padding: 6px; text-align: <?php echo $rtl; ?>; }
	#package-table td, #package-table th { border: 1px solid #ccc; }
	@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px){
		#admin-print-invoice table, #admin-print-invoice thead, #admin-print-invoice tbody, #admin-print-invoice th, #admin-print-invoice td, #admin-print-invoice tr,
		#package-table table, #package-table thead, #package-table tbody, #package-table th, #package-table td, #package-table tr { display: block; }
		#package-table thead { display:none; position: absolute; top: -9999px; left: -9999px; }
		#package-table tr { border: 1px solid #ccc; }
		#package-table td, #admin-print-invoice td { border: none; position: relative; }
		table#package-table td, table#package-table th { padding: 6px 0; }
		#admin-print-invoice td { padding-left: 0; }
		#package-table td { border-bottom: 1px solid #eee; padding-left: 50% !important; }
		#package-table td:before {  position: absolute; top: 6px; left: 6px; width: 45%;  padding-right: 10px;  white-space: nowrap; }
		<?php if( !empty(wpcargo_package_fields()) ): $s_cnt = 1; ?>
		<?php foreach ( wpcargo_package_fields() as $key => $value): ?>
			<?php  if( in_array( $key, wpcargo_package_dim_meta() ) && !wpcargo_package_settings()->dim_unit_enable ){ continue; } ?>
			#package-table td:nth-of-type(<?php echo $s_cnt; ?>):before { content: '<?php echo $value['label']; ?>'; }
		<?php $s_cnt++; endforeach; ?>
		<?php endif; ?>
	}
</style>
<table id="admin-print-invoice" style="width:100%;">
	<?php do_action( 'wpcfe_start_invoice_section', $shipmentDetails ); ?>
	<tr>
		<td style="width:50%;" class="border-bottom">
			<?php do_action( 'wpcfe_invoice_site_info', $shipmentDetails ); ?>
		</td>
		<td style="width:50%;" class="border-bottom">
			<?php do_action( 'wpcfe_invoice_barcode_info', $shipmentDetails ); ?>
		</td>
	</tr>
	<?php do_action( 'wpcfe_middle_invoice_section', $shipmentDetails ); ?>
	<tr>
		<td style="width:50%;" class="space-topbottom">
			<?php do_action( 'wpcfe_invoice_shipper_info', $shipmentDetails ); ?>
		</td>
		<td style="width:50%;" class="space-topbottom">
			<?php do_action( 'wpcfe_invoice_receiver_info', $shipmentDetails ); ?>
		</td>
	</tr>
	<?php do_action( 'wpcfe_end_invoice_section', $shipmentDetails ); ?>
</table>
<?php do_action( 'wpcfe_after_invoice_content', $shipmentDetails ); ?>
