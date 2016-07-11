<?php
global $swc_settings_config;

$swc_settings_config = array();

/**
 * Base Directory Setting
 */
$swc_settings_config['base_dir'] = __DIR__ . '/';

/**
 * Base URI Settings
 * Use Wordpress' plugins_url to set this if not a theme.
 */
$swc_settings_config['base_uri'] = plugins_url( 'settings' , dirname(__FILE__) ) . '/';



require_once (plugin_dir_path(__FILE__) . "containers.php");
require_once (plugin_dir_path(__FILE__) . "fields.php");
require_once (plugin_dir_path(__FILE__) . "factories.php");
require_once (plugin_dir_path(__FILE__) . "page-builders.php");
