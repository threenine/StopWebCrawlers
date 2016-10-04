<?php
final class Downloader{
	private $url;
	public function __construct($url){
		$this->url = $url;
	}
	
	public function get($args){
		
		$csvfile = wp_remote_get( $this->url, $args );
		
		$response_status = wp_remote_retrieve_response_code( $csvfile );
		$response_data   = wp_remote_retrieve_body( $csvfile );
		
		// Checks for any errors
		if ( ( is_wp_error( $csvfile) ) || ( $response_data == 'Not Found' ) || ! in_array( $response_status , array('200', '201') ) )
			return false;
		
			// Working up response
			$array_remote_bots = explode( PHP_EOL, $response_data );
			$array_remote_bots = array_map( 'trim', $array_remote_bots );
			$array_remote_bots = array_filter( $array_remote_bots );
		
			return $array_remote_bots;
	}
}