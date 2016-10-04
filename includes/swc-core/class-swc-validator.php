<?php
  final class RequestValidator{
	
	private $dal;
	public function __construct(){
		
		$this->dal = new data_access_layer();
		
	}
	
	/**
	 * Check for Referrer spam.
	 *
	 *  Check Referer spam .
	 *
	 * @since    1.3.5
	 */
	public function Referer($referer){
		
		
		if (empty ( $referer )) {
			return true;
		}
		
		$referer = strtolower ( $referer );
		$bots = $this->dal->GetCrawlers ( $referer );
		if ($bots != null && is_array ( $bots )) {
				
			foreach ( $bots as $row ) {
				if (strpos ( $referer, $row->url ) !== false) {
					return false;
				}
				return true;
			}
		}
		return true;
	}
	
	
}