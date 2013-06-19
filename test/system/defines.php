<?php
// Maximise error reporting.
error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', 1);

/*
 * Ensure that required path constants are defined.  These can be overridden within the phpunit.xml file
 * if you chose to create a custom version of that file.
 */
//include core 
require_once realpath(dirname(__DIR__)).'/core/defines.php';

if (!defined('RBTEST_BASE')) 		{ 	define('RBTEST_BASE', 			realpath(dirname(__DIR__))); }
if (!defined('RBTEST_SYSTEM_BASE')) { 	define('RBTEST_SYSTEM_BASE', 	RBTEST_BASE.'/system/'); }
if (!defined('RBTEST_UNIT_BASE')) 	{ 	define('RBTEST_UNIT_BASE', 		RBTEST_BASE.'/unit/'); }

// System Constant
if (!defined('RBTEST_SYSTEM_PAGES')) 			{ 	define('RBTEST_SYSTEM_PAGES', RBTEST_SYSTEM_BASE.'/Pages/'); }
if (!defined('RBTEST_SYSTEM_SERVER_CONFIG')) 	{ 	define('RBTEST_SYSTEM_SERVER_CONFIG', RBTEST_SYSTEM_BASE.'/servers/config.php'); }
