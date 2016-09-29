<?php
if (! defined ( 'ABSPATH' )) exit ();
if (! class_exists ( 'Request_Parser' )) {
	final class Request_Parser {
		
		
		public static function execute() {
		
			
			$server = new ServerVariables();
			$validate = new RequestValidator();
			// Get all the values from the request object
			$vars = $server->get_server_vars();
			list ( $ip_address, $request_uri, $query_string, $user_agent, $referer, $protocol, $method, $date ) = $vars;
			
			if($validate->Referer($referer)==false){
				wp_die ( 'Blocked access', 'Stop Web Crawlers', array (
						'response' => 403
				) );
				exit ();
			}
			
			
		}
		
		
		
	}


}
?>
