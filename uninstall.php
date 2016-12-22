<?php
/**
 * @author Gary Woodfine
 * @copyright 2016
 */
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
 
$option_name = 'stopwebcrawlers';
 
delete_option( $option_name );
delete_site_option(SWC_VERSION);
 
// For site options in Multisite
delete_site_option( $option_name ); 
 $SWC_CRAWLERS_LOG = 'swc_crawlers_log';
 $SWC_CRAWLERS = 'swc_crawlers';
 $SWC_CRAWLER_TYPE = 'swc_crawler_type';
 $SWC_BLACKLIST = 'swc_blacklist';
 
// Drop a custom db table
global $wpdb;
$blacklist = $wpdb->prefix .  $SWC_BLACKLIST;
$log_table =  $wpdb->prefix . $SWC_CRAWLERS_LOG;
$crawlers_table =  $wpdb->prefix .  $SWC_CRAWLERS;
$type_table =  $wpdb->prefix . $SWC_CRAWLER_TYPE;
$wpdb->query( "DROP TABLE IF EXISTS $blacklist" );
$wpdb->query( "DROP TABLE IF EXISTS $log_table" );
$wpdb->query( "DROP TABLE IF EXISTS $crawlers_table" );
$wpdb->query( "DROP TABLE IF EXISTS $type_table" );
?>