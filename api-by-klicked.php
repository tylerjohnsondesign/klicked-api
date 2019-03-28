<?php
/*
Plugin Name: API by Klicked Media
Plugin URI: https://klicked.com
Description: Creates API endpoints for usage elsewhere, outputs API responses from RevereReport.com and automatically creates Bitly shortlinks for tracking.
Version: 1.0.9
Author: Tyler Johnson
Author URI: http://tylerjohnsondesign.com/
Copyright: Tyler Johnson
Text Domain: KLICKEDAPI
Copyright © 2018 WP Developers. All Rights Reserved.
*/

/**
Disallow Direct Access to Plugin File
**/
if(!defined('WPINC')) { die; }

/**
Constants
**/
define('KLICKEDAPI_BASE_VERSION', '1.0.9');
define('KLICKEDAPI_BASE_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('KLICKEDAPI_BASE_URI', trailingslashit(plugin_dir_url(__FILE__)));
define('KLICKEDAPI_THEME_PATH', trailingslashit(get_template_directory()));
define('KLICKEDAPI_URL', '/wp-json/klicked/v2/');

/**
Updates
**/
require 'updates/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/klickedmedia/api-by-klicked',
	__FILE__,
	'api-by-klicked'
);
// Private repository
$myUpdateChecker->setAuthentication('049e3a5256f906850ffa5cb9c60ddc3696b85e75');

/**
Includes
**/
// Functions
require_once(KLICKEDAPI_BASE_PATH.'includes/functions.php');
// Storage
require_once(KLICKEDAPI_BASE_PATH.'includes/storage.php');
// Publish Checks
require_once(KLICKEDAPI_BASE_PATH.'includes/bitly.php');
// Revere Report
require_once(KLICKEDAPI_BASE_PATH.'output/revere-report/widget.php');