<?php

// Fix magic quotes.
ini_set('magic_quotes_runtime', 0);

// Maximise error reporting.
error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', 1);

/*
 * Ensure that required path constants are defined.  These can be overridden within the phpunit.xml file
 * if you chose to create a custom version of that file.
 */
if (!defined('RBTEST_PATH'))
{
	define('RBTEST_PATH', realpath(dirname(__DIR__)));
}
if (!defined('RBTEST_CORE_PATH'))
{
	define('RBTEST_CORE_PATH', realpath(__DIR__));
}
if (!defined('RBTEST_BASE_PATH'))
{
	define('RBTEST_BASE_PATH', realpath(dirname(dirname(__DIR__))));
}
