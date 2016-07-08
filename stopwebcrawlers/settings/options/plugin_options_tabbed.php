<?php namespace stopwebcrawlersWPSettings;

$mypage = new Page('Stop Web Crawlers', array('type' => 'menu'));
     
$settings = array();

require_once (SWCPATH. "help/info.php");


$settings['Startup Guide']['Startup Guide'] = array('info' => $ah_help );
$fields = array();   

        
$settings['Startup Guide']['Startup Guide']['fields'] = $fields;


$msg2 = 'You need only check yes or no below. All Bad Bots enabled at default ';
$msg2 .='<a href="'.SWCURL.'/wp-admin/admin.php?page=my-custom-submenu-page">Web Crawlers Table</a> ';
$msg2 .= 'and at your Custom Table (My Black List) will be blocked.
<br />Then click SAVE CHANGES. ';
 


$settings['General Settings']['Instructions'] = array('info' => $msg2);
$fields = array();
   

$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'stop_bad_bots_active',
	'label' => 'Block all Bots at Bad Bots in the Table?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'yes'),
		array('value'=>'no', 'label' => 'no')
		)			
	);
                
$settings['General Settings']['']['fields'] = $fields;


$msg2 = 'In addiction to default system table, you can add one or more string to create your customized Black List. One by Line. <br>
Example: SpiderBot (no case sensitive)
<br>Just a piece of the name is enough.
For example, if you put <strong>bot</strong> will block all bots with the string
<strong>bot</strong> at user agent name. 
<br><strong>Attention:</strong>In this case, you will block also google bot because their 
name is GoogleBot
<br> Then click SAVE CHANGES.';


$settings['My Black List']['Customized Blacklist'] = array('info' => $msg2);
$fields = array();   
$fields[] = array(
	'type' 	=> 'textarea',
	'name' 	=> 'my_blacklist',
	'label' => 'My Blacklist'
	);
        
$settings['My Black List']['']['fields'] = $fields;

$sbb_admin_email = get_option( 'admin_email' ); 
$msg_email = 'Fill out the email address to send messages.<br />Left Blank to use your default Wordpress email.<br />('.$sbb_admin_email.')<br />Then, click save changes.';

 
$settings['Email Settings']['email'] = array('info' => $msg_email );
$fields = array();
$fields[] = array(
	'type' 	=> 'text',
	'name' 	=> 'my_email_to',
	'label' => 'email'
	);
$settings['Email Settings']['email']['fields'] = $fields;




//$admin_email = get_option( 'admin_email' ); 
$notificatin_msg = 'Do you want receive email alerts for each bot attempt?
<br /><strong>If you under bruteforce attack, you will receive a lot of emails.</strong>
';
 
$settings['Notifications Settings']['Notifications'] = array('info' => $notificatin_msg );
$fields = array();


       
    
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'my_radio_report_all_visits',
	'label' => 'Alert me by email each Bots Attempts',
	'radio_options' => array(
		array('value'=>'Yes', 'label' => 'Yes.'),
		array('value'=>'No', 'label' => 'No.'),
		)			
	);    
    
    
    
    
$settings['Notifications Settings']['Notifications']['fields'] = $fields;



new OptionPageBuilderTabbed($mypage, $settings);