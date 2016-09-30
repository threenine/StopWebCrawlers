<?php
if (!defined('ABSPATH')) exit;

/**
 * Admin CSS registration.
 *
 * Register the stylesheets for admin.
 *
 * @since    1.3.5
 */
function swc_enqueue_resources_admin() {
	if (isset($_GET['page']) && (($_GET['page'] == 'swc_main_menu') || ($_GET['page'] == 'add-web-crawler'))) {
		
		wp_enqueue_style('swc_admin', SWCURL .'css/swc_admin.css');
		
	}
}


/**
 * Detect if DB Upgrade is required.
 *
 * Detect if DB upgrade is required if plugin updated.
 *
 * @since    1.3.5
 */
function checkversion()
{
	//Get Current version
	$installed_version = get_site_option('SWC_VERSION');
	if(!$installed_version){
		add_site_option('SWC_VERSION', SWC_VERSION);
	}

	if(version_compare($installed_version, SWC_VERSION, '<')){
		$du = new DatabaseUpdate($installed_version, SWC_VERSION);
		$du->upgrade();
		update_site_option('SWC_VERSION', SWC_VERSION);
	}
		
		

}