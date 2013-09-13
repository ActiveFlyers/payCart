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

// Constant for PayCart
// PayCart-Application
define('PAYCART_FRONT_END', JPATH_SITE.'/components/com_paycart');
define('PAYCART_BACK_END', 	JPATH_ADMINISTRATOR.'/components/com_paycart');

//load rb-frmwork
require_once JPATH_PLUGINS.'/system/rbsl/rb/includes.php';
// PCTODO:: Be ensured RB-framwork loaded
define('RB_DEFINE_ONSYSTEMSTART', true);
//load Paycart framwork
require_once PAYCART_FRONT_END.'/paycart/includes.php';

