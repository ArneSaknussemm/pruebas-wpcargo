<?php
class WPCargo{
	public $status;
	public $settings;
	public $logo;
	public $mail_status;
	public $admin_mail_status;
	public $mail_cc;
	public $mail_bcc;
	public $client_mail_subject;
	public $admin_mail_subject;
	public $client_mail_to;
	public $admin_mail_to;
	public $client_mail_settings;
	public $client_mail_active;
	public $admin_mail_active;
	public $client_mail_body;
	public $admin_mail_body;
	public $client_mail_footer;
	public $admin_mail_footer;
	public $agents;
	public $prefix;
	public $suffix;
	public $users;
	public $time_format;
	public $date_format;
	public $autogenerate_title;
	public $tax;
	public $number_digit;
	public $barcode_type;
	public $barcode_size;
	function __construct( ){
		$this->status 		= $this->status();
		$this->settings 	= $this->settings();
		$this->logo 		= $this->logo();
		$this->mail_status 	= $this->mail_status();
		$this->admin_mail_status 	= $this->admin_mail_status();
		$this->mail_cc 				= $this->mail_cc();
		$this->mail_bcc 			= $this->mail_bcc();
		$this->client_mail_subject 	= $this->client_mail_subject();
		$this->admin_mail_subject 	= $this->admin_mail_subject();
		$this->client_mail_to 		= $this->client_mail_to();
		$this->admin_mail_to 		= $this->admin_mail_to();
		$this->client_mail_settings = $this->client_mail_settings();
		$this->client_mail_active 	= $this->client_mail_active();
		$this->admin_mail_active 	= $this->admin_mail_active();
		$this->client_mail_body 	= $this->client_mail_body();
		$this->admin_mail_body 		= $this->admin_mail_body();
		$this->client_mail_footer 	= $this->client_mail_footer();
		$this->admin_mail_footer 	= $this->admin_mail_footer();
		$this->agents  		= $this->agents();
		$this->prefix  		= $this->prefix();
		$this->suffix  		= $this->suffix();
		$this->users 		= $this->all_wpcargo_users();
		$this->time_format 	= $this->time_format();
		$this->date_format 	= $this->date_format();
		$this->datetime_format 	= $this->datetime_format();
		$this->tax 			= $this->tax();
		$this->number_digit = $this->number_digit();
		$this->barcode_type = $this->barcode_type();
		$this->barcode_size = $this->barcode_size();
		$this->autogenerate_title();
	}
	/*
	** Public Functions
	*/
	public function history( $shipment_id ){
		$history =  maybe_unserialize( get_post_meta( $shipment_id, 'wpcargo_shipments_update', true ) );
		if( !is_array( $history ) ){
			return array();
		}
		return $history;
	}
	public function barcode_type( ){
		return apply_filters( 'wpcargo_barcode_type', 'code128' );
	}
	public function barcode_size( ){
		return apply_filters( 'wpcargo_barcode_size', 60 );
	}
	public function barcode( $shipment_id, $html = false, $width = 180, $height = 50 ){
		$barcode 		= $this->barcode_url( $shipment_id );
		if( $html ){
			$barcode 	= '<img style="width: '.$width.'px;height: '.$height.'px;" src="'.$barcode.'" alt="'.get_the_title( $shipment_id ).'" />';
		}
		return $barcode;
	}
	public function barcode_url( $shipment_id, $barcode_size = '', $barcode_type = '', $orientation = '' ){
		$barcode_type 		= empty( $size ) ? $this->barcode_type : $barcode_type ;
		$barcode_size 		= empty( $size ) ? $this->barcode_size : $barcode_size;
		$orientation 		= empty( $orientation ) ? 'horizontal' : $orientation;
		$shipment_number 	= get_the_title( $shipment_id );
		$barcode_url 		= WPCARGO_PLUGIN_URL."includes/barcode.php?orientation=".$orientation."&codetype=".$barcode_type."&size=".$barcode_size."&text=".$shipment_number;
		return $barcode_url;
	}
	/*
	** Protected Functions
	*/
	protected function status(){
		$status 					= wpcargo_default_status();
		$wpcargo_option_settings 	= $this->settings();
		if( $wpcargo_option_settings ){
			if( array_key_exists( 'settings_shipment_status', $wpcargo_option_settings)){
				$get_all_status 	= trim( $wpcargo_option_settings['settings_shipment_status'] );
				if( $get_all_status ){
					$status = array_map( 'trim', explode(",", $get_all_status) );
				}	
			}
		}
		return array_filter( apply_filters( 'wpcargo_status_option', $status  ) );
	}
	protected function settings(){
		return ( get_option('wpcargo_option_settings') ) ? get_option('wpcargo_option_settings') : array();
	}
	protected function logo(){
		$wpcargo_option_settings 	= $this->settings();
		$logo = '';
		if( $wpcargo_option_settings ){
			if( array_key_exists( 'settings_shipment_ship_logo', $wpcargo_option_settings)){
				$logo 	= $wpcargo_option_settings['settings_shipment_ship_logo'];
			}
		}
		return $logo;
	}
	protected function mail_status(){
		$status 		= array();
		$mail_status 	=  get_option('wpcargo_mail_status');
		if( $mail_status ){
			$status = $mail_status;
		}
		return $status;
	}
	protected function admin_mail_status(){
		$status 		= array();
		$mail_status 	=  get_option('wpcargo_admin_mail_status');
		if( $mail_status ){
			$status = $mail_status;
		}
		return $status;
	}
	protected function mail_cc(){
		return get_option('wpcargo_email_cc');
	}
	protected function mail_bcc(){
		return get_option('wpcargo_email_bcc');
	}
	protected function client_mail_settings(){
		return get_option('wpcargo_mail_settings');
	}
	protected function client_mail_active(){
		$mail_active = false;
		$wpcargo_mail_settings = $this->client_mail_settings();
		if( !empty( $wpcargo_mail_settings ) && array_key_exists( 'wpcargo_active_mail', $wpcargo_mail_settings ) ){
			$mail_active =  true;
		}
		return $mail_active;
	}
	protected function admin_mail_active(){
		return get_option('wpcargo_admin_mail_active');
	}
	protected function client_mail_subject(){
		$subject = '';
		$settings = $this->client_mail_settings();
		if( !empty( $settings ) && array_key_exists( 'wpcargo_mail_subject', $settings ) ){
			$subject =  $settings['wpcargo_mail_subject'];
		}
		return $subject;
	}
	protected function admin_mail_subject(){
		$subject = esc_html__('Shipment Notification', 'wpcargo' );
		if( !empty( trim( get_option( 'wpcargo_admin_mail_subject' ) ) ) ){
			$subject = get_option( 'wpcargo_admin_mail_subject' );
		}
		return $subject;
	}
	protected function client_mail_to(){
		$mail_to = '';
		$settings = $this->client_mail_settings();
		if( !empty( $settings ) && array_key_exists( 'wpcargo_mail_to', $settings ) ){
			$mail_to =  $settings['wpcargo_mail_to'];
		}
		return $mail_to;
	}
	protected function admin_mail_to(){
		$mail_to = '';
		if( !empty( trim( get_option('wpcargo_admin_mail_to') ) ) ){
			$mail_to =  get_option('wpcargo_admin_mail_to');
		}
		return $mail_to;
	}
	protected function client_mail_body(){
		$mail_body = wpcargo_default_client_email_body();
		$wpcargo_mail_settings = $this->client_mail_settings();
		if( !empty( $wpcargo_mail_settings ) && array_key_exists( 'wpcargo_mail_message', $wpcargo_mail_settings ) && !empty( trim( $wpcargo_mail_settings['wpcargo_mail_message'] ) ) ){
			$mail_body =  $wpcargo_mail_settings['wpcargo_mail_message'];
		}
		return $mail_body;
	}
	protected function admin_mail_body(){
		$mail_body = wpcargo_default_admin_email_body();
		if( !empty( trim( get_option('wpcargo_admin_mail_body') ) ) ){
			$mail_body =  get_option('wpcargo_admin_mail_body');
		}
		return $mail_body;
	}
	protected function client_mail_footer(){
		$mail_footer = wpcargo_default_email_footer();
		$wpcargo_mail_settings = $this->client_mail_settings();
		if( !empty( $wpcargo_mail_settings ) && array_key_exists( 'wpcargo_mail_footer', $wpcargo_mail_settings ) && !empty( trim( $wpcargo_mail_settings['wpcargo_mail_footer'] ) ) ){
			$mail_footer =  $wpcargo_mail_settings['wpcargo_mail_footer'];
		}
		return $mail_footer;
	}
	protected function admin_mail_footer(){
		$mail_footer = wpcargo_default_email_footer();
		if( !empty( trim( get_option( 'wpcargo_admin_mail_footer' ) ) ) ){
			$mail_footer = get_option( 'wpcargo_admin_mail_footer' );
		}
		return $mail_footer;
	}
	public function user_time( $userID ){
		global $wpdb, $user;
		$time = current_time( $this->time_format() );
		if( get_option('wpcargo_user_timezone') ){
			$timezone = get_user_meta( $userID, 'wpc_user_timezone', true );
			if( $timezone ){
				$findme = 'UTC';
				$result = stripos( $timezone, $findme );
				if( $result === false ){
					date_default_timezone_set($timezone);
					date_default_timezone_get();
					$time = current_time( $this->time_format() );
					date_default_timezone_set('UTC');
				}
			}
		}
		return $time;
	}
	public function user_date( $userID ){
		$date = current_time( $this->date_format() );
		if( get_option('wpcargo_user_timezone') ){
			$timezone = get_user_meta( $userID, 'wpc_user_timezone', true );
			if( $timezone ){
				$findme = 'UTC';
				$result = stripos( $timezone, $findme );
				if( $result === false ){
					date_default_timezone_set($timezone);
					date_default_timezone_get();
					$date = current_time( $this->date_format() );
					date_default_timezone_set('UTC');
				}
			}
		}
		return $date;
	}
	function agents(){
		$users = array();
		$args = array(
		'role__in'     => array('cargo_agent'),
		 );
		$agents = get_users( $args );
		if( !empty($agents) ){
			foreach ($agents as $user ) {
				$user_fullname = $user->display_name;
				if( !empty( $user->first_name ) && !empty( $user->last_name ) ){
					$user_fullname = $user->first_name.' '.$user->last_name;
				}
				$users[$user->ID] = $user_fullname;
			}
		}
		return $users;
	}
	function get_shipment_agent( $shipmentID ){
		$agent = get_post_meta( $shipmentID, 'agent_fields', true );
		if( !is_numeric( $agent ) ){
			$agent = $this->agent_id( 'display_name', $agent );
		}
		return $agent;
	}
	function agent_display_name( $userID ){
		global $wpdb;
		$table_prefix = $wpdb->prefix;
		$display_name = $userID;
		if( is_numeric( $userID  ) ) {
			$query = 'SELECT `display_name` FROM `'.$table_prefix.'users` WHERE `ID` = %d';
			$display_name =  $wpdb->get_var( $wpdb->prepare( $query, $userID ) );
		}
		return $display_name;
	}
	function user_fullname( $userID ){
		$user_fullname = '';
		$user = get_userdata( (int)$userID );
		if( !empty($user) ){
			$user_fullname = $user->display_name;
			if( !empty( $user->first_name ) && !empty( $user->last_name ) ){
				$user_fullname = $user->first_name.' '.$user->last_name;
			}
		}
		return $user_fullname;
	}
	function agent_id( $field = 'display_name', $value ){
		global $wpdb;
		$table_prefix = $wpdb->prefix;
		$query = 'SELECT `ID` FROM `'.$table_prefix.'users` WHERE `'.$field.'` LIKE %s';
		$display_name =  $wpdb->get_var( $wpdb->prepare( $query, $value ) );
		return $display_name;
	}
	function time_format(){
		$time_format = apply_filters( 'wpcargo_time_format', 'H:i a' );
		return $time_format;
	}
	function date_format(){
		$date_format = apply_filters( 'wpcargo_date_format', 'Y-m-d' );
		return $date_format;
	}
	function datetime_format(){
		$datetime_format = apply_filters( 'wpcargo_datetime_format', 'Y-m-d H:i a' );
		return $datetime_format;
	}
	protected function all_wpcargo_users( ){
		$wpcargo_args 	= apply_filters( 'all_wpcargo_users', array(
			'role__in'     => wpcargo_user_roles_list()
		) );
		$all_wpcargo_users = get_users( $wpcargo_args );
		return $all_wpcargo_users;
	}
	protected function prefix(){
		$options 	= $this->settings();
		$prefix 	= '';
		if( array_key_exists( 'wpcargo_title_prefix', $options ) ){
	        $prefix = trim( $options['wpcargo_title_prefix'] );
	    }
	    return apply_filters( 'wpcargo_prefix', $prefix );
	}
	protected function suffix(){
		$suffix = get_option('wpcargo_title_suffix');
		return apply_filters( 'wpcargo_suffix', $suffix );
	}
	protected function tax(){
		$options = $this->settings();
		$tax 	 = 0;
		if( array_key_exists( 'wpcargo_tax', $options ) ){
	        $tax = floatval( $options['wpcargo_tax'] );
	    }
	    return $tax;
	}
	protected function autogenerate_title(){
		$options 	= $this->settings();
		$autogenerate 	= false;
		if( array_key_exists( 'wpcargo_title_prefix_action', $options ) ){
	        $autogenerate = true;
	    }
	    $this->autogenerate_title = $autogenerate;
	}
	protected function number_digit(){
		return ( get_option('wpcargo_title_numdigit') ) ? get_option('wpcargo_title_numdigit') : 12 ;
	}
	public function create_shipment_number(){
    	global $wpdb;
		$numdigit  	= $this->number_digit;
		$numstr = '';
		for ( $i = 1; $i < $numdigit; $i++ ) {
			$numstr .= 9;
		}
	    $shipment_title = $this->prefix.str_pad( wp_rand( 0, $numstr ), $numdigit, "0", STR_PAD_LEFT ).$this->suffix;
		if( get_option('wpcargo_restrict_duplicate') ){
			if( $this->is_title_exist($shipment_title) ){
				return $this->create_shipment_number();
			}
		}
	    return $shipment_title;
	}
	public function is_title_exist( $title = '' ){
		global $wpdb;
		$sql 	= $wpdb->prepare( "SELECT COUNT(*) FROM `{$wpdb->prefix}posts` WHERE `post_type` LIKE 'wpcargo_shipment' AND `post_status` IN ('publish', 'pending', 'draft') AND `post_title` LIKE %s", $title );
		$result =  $wpdb->get_var( $sql );
		return $result;
	}
}
$wpcargo = new WPCargo();