<?php
/**
 * Enqueue child styles.
 */
function child_enqueue_styles() {
	wp_enqueue_style( 'child-theme', get_stylesheet_directory_uri() . '/style.css', array(), 100 );
}

// add_action( 'wp_enqueue_scripts', 'child_enqueue_styles' ); // Remove the // from the beginning of this line if you want the child theme style.css file to load on the front end of your site.

/**
 * Add custom functions here
 */

add_action( 'after_setup_theme','reactiva_acceso_admin', 0 );
function reactiva_acceso_admin()
{
	//Otorga permisos más amplios a las páginas de factura y etiqueta de envío
	global $wpcargo_print_admin;
	remove_action( 'admin_menu',  array( $wpcargo_print_admin, 'wpcargo_print_register_submenu_page' ) );
	add_action('admin_menu', 'registra_submenu_botones_imprimir' );
	//Devuelve los permisos para acceder al panel de administración
	global $wpcargo_metabox;
	remove_action( 'admin_init',  array( $wpcargo_metabox, 'wpc_blockusers_init' ) );
	remove_action( 'after_setup_theme', array( $wpcargo_metabox, 'wpc_remove_admin_bar' ));

}

function registra_submenu_botones_imprimir() {
	global $wpcargo_print_admin;
	add_submenu_page(
		NULL,
		// wpcargo_print_layout_label(),  //DEFINIDA EN /wpcargo/admin/includes/functions.php
		// wpcargo_print_layout_label(),
		apply_filters('wpcargo_print_layout_label', esc_html__('Print Layout', 'wpcargo' ) ),
		apply_filters('wpcargo_print_layout_label', esc_html__('Print Layout', 'wpcargo' ) ),
		'read',
		'wpcargo-print-layout',
		array( $wpcargo_print_admin, 'wpcargo_print_submenu_page_callback' )
	);
	add_submenu_page(
		NULL,
		// wpcargo_shipment_label(), //DEFINIDA EN /wpcargo/admin/includes/functions.php
		// wpcargo_shipment_label(),
		apply_filters('wpcargo_shipment_label', esc_html__('Shipment Label', 'wpcargo' ) ),
		apply_filters('wpcargo_shipment_label', esc_html__('Shipment Label', 'wpcargo' ) ),
		'read',
		'wpcargo-print-label',
		array( $wpcargo_print_admin, 'wpcargo_print_label_page_callback' )
	);
}

add_filter( 'register_post_type_args', 'wpc_cambia_permisos' , 10, 2 );
function wpc_cambia_permisos( $args, $post_type )
{
	if ( 'wpcargo_shipment' !== $post_type ) return $args;
	$args['capability_type'] = array('desapcho', 'despachos');
	$args['map_meta_cap'] = true;
	return $args;
}

add_action('wp_head', 'infoarriba');
function infoarriba()
{
	$roles_autorizados = ['wpcargo_employee', 'revisor', 'subscriber'];
	echo var_export(wp_get_current_user()->roles);echo '<br>';
	//echo var_export(array_intersect( $roles_autorizados, wp_get_current_user()->roles ));echo '<br>';
	echo var_export(!empty(array_intersect( $roles_autorizados, wp_get_current_user()->roles )));echo '<br>';

}
