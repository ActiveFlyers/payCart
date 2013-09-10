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


// Load Joomla and Joomla Mock classes
require_once __DIR__ . '/core/core.php';

//load core Paycart and basic test case stuff
require_once 'defines.php'; 

// Load PaycartTestCase Class
require_once 'PaycartTestCase.php';


