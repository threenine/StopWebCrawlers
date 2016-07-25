

<?php
add_action ( 'admin_menu', 'swc_create_menu' );



function swc_create_menu() {
	add_menu_page ( 'Stop Web Crawlers', 
			'Dashboard', 
			'manage_options', 
			'swc_main_menu', 
			'swc_main_page', 
			plugins_url ( 'images/39balls.png', __FILE__ ) );
	
	add_submenu_page(
			'swc_main_menu', // $parent_slug
			'Add Web Crawler', // string $page_title
			'List', // string $menu_title
			'manage_options', // string $capability
			'web-crawlers-add',
			'swc_render_list_page' );
	
	add_submenu_page(
			'swc_main_menu', // $parent_slug
			'Web Crawlers Table', // string $page_title
			'Add', // string $menu_title
			'manage_options', // string $capability
			'web-crawlers-table',
			'swc_add_page' );
	
}
?>

<?php 
function swc_render_list_page() {
		$crawler_list_table = new swc_List_Table();
		$crawler_list_table->prepare_items();
		require dirname( __FILE__ ) . '/includes/list-tables/page.php';
	}
	?>

<?php 
function swc_main_page(){
	?>
	<div class="wrap">
	<h2>Stop Web Crawlers by threenine.co.uk : Dashboard</h2>
	
	</div>


<?php 	
}
?>


<?php
function swc_add_page() {
	?>
<div class="wrap">
	<h2>Add Web Crawler</h2>
	<form method="post" action="options.php">
<?php settings_fields( 'prowp-settings-group' ); ?> <?php $prowp_options = get_option( 'prowp_options' ); ?> <table
			class="form-table">
			<tr valign="top">
				<th scope="row">Name</th>
				<td><input type="text" name="prowp_options[option_name]"
					value="<?php
	
echo esc_attr ( $prowp_options ['option_name'] );
	?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Email</th>
				<td><input type="text" name="prowp_options[option_email]"
					value="<?php
	
echo esc_attr ( $prowp_options ['option_email'] );
	?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">URL</th>
				<td><input type="text" name="prowp_options[option_url]"
					value="<?php echo esc_url( $prowp_options['option_url'] ); ?>" /></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Save Changes" />
		
		</p>
	</form>
</div>
<?php
}
	
?>