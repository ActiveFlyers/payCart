<?php
/**
 * Prepares a minimalist framework for unit testing.
 *
 *
 * @package     PayCart.UnitTest
 * @author		mManishTrivedi
 */


// Constant for PayCart
// PayCart-Application
define('PAYCARTTEST_FRONT_END', JPATH_SITE.'/components/com_paycart');
define('PAYCARTTEST_BACK_END', 	JPATH_ADMINISTRATOR.'/components/com_paycart');

//load rb-frmwork
require_once JPATH_PLUGINS.'/system/rbsl/rb/includes.php';
// PCTODO:: Be ensured RB-framwork loaded
define('RB_DEFINE_ONSYSTEMSTART', true);
//load Paycart framwork
require_once PAYCARTTEST_FRONT_END.'/paycart/includes.php';


// Register the core Joomla test classes.
//IMP :: Use Proper Class name Otherwise It will not work.
// like You have class PayCartTestCaseReflection then don't use PaycartTestCaseReflection
// 'c' should be capital in Paycart
JLoader::registerPrefix('PayCartTest', __DIR__ );

include_once 'NullDataSet.php';
include_once 'DeleteSqliteSequence.php';
include_once 'Array.php';


