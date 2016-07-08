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
 
// For site options in Multisite
delete_site_option( $option_name );  
 
// Drop a custom db table
global $wpdb;
$current_table = $wpdb->prefix . 'swc_blacklist';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );

?>