<?php
if (!defined('ABSPATH')) exit;
function swc_enqueue_resources_admin() {
	if (isset($_GET['page']) && (($_GET['page'] == 'swc_main_menu') || ($_GET['page'] == 'add-web-crawler'))) {
		
		wp_enqueue_style('swc_admin', SWCURL .'css/swc_admin.css');
		
	}
}