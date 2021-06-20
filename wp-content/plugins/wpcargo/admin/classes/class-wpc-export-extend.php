<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once( WPCARGO_PLUGIN_PATH.'admin/classes/class-wpc-export.php');
class WPC_Export_Admin extends WPC_Export{
	protected $post_type 		= 'wpcargo_shipment';
	protected $post_taxonomy 	= 'wpcargo_shipment_cat';
	function __construct(){
		add_action('admin_menu', array($this,'wpc_import_export_submenu_page') );
		add_action( 'wp_ajax_update_import_option_ajax_request',  array($this,'update_import_option_ajax_request') );
		add_action( 'wp_ajax_search_shipper',  array($this,'wpc_import_export_search_shipper') );
	}
	function wpc_import_export_submenu_page() {
		//** Import Submenu
		add_submenu_page(
			'edit.php?post_type=wpcargo_shipment',
			wpcargo_report_settings_label(),
			wpcargo_report_settings_label(),
			'manage_options',
			'wpc-report-export',
			array($this,'wpc_import_export_submenu_page_callback') );
		//** Exmport Submenu
		add_submenu_page(
			NULL,
			wpcargo_report_settings_label(),
			wpcargo_report_settings_label(),
			'manage_options',
			'wpc-ie-import',
			array($this,'wpc_import_export_submenu_page_callback') );
	}
	function wpc_import_export_submenu_page_callback() {
		global $wpdb;
		$table_name = $wpdb->prefix.'wpcargo_custom_fields';
		$field_selection = $this->form_fields();
		$page = $_GET['page'];
		$tax_args       = array(
			'orderby' => 'name',
			'order' => 'ASC',
			'taxonomy' => $this->post_taxonomy,
			'hide_empty' => 0
		);
		$cat_taxonomy = get_categories($tax_args);
		ob_start();
		?>
		<div class="wrap"><div id="icon-tools" class="icon32"></div>
            <?php $this->wpc_ie_header_tab();  ?>
            <div style="clear: both;"></div>
            <div id="form-block">
            	<?php
					if( $page == 'wpc-report-export' ){
						$this->wpc_export_form( $field_selection, $cat_taxonomy, $page);
					}
				?>
            </div>
            <div id="ads">
		    	<a href="http://www.wpcargo.com/product/wpcargo-importexport-add-ons/" target="_blank" class="wpc-documentation">
				    <div class="wpc-img"> <img src="<?php echo WPCARGO_PLUGIN_URL; ?>/admin/assets/images/documentation.png"> </div>
				    <div class="wpc-desc">
				      <h3><?php esc_html_e('Purchase', 'wpcargo'); ?> WPCargo Import Export Add-ons</h3>
				      <p><?php esc_html_e('If you want a more comprehensive and customizable report, purchase', 'wpcargo'); ?> WPCargo Import Export Add-ons.</p>
				    </div>
				</a>
		    </div>
		</div>
        <?php
		echo ob_get_clean();
	}
	function update_import_option_ajax_request() {
		// The $_REQUEST contains all the data sent via ajax
		if ( isset($_REQUEST) ) {
			update_option('multiselect_settings', $_REQUEST['multiselect_settings'], true);
		}
		// Always die in functions echoing ajax content
	   die();
	}
	function wpc_export_form( $fields = array(), $taxonomy = array(), $page ='') {
		add_action( 'wp_ajax_update_import_option_ajax_request',  'update_import_option_ajax_request' );
		global $wpcargo;
		$options 					= get_option( 'multiselect_settings' );
		if( !empty( $options ) ){
			if( array_key_exists( 0, $options ) ){
				$options = array();
			}
		}
		$user_args = array(
						'meta_key' => 'first_name',
						'orderby'  => 'meta_value',
						'role__in' => array( 'wpcargo_client' ),
					);
		$users = get_users( $user_args );
		$registered_shipper = isset( $_REQUEST['shipment_author'] ) ? (int)$_REQUEST['shipment_author'] : '';
			include_once( wpcargo_include_template( 'report.tpl' ) );
		?>        
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#multi-select-export button.btn.btn-block').on('click', function(e){
				e.preventDefault();
				$("#multiselect_to, #multiselect").trigger('change');
			});
			$('#multiselect').multiselect({
				sort: false,
				autoSort: false,
				autoSortAvailable: false,
			});
			$("#multiselect_to, #multiselect").on('change',function() {
				setTimeout(function(){
					var selectoptions= {};
					$.each($("#multiselect_to option"), function( ) {
						var metaKey = $(this).attr("value");
						var metaValue = $(this).text();
						selectoptions[metaKey] = metaValue;
					});
					jQuery.ajax({
						url : 'admin-ajax.php',
						type : 'post',
						data : {
							action : 'update_import_option_ajax_request',
							multiselect_settings: selectoptions
						},
						success : function( response ) {
							//alert(response)
						}
					});
				}, 1000);
			});
		});
		</script>
	    <?php
	}
	function wpc_import_export_search_shipper(){
		global $wpdb, $post;
		// Handle request then generate response using WP_Ajax_Response
		$term = $_GET['term'];
		$shipper_name_metakey = apply_filters( 'wpc_report_search_shipper_name_metakey', 'wpcargo_shipper_name' );
		$wpc_get_fields = $wpdb->get_results("SELECT tbl2.meta_value AS meta_value FROM `$wpdb->posts` AS tbl1 INNER JOIN `$wpdb->postmeta` AS tbl2 ON tbl1.ID = tbl2.post_id WHERE tbl1.post_type LIKE 'wpcargo_shipment' AND tbl2.meta_key LIKE '".$shipper_name_metakey."' AND tbl2.meta_value LIKE '%".$term."%' GROup BY meta_value");
		if( !empty($wpc_get_fields) ){
			foreach( $wpc_get_fields as $shipper ){
				$suggestions[] = array(
					'label'	=> $shipper->meta_value,
				);
			}
		}
		$response = wp_send_json( $suggestions );
		echo $response;
		die();
	}
	function wpc_ie_header_tab(){
		$view = $_GET['page'];
		?>
		<div class="wpc-ie-tab">
			<h2 class="nav-tab-wrapper">
            <a href="<?php echo admin_url( 'edit.php?post_type=wpcargo_shipment&page=wpc-report-export' );?>" class="nav-tab<?php if($view == 'wpc-report-export') { ?> nav-tab-active<?php } ?>"><?php esc_html_e("Shipment Reports", 'wpcargo'); ?> </a>
			</h2>
		</div>
		<?php
		if( $view == 'wpc-report-export' ){
			$this->wpc_export_request( );
		}
	}
}
$wpc_export_admin = new WPC_Export_Admin();