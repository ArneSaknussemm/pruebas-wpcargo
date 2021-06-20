<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function wpcargo_default_logmeta(){
    global $wpcargo;
    $user_agent     = $_SERVER['HTTP_USER_AGENT'];
    $ip_address     = $_SERVER['SERVER_ADDR'];
    $current_user   = wp_get_current_user();
    $user_id        = $current_user->ID;
    $user_fullname  = $wpcargo->user_fullname( $user_id );
    $defualt_meta = array(
        array(
            'metakey'   => 'USERID',
            'value'     => $user_id
        ),
        array(
            'metakey'   => 'USERFULLNAME',
            'value'     => $user_fullname
        ),
        array(
            'metakey'   => 'HTTP_USER_AGENT',
            'value'     => $user_agent
        ),
        array(
            'metakey'   => 'SERVER_ADDR',
            'value'     => $ip_address
        )
    );
    return $defualt_meta;
}
/**
 *
 * @param array data
 * @param Integer log_id
 * @param String metakey
 * @param String value
 */
function wpcargo_generate_log( $shipment_id ){
    global $wpdb;
    if( !(int)$shipment_id ){
        return false;
    }
    $logs_table     = $wpdb->prefix.WPCARGO_DB_LOG;
    // Insert Data
    $wpdb->insert( 
        $logs_table, 
        array( 'shipment_id'   => $shipment_id ), 
        array( '%d' ) 
    );
    return $wpdb->insert_id;
}
/**
 *
 * @param array data
 * @param Integer log_id
 * @param String metakey
 * @param String value
 */
function wpcargo_generate_logmeta( $log_id, $data ){
    global $wpcargo, $wpdb;
    $logmeta_table  = $wpdb->prefix.WPCARGO_DB_LOGMETA;
    $default_meta   = wpcargo_default_logmeta();
    // Insert Data
    $wpdb->insert( 
        $logmeta_table, 
        array( 
            'log_id'        => $log_id, 
            'metakey'       => $data['metakey'], 
            'value'         => $data['value'], 
        ),
        array( '%d', '%s', '%s' ) 
    );
    $log_id = $wpdb->insert_id;
    // if( $log_id ){
    //     foreach ($default_meta as $meta_data ) {
    //         wpcargo_generate_logmeta( $log_id, $meta_data );
    //     }
    // }
    return $log_id;
}
// Hooks
function wpcargo_get_before_post_edit_data( $post_id ){
    $post_id = (int) $post_id; // Making sure that the post id is integer.
    $post    = get_post( $post_id ); // Get post.
    if ( ! empty( $post ) && $post instanceof WP_Post ) {
        $log_id = wpcargo_generate_log( $post_id );
        if( $log_id ){
            wpcargo_generate_logmeta( $log_id, array(
                'metakey'   => 'ACTION',
                'value'     => 'before_post_edit_data'
            ) );
        }
    }
}
function wpcargo_post_changed( $post_id, $post, $update ){
    // Ignore if post type is empty, revision or trash.
    if ( empty( $post->post_type ) || 'revision' === $post->post_type || 'trash' === $post->post_status ) {
        return;
    }

    // Ignore updates from ignored custom post types.
    if ( $post->post_type !== 'wpcargo_shipment' ) {
        return;
    }

    // Ignorable states.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // $log_id = wpcargo_generate_log( $post_id );
    // if( $log_id ){
    //     wpcargo_generate_logmeta( $log_id, array(
    //         'metakey'   => 'ACTION',
    //         'value'     => 'post_changed'
    //     ) );
    // }
}
function wpcargo_event_post_deleted( $post_id ) {
    // Exclude CPTs from external plugins.
    $post = get_post( $post_id );
    // Ignore updates from ignored custom post types.
    if ( $post->post_type !== 'wpcargo_shipment' ) {
        return;
    }

    if ( 'auto-draft' === $title || 'Auto Draft' === $title ) {
        return;
    }

    // $log_id = wpcargo_generate_log( $post_id );
    // if( $log_id ){
    //     wpcargo_generate_logmeta( $log_id, array(
    //         'metakey'   => 'ACTION',
    //         'value'     => 'event_post_deleted'
    //     ) );
    // }
}
function wpcargo_event_post_trashed( $post_id ) {
    // Exclude CPTs from external plugins.
    $post = get_post( $post_id );
    // Ignore updates from ignored custom post types.
    if ( $post->post_type !== 'wpcargo_shipment' ) {
        return;
    }

    if ( 'auto-draft' === $title || 'Auto Draft' === $title ) {
        return;
    }
    // $log_id = wpcargo_generate_log( $post_id );
    // if( $log_id ){
    //     wpcargo_generate_logmeta( $log_id, array(
    //         'metakey'   => 'ACTION',
    //         'value'     => 'event_post_trashed'
    //     ) );
    // }
    
}
function wpcargo_event_post_untrashed( $post_id ){
    // Exclude CPTs from external plugins.
    $post = get_post( $post_id );
    // Ignore updates from ignored custom post types.
    if ( $post->post_type !== 'wpcargo_shipment' ) {
        return;
    }

    if ( 'auto-draft' === $title || 'Auto Draft' === $title ) {
        return;
    }

    // $log_id = wpcargo_generate_log( $post_id );
    // if( $log_id ){
    //     wpcargo_generate_logmeta( $log_id, array(
    //         'metakey'   => 'ACTION',
    //         'value'     => 'event_post_untrashed'
    //     ) );
    // }
}
function wpcargo_check_template_change(){
    
}
add_action( 'pre_post_update', 'wpcargo_get_before_post_edit_data', 10, 2 );
add_action( 'save_post', 'wpcargo_post_changed', 10, 3 );
add_action( 'delete_post', 'wpcargo_event_post_deleted', 10, 1 );
add_action( 'wp_trash_post', 'wpcargo_event_post_trashed', 10, 1 );
add_action( 'untrash_post', 'wpcargo_event_post_untrashed' );
add_action( 'updated_post_meta', 'wpcargo_check_template_change', 10, 4 );