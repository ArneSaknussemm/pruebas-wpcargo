<form id="wpc-ie-form" method="POST" action="<?php echo admin_url(); ?>edit.php?post_type=wpcargo_shipment&page=wpc-report-export" >
			<?php wp_nonce_field( 'wpc_import_ie_results_callback', 'wpc_ie_nonce' ); ?>
            <p><strong class="left-lbl"><?php esc_html_e('Shipper Name:','wpcargo'); ?></strong> <input id="search-shipper" type="text" name="search-shipper" value="<?php echo isset($_REQUEST['search-shipper']) ? $_REQUEST['search-shipper'] : '';  ?>" /></p>
            <?php if( !empty($users) && !is_wpcargo_client() ): ?>
				<p>
					<strong class="left-lbl"><?php esc_html_e( 'Registered Shipper:', 'wpcargo' ); ?></strong>
					<select name="registered_shipper" class="form-control browser-default custom-select" id="registered_shipper">
						<option value=""><?php esc_html_e('-- Registered Shipper --', 'wpcargo' ); ?></option>
						<?php foreach( $users as $user ): ?>
							<option value="<?php  echo $user->ID; ?>" <?php selected( $registered_shipper, $user->ID ); ?> ><?php echo $wpcargo->user_fullname( $user->ID ); ?></option>
						<?php endforeach; ?>      
					</select>
				</p>
            <?php endif; ?>
			<p id="import-datepicker"><strong class="left-lbl"><?php esc_html_e('Date Range','wpcargo'); ?> : </strong></p>
			<p id="daterange-section" style="padding-left: 20px;">
				<label for="date-from" ><strong><?php esc_html_e('From : ','wpcargo'); ?></strong></label>
				<input class="wpcargo-datepicker" type="text" id="wpcargo-import-form" name="date-from" value="<?php echo isset($_REQUEST['date-from']) ? $_REQUEST['date-from'] : ''; ?>" required />
				<label for="date-to"><strong><?php esc_html_e('To : ','wpcargo'); ?></strong></label>
				<input class="wpcargo-datepicker" type="text" id="wpcargo-import-to" name="date-to" value="<?php echo isset($_REQUEST['date-to']) ? $_REQUEST['date-to'] : ''; ?>" required />
			</p>
			<p>
			<strong class="left-lbl"><?php esc_html_e('Status','wpcargo'); ?>: </strong>
			<select name="wpcargo_status" class="wpc-status">
				<?php
				if(!empty($wpcargo->status)) {
					echo '<option value="">-- '.esc_html__('Choose Status', 'wpcargo').' --</option>';
					foreach( $wpcargo->status as $status ){
						$get_selected_value = isset($_REQUEST['wpcargo_status']) && $_REQUEST['wpcargo_status'] == $status ? 'selected' : '';
						echo '<option value="'.$status.'" '.$get_selected_value.'>'.$status.'</option>';
					}
				}
				?>
			</select>
			</p>
            <div id="multi-select-export">
                <p><strong><?php esc_html_e('Select Option','wpcargo'); ?></strong></p>
                <div class="row">
                    <div class="col-xs-5">
                        <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple">
                            <?php
                            if($fields) {
                            	asort($fields);
                                foreach( $fields as $key => $value ){
                                    ?><option value="<?php echo $value['meta_key']; ?>"><?php echo $value['label']; ?></option><?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <button type="button" id="multiselect_rightAll" class="btn btn-block"><span class="dashicons dashicons-controls-skipforward"></span></button>
                        <button type="button" id="multiselect_rightSelected" class="btn btn-block"><span class="dashicons dashicons-controls-forward"></span></button>
                        <button type="button" id="multiselect_leftSelected" class="btn btn-block"><span class="dashicons dashicons-controls-back"></span></button>
                        <button type="button" id="multiselect_leftAll" class="btn btn-block"><span class="dashicons dashicons-controls-skipback"></span></button>
                    </div>
                    <div class="col-xs-5">
                        <select name="meta-fields[]" id="multiselect_to" class="form-control" size="8" multiple="multiple">
                            <?php
                                if(!empty( $options ) ) {
                                    foreach ($options as $optkey => $optvalue ) {
                                    	echo "<option value='".$optkey."'>".$optvalue."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
			<div style="clear:both;"></div>
            <input type="hidden" name="post_type" value="wpcargo_shipment" />
            <input type="hidden" name="page" value="<?php echo $page; ?>" />
            <p><input style="margin-top: 24px;" class="button button-primary button-large" type="submit" name="submit" value="<?php esc_html_e('Generate Report','wpcargo'); ?>" /></p>
            <p class="description"><?php esc_html_e('Note: This will take a couple of seconds to generate reports, wait for a pop up message to save your report.', 'wpcargo' ); ?></p>
        </form>
	    <div style="clear: both;"></div>