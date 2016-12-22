<?php 
  
	abstract class updater{

 	const SWC_BLACKLIST = 'swc_blacklist';
 	const SWC_CRAWLER_LOG ='swc_crawler_log';

 	abstract protected function update();

 }
?>