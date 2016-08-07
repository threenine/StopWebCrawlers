<?php


if (!defined('SWC')) define('SWC', '0.2' );
define('SWCPATH', plugin_dir_path(__FILE__) );
define('SWCURL', plugin_dir_url(__FILE__));
define('SWCDOMAIN', get_site_url() );

require_once (SWCPATH . "settings/load-plugin.php");
require_once (SWCPATH . "mainmenu.php");
require_once (SWCPATH . "functions/functions.php");
require_once (ABSPATH . "wp-includes/pluggable.php");
