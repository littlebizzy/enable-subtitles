<?php
/*
Plugin Name: Enable Subtitles
Plugin URI: https://www.littlebizzy.com/plugins/enable-subtitles
Description: Creates a the_subtitle function for use in WordPress posts and pages, such as for H2 subtitles, that can be called in template files or shortcodes.
Version: 1.0.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Prefix: ENBSTL
*/

// Plugin namespace
namespace LittleBizzy\EnableSubtitles;

// Block direct calls
if (!function_exists('add_action'))
	die;

// Plugin constants
const FILE = __FILE__;
const PREFIX = 'enbstl';
const VERSION = '1.0.0';

// Loader
require_once dirname(FILE).'/helpers/loader.php';

// Run the main class
Helpers\Runner::start('Core\Core', 'instance');