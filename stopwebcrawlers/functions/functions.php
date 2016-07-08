<?php
function swc_plugin_activated()
{
	global $wp_swc_blacklist;

	require_once (SWCPATH . "functions/crawlers.php");

	swc_create_db();
	swc_populate_db($wp_swc_blacklist);
}


function swc_create_db()
{
	global $wpdb;
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	// creates my_table in database if not exists
	$table = $wpdb->prefix . "swc_blacklist";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table (
	`id` mediumint(9) NOT NULL AUTO_INCREMENT,
	`botnickname` varchar(30) NOT NULL,
	`botname` text NOT NULL,
	`boturl` text NOT NULL,
	`botip` varchar(100) NOT NULL,
	`botobs` text NOT NULL,
	`botstate` varchar(10) NOT NULL,

	UNIQUE (`id`),
	UNIQUE (`botnickname`)

	) $charset_collate;";

	

	dbDelta($sql);

}


function swc_plugin_db_update()
{

	global $wp_swc_blacklist, $wpdb;
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

	require_once (SWCPATH . "functions/crawlers.php");

	$z = count($wp_swc_blacklist);

	$table_name = $wpdb->prefix . "swc_blacklist";

	$results9 = $wpdb->get_results("SELECT * FROM $table_name");

	if (count($results9) >= $z)
		return;

		swc_create_db();
		swc_populate_db($wp_swc_blacklist);
		
}

function swc_populate_db($wp_swc_blacklist)
{
	global $wpdb;
	$table_name = $wpdb->prefix . "swc_blacklist";
	$charset_collate = $wpdb->get_charset_collate();
	$z = count($wp_swc_blacklist);


	for ($i = 0; $i < $z; $i++) {
		$a = $wp_swc_blacklist[$i];


		$botnickname = trim($a['botnickname']);
		$botname = trim($a['botname']);
		$boturl = trim($a['boturl']);

		$results9 = $wpdb->get_results("SELECT * FROM $table_name where botnickname = '$botnickname' limit 1");


		if (count($results9) > 0 or empty($botnickname))
			continue;

			/*
			 $r = $wpdb->insert($table_name, array(
			 'botnickname' => $botnickname,
			 'botname' => $a['botname'],
			 'boturl' => $a['boturl'],
			 'botstate' => "Enabled",
			 ), array(
			 '%s',
			 '%s',
			 '%s',
			 '%s'));
			 */

			$query = "INSERT INTO ".$table_name.
			" (botnickname, botname, boturl, botstate)
          VALUES ('"
					.$botnickname.
					"', '".
					$botname .
					"', '"
							.$boturl .
							"', 'Enabled')";
							 
							$r = $wpdb->get_results($query);
	}
	}