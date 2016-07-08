<?php namespace swcWPSettings;

$mypage = new Page('Stop Web Crawlers', array('type' => 'menu'));
     
$settings = array();

require_once (SWCPATH. "help/info.php");


$settings['Startup Guide']['Startup Guide'] = array('info' => $ah_help );
$fields = array();   

        
$settings['Startup Guide']['Startup Guide']['fields'] = $fields;


$msg2 = 'You need only check yes or no below. All Bad Bots enabled at default ';
$msg2 .='<a href="'.SWCURL.'/wp-admin/admin.php?page=web-crawlers-table">Web Crawlers Table</a> ';
$msg2 .= 'and at your Custom Table (My Black List) will be blocked.
<br />Then click SAVE CHANGES. ';
 






new OptionPageBuilderTabbed($mypage, $settings);