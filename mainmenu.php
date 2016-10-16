<?php

function swc_create_menu() {
	$admin_page_title = 'Stop Web Crawlers by threenine.co.uk : Dashboard';
	$admin_menu_title = 'Stop Web Crawlers';
	$manage_options_capability = 'manage_options';
	$admin_menu_slug = 'swc_main_menu';
	$admin_menu_function = 'swc_main_page';
	$admin_icon_url = plugins_url ( 'images/stop-web-crawlers.png', __FILE__ );
	$admin_list_page_title = 'Web Crawler List';
	$list_menu_title = 'List';
	$list_slug = 'web-crawlers-list';
	$list_table_function = 'swc_render_list_page';
	$add_new_crawler_title = 'Add Web Crawler';
	$add_new_title = 'Add New';
	$add_new_slug = 'add-web-crawler';
	$add_new_function = 'swc_add_page';
	
	add_menu_page ( '', $admin_menu_title, $manage_options_capability, $admin_menu_slug, $admin_menu_function, $admin_icon_url );
		
	add_submenu_page ( $admin_menu_slug, 'Dashboard', 'Dashboard', $manage_options_capability, $admin_menu_slug, $admin_menu_function);
	
	add_submenu_page ( $admin_menu_slug, $admin_list_page_title, $list_menu_title,  $manage_options_capability, $list_slug , $list_table_function );
	
	add_submenu_page ( $admin_menu_slug, $add_new_crawler_title,  $add_new_title, $manage_options_capability, $add_new_slug , $add_new_function );
}
function swc_render_list_page() {
	$crawler_list_table = new swc_List_Table ();
	$crawler_list_table->prepare_items ();
	require dirname ( __FILE__ ) . '/views/list.php';
}
function swc_main_page(){
	include 'views/dashboard.php';
}
function swc_add_page() {
	
	
	if (isset($_POST ['nonce']) && wp_verify_nonce ( $_POST ['nonce'], 'add' )) {
		
		$name =  sanitize_text_field($_POST ['swc_name']);
		
		$url =  sanitize_text_field($_POST ['swc_url']);

		$dal = new data_access_layer();
		$dal->insert_crawler($name, $url);
		
		$message = 'New crawler added successfully!';
	}
	include 'views/addnew.php';
}





?>