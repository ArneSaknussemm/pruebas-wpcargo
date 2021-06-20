<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function wpcargo_email_footer_divider_callback(){
    $footer_image       = WPCARGO_PLUGIN_URL.'admin/assets/images/wpc-email-footer.png';
    ob_start();
    ?>
    <div class="wpc-footer-devider">
        <img src="<?php echo $footer_image; ?>" style="width:100%;" />
    </div>
    <?php
    echo ob_get_clean();
}
function wpcargo_fields_option_settings_group_callback( $options ){
    require_once( WPCARGO_PLUGIN_PATH.'admin/templates/settings-fields-option.tpl.php' );
}
function wpcargo_plugins_loaded_hook_callback(){
    add_action( 'wpcargo_fields_option_settings_group', 'wpcargo_fields_option_settings_group_callback', 10, 1 );
    add_action( 'wpcargo_email_footer_divider', 'wpcargo_email_footer_divider_callback' );
    if( get_option('shmap_active') && !empty( trim( get_option('shmap_api')  ) ) ){
        add_action('before_wpcargo_shipment_history', 'wpcargo_shipment_history_map_callback', 10, 1 );
    } 
    if( !function_exists('is_plugin_active') ) {	
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    if ( !is_plugin_active( 'wpcargo-frontend-manager/wpcargo-frontend-manager.php' ) ) {
        // Print Label hooks
        add_action( 'wpcfe_before_invoice_content', 'wpcargo_before_invoice_content_callback', 10, 1 );
        add_action( 'wpcfe_invoice_site_info', 'wpcargo_invoice_site_info_callback', 10, 1 );
        add_action( 'wpcfe_invoice_barcode_info', 'wpcargo_invoice_barcode_info_callback', 10, 1 );
        add_action( 'wpcfe_invoice_shipper_info', 'wpcargo_invoice_shipper_info_callback', 10, 1 );
        add_action( 'wpcfe_invoice_receiver_info', 'wpcargo_invoice_receiver_info_callback', 10, 1 );
        add_action( 'wpcfe_end_invoice_section', 'wpcargo_end_invoice_section_callback', 100, 1 );
    } 
    add_action( 'wpcfe_before_invoice_content', 'wpcargo_before_invoice_font_content_callback', 15, 1 );
    
}
add_action( 'plugins_loaded', 'wpcargo_plugins_loaded_hook_callback' );

function wpcargo_activation_setup_settings(){
	// Set up packages settings
	$package_settings = array(
		'wpc_mp_enable_admin' 			=> 1,
		'wpc_mp_enable_frontend' 		=> 1,
		'wpc_mp_enable_dimension_unit' 	=> 1,
		'wpc_mp_dimension_unit' 		=> 'cm',
		'wpc_mp_weight_unit' 			=> 'kg',
		'wpc_mp_piece_type' 			=> 'Pallet, Carton, Crate, Loose, Others',
	);
	if( !empty( get_option( 'wpc_mp_settings' )  ) ){
		$package_settings = array(
			'wpc_mp_enable_admin' 			=> wpcargo_package_settings()->admin_enable,
			'wpc_mp_enable_frontend' 		=> wpcargo_package_settings()->frontend_enable,
			'wpc_mp_enable_dimension_unit' 	=> wpcargo_package_settings()->dim_unit_enable,
			'wpc_mp_dimension_unit' 		=> wpcargo_package_settings()->dim_unit,
			'wpc_mp_weight_unit' 			=> wpcargo_package_settings()->weight_unit,
			'wpc_mp_piece_type' 			=> implode(",", wpcargo_package_settings()->peice_types ),
		);
	}
	update_option( 'wpc_mp_settings', $package_settings );
	// General Settings
	$general_settings = array(
		'settings_shipment_type' 			=> 'Air Freight, International Shipping, Truckload, Van Move',
		'settings_shipment_wpcargo_mode' 	=> 'Sea Transport, Land Shipping, Air Freight',
		'settings_shipment_status' 			=> implode(",", wpcargo_default_status() ),
		'settings_shipment_country' 		=> wpcargo_country_list(),
		'settings_shipment_wpcargo_carrier' => 'DHL, USPS, FedEx',
		'settings_shipment_wpcargo_payment_mode' => 'CASH, Cheque, BACS',
		'settings_shipment_ship_logo' 		=> '',
		'settings_barcode_checkbox'			=> 1,
		'wpcargo_title_prefix_action'		=> 'on',
		'wpcargo_title_prefix'				=> 'WPC',
		'wpcargo_base_color'				=> '#01ba7c',
		'wpcargo_tax'						=> 12,
		'wpcargo_invoice_display_history'	=> 'on',
		'wpcargo_edit_history_role'			=> array( 'administrator', 'wpc_shipment_manager', ),
		'wpcargo_email_employee'			=> false,
		'wpcargo_email_agent'				=> false,
		'wpcargo_email_client'				=> false,
	);
	if( !empty( get_option( 'wpcargo_option_settings' )  ) ){
		$gen_settings 		= get_option( 'wpcargo_option_settings' );
		$barcode_checkbox 	= array_key_exists('settings_barcode_checkbox', $gen_settings ) ? 1 : false;
		$prefix_action 		= array_key_exists('wpcargo_title_prefix_action', $gen_settings ) ? 'on' : false;
		$display_history 	= array_key_exists('wpcargo_invoice_display_history', $gen_settings ) ? 'on' : false;
		$email_employee 	= array_key_exists('wpcargo_email_employee', $gen_settings ) ? 'on' : false;
		$email_agent		= array_key_exists('wpcargo_email_agent', $gen_settings ) ? 'on' : false;
		$email_client		= array_key_exists('wpcargo_email_client', $gen_settings ) ? 'on' : false;
		$general_settings 	= array(
			'settings_shipment_type' 			=> $gen_settings['settings_shipment_type'],
			'settings_shipment_wpcargo_mode' 	=> $gen_settings['settings_shipment_wpcargo_mode'],
			'settings_shipment_status' 			=> $gen_settings['settings_shipment_status'],
			'settings_shipment_country' 		=> $gen_settings['settings_shipment_country'],
			'settings_shipment_wpcargo_carrier' => $gen_settings['settings_shipment_wpcargo_carrier'],
			'settings_shipment_wpcargo_payment_mode' => $gen_settings['settings_shipment_wpcargo_payment_mode'],
			'settings_shipment_ship_logo' 		=> $gen_settings['settings_shipment_ship_logo'],
			'settings_barcode_checkbox'			=>  $barcode_checkbox,
			'wpcargo_title_prefix_action'		=> $prefix_action,
			'wpcargo_title_prefix'				=> $gen_settings['wpcargo_title_prefix'],
			'wpcargo_base_color'				=> $gen_settings['wpcargo_base_color'],
			'wpcargo_tax'						=> $gen_settings['wpcargo_tax'],
			'wpcargo_invoice_display_history'	=> $display_history,
			'wpcargo_edit_history_role'			=> $gen_settings['wpcargo_edit_history_role'],
			'wpcargo_email_employee'			=> $gen_settings['wpcargo_email_employee'],
			'wpcargo_email_agent'				=> $gen_settings['wpcargo_email_agent'],
			'wpcargo_email_client'				=> $gen_settings['wpcargo_email_client'],
		);
	}
	update_option( 'wpcargo_option_settings', $general_settings );
	
	if( empty( get_option('wpcargo_title_suffix') ) ){
		update_option( 'wpcargo_title_suffix', '-CARGO' );
	}
	// Client email settings
	$client_mail_settings = array(
		'wpcargo_active_mail'	=> 1,
		'wpcargo_mail_to'		=> '{wpcargo_shipper_email}',
		'wpcargo_mail_subject' 	=> 'Shipment Notification # {wpcargo_tracking_number}',
		'wpcargo_mail_message' 	=>'',
		'wpcargo_mail_footer'	=> ''
	);
	if( !empty( get_option( 'wpcargo_mail_settings' ) ) ){
		$c_mail_settings 	= get_option( 'wpcargo_mail_settings' );
		$c_active_mail 		= array_key_exists( 'wpcargo_active_mail', $c_mail_settings ) ? $c_mail_settings['wpcargo_active_mail'] : false;
		$client_mail_settings = array(
			'wpcargo_active_mail'	=> $c_active_mail,
			'wpcargo_mail_to'		=> $c_mail_settings['wpcargo_mail_to'],
			'wpcargo_mail_subject' 	=> $c_mail_settings['wpcargo_mail_subject'],
			'wpcargo_mail_message' 	=> $c_mail_settings['wpcargo_mail_message'],
			'wpcargo_mail_footer'	=> $c_mail_settings['wpcargo_mail_footer']
		);
	}
	update_option( 'wpcargo_mail_settings', $client_mail_settings );
	if( empty( get_option( 'wpcargo_admin_mail_active' ) ) ){
		update_option( 'wpcargo_admin_mail_active', 1 );
	}
	if( empty( get_option( 'wpcargo_admin_mail_to' ) ) ){
		update_option( 'wpcargo_admin_mail_to', get_bloginfo('admin_email') );
	}
	if( empty( get_option( 'wpcargo_admin_mail_subject' ) ) ){
		update_option( 'wpcargo_admin_mail_subject', 'Shipment Notification # {wpcargo_tracking_number}' );
	}
}
register_activation_hook( WPCARGO_FILE_DIR, 'wpcargo_activation_setup_settings' );

function wpcargo_track_shipment_history_details( $shipment ) {
    global $wpdb, $wpcargo;
    $settings    = $wpcargo->settings;
    $date_format = $wpcargo->date_format;
    $time_format = $wpcargo->time_format;
    if( !empty( $settings ) ){
        if( !array_key_exists( 'wpcargo_invoice_display_history', $settings ) ){
            if( isset( $_REQUEST['wpcfe'] ) || isset( $_REQUEST['wpcargo_tracking_number'] ) ){
                // Nothing happen
                // Include the shipment history
            }else{
                return false;
            }
            
        }
    }
    $shmap_active 	= get_option('shmap_active');
    $shmap_api      = trim( get_option('shmap_api') );
	if( $shmap_active && !empty( $shmap_api ) ){
		?>
        <div id="shmap-wrapper" style="margin: 12px 0;">
            <?php do_action('before_wpcargo_shipment_history_map', $shipment->ID); ?>
            <div id="wpcargo-shmap" style="height: 320px;"></div>
            <?php do_action('after_wpcargo_shipment_history_map', $shipment->ID); ?>
		</div>
		<?php
	}
	$template = wpcargo_include_template( 'result-shipment-history.tpl' );
	require( $template );
}
add_action('wpcargo_after_track_details', 'wpcargo_track_shipment_history_details', 10, 1);
/*
 * Hooks for Custom Field Add ons
 */
function wpcargo_add_display_client_accounts( $flags ){
    ?>
        <tr>
            <th><?php esc_html_e('Do you want to display it on account page?', 'wpcargo' ); ?></th>
            <td><input name="display_flags[]" value="account_page" type="checkbox"></td>
        </tr>
    <?php
}
add_action( 'wpc_cf_after_form_field_add', 'wpcargo_add_display_client_accounts' );
function wpcargo_edit_display_client_accounts( $flags ){
    ?>
        <tr>
            <th><?php esc_html_e('Do you want to display it on account page?', 'wpcargo' ); ?></th>
            <td><input name="display_flags[]" value="account_page" type="checkbox" <?php echo is_array($flags) && in_array( 'account_page', $flags) ? 'checked' : ''; ?> /></td>
        </tr>
    <?php
}
add_action( 'wpc_cf_after_form_field_edit', 'wpcargo_edit_display_client_accounts' );
add_action('wp_footer', function(){
    global $post;
    if ( is_a( $post, 'WP_Post' ) && ( has_shortcode( $post->post_content, 'wpcargo_account') || has_shortcode( $post->post_content, 'wpc-ca-account') ) ) {
		?>
		<!-- The Modal -->
		<div id="view-shipment-modal" class="wpcargo-modal">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<span class="close">&times;</span>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"></div>
			</div>
		</div>
		<?php
    }
});

// Plugin row Hook
add_filter( 'plugin_row_meta', 'wpcargo_plugin_row_meta', 10, 2 );
function wpcargo_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, 'wpcargo.php' ) !== false ) {
        $new_links = array(
            'settings' => '<a href="'.admin_url('admin.php?page=wpcargo-settings').'">'.esc_html__('Settings', 'wpcargo').'</a>',
            );
        $links = array_merge( $links, $new_links );
    }
    return $links;
}
add_action( 'quick_edit_custom_box', 'wpcargo_bulk_update_registered_shipper_custom_box', 10, 2 );
add_action( 'bulk_edit_custom_box', 'wpcargo_bulk_update_registered_shipper_custom_box', 10, 2 );
function wpcargo_bulk_update_registered_shipper_custom_box( $column_name,  $screen_post_type ){
    global $wpcargo;
    $shmap_active   = get_option('shmap_active');
    if( $screen_post_type == 'wpcargo_shipment'  ){
        wp_nonce_field( 'reg_shipper_bulk_update_action', 'reg_shipper_bulk_update_nonce' );
        $user_args = array(
            'meta_key' => 'first_name',
            'orderby'  => 'meta_value',
         );
        $all_users = get_users( $user_args );
        if( $column_name == 'registered_shipper' ){
            ?>
            <fieldset class="inline-edit-col-right">
                <div class="inline-edit-col">
                    <div class="inline-edit-group wp-clearfix">
                        <label class="inline-edit-registered_shipper">
                            <span class="title"><?php esc_html_e( 'Registered Shipper', 'wpcargo' ); ?></span>
                            <select name="registered_shipper">
                                <option value=""><?php esc_html_e( '— No Change —', 'wpcargo' ); ?></option>
                                <?php
                                foreach($all_users as $user){
                                    $user_fullname = apply_filters( 'wpcargo_registered_shipper_option_label', $wpcargo->user_fullname( $user->ID ), $user->ID );
                                    echo '<option value="'.trim($user->ID).'" >'.$user_fullname.'</option>';
                                }
                                ?>
                            </select>
                        </label>
                    </div>
                </div>
            </fieldset>
            <?php
        }
    }
}
function wpcargo_shipment_registered_shipper_custom_box_bulk_save( $post_id ) {
    global $wpcargo;
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if( !isset( $_REQUEST["reg_shipper_bulk_update_nonce"] ) ){
        return;
    }
    if ( !wp_verify_nonce( $_REQUEST["reg_shipper_bulk_update_nonce"], 'reg_shipper_bulk_update_action' ) ){
        return;
    }
    $current_user = wp_get_current_user();
    if ( isset( $_REQUEST['registered_shipper'] ) && $_REQUEST['registered_shipper'] != '' ) {
        update_post_meta( $post_id, 'registered_shipper', abs($_REQUEST['registered_shipper']) );
    }
}
add_action( 'save_post', 'wpcargo_shipment_registered_shipper_custom_box_bulk_save' );
// Run this hook when plugin is deactivated
function wpcargo_detect_plugin_deactivation(  $plugin, $network_activation ) {
    if( 'wpcargo-client-accounts-addons/wpcargo-client-accounts.php' == $plugin  ){
        add_role('wpcargo_client', 'WPCargo Client', array(
            'read' => true, // True allows that capability
        ));
    }
}
add_action( 'deactivated_plugin', 'wpcargo_detect_plugin_deactivation', 10, 2 );
// Shipment History Map
function wpcargo_shipment_history_map_callback( $shipment_id ){
	global $post, $wpcargo;
    $shmap_api          = get_option('shmap_api');
    $shmap_longitude    = !empty(get_option('shmap_longitude') ) ? get_option('shmap_longitude') : -87.65;
    $shmap_latitude     = !empty(get_option('shmap_latitude') )  ? get_option('shmap_latitude') : 41.85;
    $shmap_country_restrict      = get_option('shmap_country_restrict');
    $shmap_active       = get_option('shmap_active');
    $shmap_type         = get_option('shmap_type') ? get_option('shmap_type') : 'terrain' ;
    $shmap_zoom         = get_option('shmap_zoom') ? get_option('shmap_zoom') : 15 ;
    $maplabels          = apply_filters('wpcargo_map_labels', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890' );
    $history            = wpcargo_history_order( $wpcargo->history( $shipment_id ) );
    $shipment_addresses = array();
    $shipment_info      = array();
    if( !empty( $history ) ){
        foreach ( $history as $value ) {
            if( empty( $value['location'] ) ){
                continue;
            }
            $shipment_addresses[]   = $value['location'];
            $shipment_info[]        = $value;
        }
    }
    $addressLocations   = array_reverse( $shipment_addresses );
    $shipment_info      = array_reverse( $shipment_info );

    ?>
    <script>
        /*
        ** Google map Script Auto Complete address
        */
        var placeSearch, autocomplete, map, geocoder;
        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'
        };
        var labels = '<?php echo $maplabels; ?>';
        var labelIndex = 0;
        function wpcSHinitMap() {
            geocoder = new google.maps.Geocoder();
            getPlace_dynamic();
            var map = new google.maps.Map( document.getElementById('wpcargo-shmap'), {
                zoom: <?php echo $shmap_zoom; ?>,
                center: {lat: <?php echo $shmap_latitude; ?>, lng: <?php echo $shmap_longitude; ?>},
                mapTypeId: '<?php echo $shmap_type; ?>',
            });
            /*  Map script
            **  Initialize Shipment Locations
            */
            var shipmentAddress = <?php echo json_encode( $addressLocations ); ?>;
            var shipmentData    = <?php echo json_encode( $shipment_info ); ?>;

            var flightPlanCoordinates = [];
            var lastAddress           = false;
            var shipmentlength        = shipmentData.length - 1;
            for (var i = 0; i < shipmentAddress.length; i++ ) {
                if( i == shipmentlength ){
                    lastAddress = true;
                }
                codeAddress( geocoder, map, shipmentAddress[i], flightPlanCoordinates, i, shipmentData, lastAddress );
            }
            <?php do_action( 'wpc_after_init_map' ); ?>
        }
        function getPlace_dynamic() {
            var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(-33.8902, 151.1759),
                new google.maps.LatLng(-33.8474, 151.2631)
            );
            var input = document.getElementsByClassName('status_location');
            var options = {
                bounds: defaultBounds,
                types: ['geocode'],
                <?php if( !empty( $shmap_country_restrict ) ): ?>
                    componentRestrictions: {country: "<?php echo $shmap_country_restrict; ?>"}
                <?php endif; ?>
            };
            for (i = 0; i < input.length; i++) {
                autocomplete = new google.maps.places.Autocomplete(input[i], options);
            }
            <?php do_action( 'wpc_after_get_dynamic_place' ); ?>
        }
        function codeAddress( geocoder, map, address, flightPlanCoordinates, index, shipmentData, lastAddress ) {
            var wpclabelColor   = '<?php echo ( get_option('shmap_label_color') ) ? get_option('shmap_label_color') : '#fff' ;  ?>';
            var wpclabelSize    = '<?php echo ( get_option('shmap_label_size') ) ? get_option('shmap_label_size').'px' : '18px' ;  ?>';
            var wpcMapMarker    = '<?php echo ( get_option('shmap_marker') ) ? get_option('shmap_marker') : WPCARGO_PLUGIN_URL.'/admin/assets/images/wpcmarker.png' ;  ?>';
            var wpcCurrMarker   = '<?php echo apply_filters( 'shmap_current_marker_url', WPCARGO_PLUGIN_URL.'/admin/assets/images/current-map.png' );  ?>';
            geocoder.geocode({'address': address}, function(results, status) {
                if (status === 'OK') {
                    var geolatlng = { lat: results[0].geometry.location.lat(),  lng: results[0].geometry.location.lng() };
                    var mapLabel  = {text: labels[index % labels.length], color: wpclabelColor, fontSize: wpclabelSize };
                    flightPlanCoordinates[index] = geolatlng;
                    if( lastAddress === true ){
                        map.setCenter( geolatlng );
                        wpcMapMarker  = wpcCurrMarker;
                        mapLabel      = '';
                    }
                    var marker = new google.maps.Marker({
                        map: map,
                        label: mapLabel,
                        position: results[0].geometry.location,
                        icon: wpcMapMarker
                    });

                    /*
                    ** Marker Information window
                    */
                    // shipmentData
                    var sAddressDate = shipmentData[index].date;
                    var sAddresstime = shipmentData[index].time;
                    var sAddresslocation = shipmentData[index].location;
                    var sAddressstatus = shipmentData[index].status;
                    var shipemtnInfo = '<strong><?php esc_html_e('Date', 'wpcargo'); ?>:</strong> '+sAddressDate+' '+sAddresstime+'</br>'+
                                        '<strong><?php esc_html_e('Location', 'wpcargo'); ?>:</strong> '+sAddresslocation+'</br>'+
                                        '<strong><?php esc_html_e('Status', 'wpcargo'); ?>:</strong> '+sAddressstatus;
                    var infowindow = new google.maps.InfoWindow({
                        content: shipemtnInfo
                    });
                    marker.addListener('click', function() {
                        infowindow.open(map, marker);
                    });
                }
            });
        }
    </script>
    <?php
    echo wpcargo_map_script( 'wpcSHinitMap' );
}
function wpcargo_track_shipment_status_result( $shimpment_details ){
		$shipment_status = get_post_meta( $shimpment_details->ID, 'wpcargo_status', true );
		?>
		<div id="shipment-status" class="wpcargo-row" style="text-align:center;">
			<p id="result-status-header"><?php echo apply_filters( 'wpcargo_track_shipment_status_result_title', esc_html__( 'Shipment Status: ', 'wpcargo' ) ); ?><?php echo $shipment_status; ?></p>
		</div>
		<?php
}
add_action( 'wpcargo_before_shipment_details', 'wpcargo_track_shipment_status_result', 10, 1 );
// Print Invoice hook callback
function wpcargo_before_invoice_font_content_callback(){
    $print_fonts 		= wpcargo_print_fonts();
    $ffamily 			= get_option('wpcargo_print_ffamily');
    $fsize 				= get_option('wpcargo_print_fsize') ? get_option('wpcargo_print_fsize') : 12 ;
    ?>
    <style type="text/css">
        <?php if( $ffamily && array_key_exists( $ffamily, $print_fonts ) ): ?>
            @import url('<?php echo $print_fonts[$ffamily]['url']; ?>');
            #wpcargo-result-print *{
                font-family: <?php echo $print_fonts[$ffamily]['fontfamily']; ?> !important;
                font-size: <?php echo $fsize; ?>px;
            } 
            @media print{
                @import url('<?php echo $print_fonts[$ffamily]['url']; ?>');
                *{
                    font-family: <?php echo $print_fonts[$ffamily]['fontfamily']; ?> !important;
                    font-size: <?php echo $fsize; ?>px;
                } 
            }
        <?php endif; ?>	
    </style>
    <?php
}
function wpcargo_before_invoice_content_callback(){
    ?>
    <style>
        table{
            border-collapse: collapse;
        }
        table td{
            vertical-align:top;
            padding: 8px;
        }
        table td *{
            margin:0;
            padding:0;
        }
        table#package-table td,
        table#package-table th{
            border:1px solid #000;
            padding: 6px;
            font-size: 12px;
        }
        table#package-table th{
            white-space:nowrap;
        }
        .border-bottom{
            border-bottom: 1px solid #000;
        }
        .space-topbottom{
            padding-top:18px;
            padding-bottom:18px;
        }
        img#log{
            width:50% !important;
        }
        h2, h1{
            font-size: 14px;
        }
        p{
            font-size:12px;
        }
    </style>
    <?php
}
function wpcargo_invoice_site_info_callback( $shipmentDetails ){
    ?>
    <section style="text-align:<?php echo is_rtl() ? 'right' : 'left'; ?>"><?php echo $shipmentDetails['siteInfo']; ?></section>
    <?php
}
function wpcargo_invoice_shipper_info_callback( $shipmentDetails ){
    global $WPCCF_Fields, $wpcargo;
    ?>
    <p style="font-size:1.2rem;margin-bottom:18px;"><strong><?php esc_html_e('SHIPPER DETAILS:', 'wpcargo-frontend-manager'); ?></strong></p>
    <?php
    if( class_exists( 'WPCCF_Fields' ) ){
        echo $WPCCF_Fields->get_fields_data( 'shipper_info', $shipmentDetails['shipmentID']);
    }else{
        ?>
        <p><?php esc_html_e( 'Shipper Name', 'wpcargo'); ?>: <?php echo get_post_meta( $shipmentDetails['shipmentID'], 'wpcargo_shipper_name', true ); ?></p>
        <p><?php esc_html_e( 'Phone Number', 'wpcargo'); ?>: <?php echo get_post_meta( $shipmentDetails['shipmentID'], 'wpcargo_shipper_phone', true ); ?></p>
        <p><?php esc_html_e( 'Address', 'wpcargo'); ?>: <?php echo get_post_meta( $shipmentDetails['shipmentID'], 'wpcargo_shipper_address', true ); ?></p>
        <p><?php esc_html_e( 'Email', 'wpcargo'); ?>: <?php echo get_post_meta( $shipmentDetails['shipmentID'], 'wpcargo_shipper_email', true ); ?></p>
        <?php
    }
    
}
function wpcargo_invoice_receiver_info_callback( $shipmentDetails ){
    global $WPCCF_Fields, $wpcargo;
    ?>
    <p style="font-size:1.2rem;margin-bottom:18px;"><strong><?php esc_html_e('RECEIVER DETAILS:', 'wpcargo-frontend-manager'); ?></strong></p>
    <section id="section-to">
    <?php 
        if( class_exists( 'WPCCF_Fields' ) ){
            echo $WPCCF_Fields->get_fields_data( 'receiver_info', $shipmentDetails['shipmentID']);
        }else{
            ?>
            <p><?php esc_html_e( 'Receiver Name', 'wpcargo'); ?>: <?php echo get_post_meta( $shipmentDetails['shipmentID'], 'wpcargo_receiver_name', true ); ?></p>
            <p><?php esc_html_e( 'Phone Number', 'wpcargo'); ?>: <?php echo get_post_meta( $shipmentDetails['shipmentID'], 'wpcargo_receiver_phone', true ); ?></p>
            <p><?php esc_html_e( 'Address', 'wpcargo'); ?>: <?php echo get_post_meta( $shipmentDetails['shipmentID'], 'wpcargo_receiver_address', true ); ?></p>
            <p><?php esc_html_e( 'Email', 'wpcargo'); ?>: <?php echo get_post_meta( $shipmentDetails['shipmentID'], 'wpcargo_receiver_email', true ); ?></p>
            <?php
        }
    ?>
    </section>
    <?php
}
function wpcargo_invoice_barcode_info_callback( $shipmentDetails ){
    global $wpcargo;
    $barcode_height   = wpcargo_print_barcode_sizes()['invoice']['height']? : 60;
    $barcode_width    = wpcargo_print_barcode_sizes()['invoice']['width']? : 200;
    ?>
    <section style="text-align:center;" >
        <img id="admin-invoice-barcode" class="invoice-barcode" style="height: <?php echo absint($barcode_height).'px'; ?>; width: <?php echo absint($barcode_width).'px'; ?>" src="<?php echo $wpcargo->barcode_url( $shipmentDetails['shipmentID'] ); ?>">
        <p style="font-size:18px;"><?php echo get_the_title( $shipmentDetails['shipmentID']); ?><p>
    </section>
    <?php
}
function wpcargo_end_invoice_section_callback( $shipmentDetails ){
    if( empty(wpcargo_get_package_data( $shipmentDetails['shipmentID'] ))){
        return false;
    }
    if( !wpcargo_package_settings()->frontend_enable ){
        return false;
    }
    ?>
    <tr>
        <td colspan="2">
            <p style="font-size:1.2rem;margin-bottom:18px;"><strong><?php esc_html_e('PACKAGE DETAILS:', 'wpcargo-frontend-manager'); ?></strong></p>
            <table id="package-table" style="width:100%;">
                <thead>
                    <tr>
                        <?php foreach ( wpcargo_package_fields() as $key => $value): ?>
                            <?php  if( in_array( $key, wpcargo_package_dim_meta() ) && !wpcargo_package_settings()->dim_unit_enable ){ continue; }
                            ?>
                            <th><?php echo $value['label']; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( wpcargo_get_package_data( $shipmentDetails['shipmentID'] ) as $data_key => $data_value): ?>
                    <tr>
                        <?php foreach ( wpcargo_package_fields() as $field_key => $field_value): ?>
                            <?php if( in_array( $field_key, wpcargo_package_dim_meta() ) && !wpcargo_package_settings()->dim_unit_enable ){ continue; } ?>
                            <td>
                                <?php 
                                    $package_data = array_key_exists( $field_key, $data_value ) ? $data_value[$field_key] : '' ;
                                    echo is_array( $package_data ) ? implode(',', $package_data ) : $package_data; 
                                ?>

                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </td>
    </tr>
    <?php
}