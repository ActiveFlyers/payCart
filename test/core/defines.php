<?php


/*
 * Ensure that required path constants are defined.  These can be overridden within the phpunit.xml file
 * if you chose to create a custom version of that file.
 */
if (!defined('RBTEST_BASE')) 				{ 	define('RBTEST_BASE', 			realpath(dirname(__DIR__))); }
if (!defined('RBTEST_SYSTEM_BASE')) 		{ 	define('RBTEST_SYSTEM_BASE', 	RBTEST_BASE.'/system/'); }
if (!defined('RBTEST_UNIT_BASE')) 			{ 	define('RBTEST_UNIT_BASE', 		RBTEST_BASE.'/unit/'); }

// System Constant
if (!defined('RBTEST_SITE_ROOT')) 			{ 	define('RBTEST_SITE_ROOT', 	'@joomla.rootpath@'); }
if (!defined('RBTEST_HOST'))				{ 	define('RBTEST_HOST', 			'http://localhost'); }
if (!defined('RBTEST_SCREENSHOTS')) 		{ 	define('RBTEST_SCREENSHOTS', 	 RBTEST_BASE.'/system/screenshots/'); }
if (!defined('RBTEST_ERROR_REPORTING'))		{ 	define('RBTEST_ERROR_REPORTING','@joomla.error.reporting@'); }	//'maximum'

if (!defined('RBTEST_DB_HOST'))				{ 	define('RBTEST_DB_HOST', 		'localhost'); }
if (!defined('RBTEST_DB_USER'))				{ 	define('RBTEST_DB_USER', 		'@joomla.dbuser@'); }
if (!defined('RBTEST_DB_PASSWORD'))			{ 	define('RBTEST_DB_PASSWORD', 	'@joomla.dbpassword@'); }
if (!defined('RBTEST_DB_NAME'))				{ 	define('RBTEST_DB_NAME', 		'@joomla.dbname@'); }
if (!defined('RBTEST_DB_PREFIX'))			{ 	define('RBTEST_DB_PREFIX', 		'@joomla.dbprefix@'); }
if (!defined('RBTEST_DB_TYPE'))				{ 	define('RBTEST_DB_TYPE', 		'@joomla.dbtype@'); }

if (!defined('RBTEST_ADMIN_USERNAME'))		{ 	define('RBTEST_ADMIN_USERNAME', '@joomla.admin.username@'); }
if (!defined('RBTEST_ADMIN_PASSWORD'))		{ 	define('RBTEST_ADMIN_PASSWORD', '@joomla.admin.password@'); }
if (!defined('RBTEST_ADMIN_EMAIL'))			{ 	define('RBTEST_ADMIN_EMAIL', 	'@joomla.admin.email@'); }


if (!defined('RBTEST_SITE_FOLDER')) 		{ 	define('RBTEST_SITE_FOLDER', 	'/@joomla.folder@/'); }
if (!defined('RBTEST_SITE_NAME')) 			{ 	define('RBTEST_SITE_NAME', 	 	'@joomla.site.name@'); }
if (!defined('RBTEST_PACKAGE_NAME')) 		{ 	define('RBTEST_PACKAGE_NAME', 	'@joomla.package.name@'); }
if (!defined('RBTEST_EXTENSION_NAME')) 		{ 	define('RBTEST_EXTENSION_NAME', '@joomla.extension.name@'); }