<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
if (! class_exists ( 'Request_Parser' )) {
	final class Request_Parser {

		public static function execute() {
			
			// Simply exit if logged or in admin area
			if (is_user_logged_in () || is_admin ()) {
				return;
			}
			
			global $wpdb;
			//$referer = isset ( $_SERVER ['HTTP_REFERER'] ) ? $_SERVER ['HTTP_REFERER'] : false;
			
			$vars = swc_get_server_vars();
			list ($ip_address, $request_uri, $query_string, $user_agent, $referer, $protocol, $method, $date) = $vars;


			if (empty ( $referer )) {
				return;
			}
			
			$referer = strtolower ( $referer );
			$table_name = $wpdb->prefix . "swc_blacklist";
			
			$sql = "SELECT boturl FROM " . $table_name . " WHERE boturl = '" . $referer . "' AND botstate = 'Enabled'";
			$bots = $wpdb->get_results ( $sql );
			
			foreach ( $bots as $row ) {
				if (strpos ( $referer, $row->boturl ) !== false) {
					wp_die ( '', '', array (
							'response' => 403 
					) );
					exit ();
				}
			}
		}

	}
}

	?>
