<?php
/**
 * Prepares a minimalist framework for unit testing.
 *
 *
 * @package     PayCart.UnitTest
 * @author		mManishTrivedi
 */

define('_JEXEC', 1);
// Fix magic quotes.
ini_set('magic_quotes_runtime', 0);

// Maximise error reporting.
ini_set('zend.ze1_compatibility_mode', '0');
error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', 1);


/*
 * Ensure that required path constants are defined.  These can be overridden within the phpunit.xml file
 * if you chose to create a custom version of that file.
 */
if (!defined('RBTEST_BASE')) 		{ 	define('RBTEST_BASE', 			realpath(dirname(__DIR__))); }
if (!defined('RBTEST_SYSTEM_BASE')) { 	define('RBTEST_SYSTEM_BASE', 	RBTEST_BASE.'/system/'); }
if (!defined('RBTEST_UNIT_BASE')) 	{ 	define('RBTEST_UNIT_BASE', 		RBTEST_BASE.'/unit/'); }

if (!defined('RBTEST_PATH_DATA')) 		{ 	define('RBTEST_PATH_DATA', 	RBTEST_BASE.'/_data/'); }
if (!defined('RBTEST_PATH_DATASET')) 	{ 	define('RBTEST_PATH_DATASET', 	RBTEST_PATH_DATA.'/dataset/'); }

//include our Testing Core 
require_once realpath(dirname(__DIR__)).'/core/defines.php';

// Load Joomla and Joomla Mock classes
require_once __DIR__ . '/joomla/joomla.php';

// Load Paycart Stuff
require_once __DIR__ . '/paycart/paycart.php';

// require_once  only rb_ecommerce api. Avoid to load rb-ecmmoerce whole Package
$file = JPATH_ROOT."/plugins/system/rbsl/rb/pkg/ecommerce/api.php";
if (!JFile::exists($file)) {
	// fire exception
	throw new RuntimeException(Rb_Text::sprintf('COM_PAYCART_PACKAGE_NOT_EXIST', 'ECOMMERCE'));
}
include_once $file;



