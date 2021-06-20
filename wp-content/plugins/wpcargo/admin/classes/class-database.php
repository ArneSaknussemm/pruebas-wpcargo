<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
class WPCARGO_DATABASE{
    public static function create_log_table(){
        global $wpdb;
        $logs_table = $wpdb->prefix.WPCARGO_DB_LOG;
        if($wpdb->get_var("SHOW TABLES LIKE '$logs_table'") != $logs_table) {
			$charset_collate = $wpdb->get_charset_collate();
			$logs_sql    = "CREATE TABLE IF NOT EXISTS $logs_table (
							`id` bigint(20) NOT NULL auto_increment,
							`date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            `shipment_id` bigint(20) NOT NULL,
							UNIQUE KEY `id` (`id`)
							) $charset_collate;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($logs_sql);
        }
        $logmeta_table = $wpdb->prefix.WPCARGO_DB_LOGMETA;
        if($wpdb->get_var("SHOW TABLES LIKE '$logmeta_table'") != $logmeta_table) {
			$charset_collate = $wpdb->get_charset_collate();
			$logmeta_sql    = "CREATE TABLE IF NOT EXISTS $logmeta_table (
							`id` bigint(20) NOT NULL auto_increment,
							`log_id` bigint(20) NOT NULL,
							`metakey` varchar(100) NOT NULL,
                            `value` longtext NOT NULL,
							UNIQUE KEY `id` (`id`)
							) $charset_collate;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($logmeta_sql);
		}
    }
}