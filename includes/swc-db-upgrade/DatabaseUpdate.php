<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
if (! class_exists ( 'DatabaseUpdate' )) {

	final class DatabaseUpdate {
		private startVersion;
		private string 
		public function __construct(){

			}

		public function __destruct() {
	        //Clear any objects created
	    }

	    public function upgradeToVersion($fromVerion, $toVersion){

	    	$from = str_replace('.', '_', $fromVersion);
	    	$to = str_replace ('.', '_', $toVersion);)




	    }
	}
}
?>