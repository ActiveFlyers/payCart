<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

// if PAYCART already loaded, then do not load it again
if(defined('PAYCART_CORE_LOADED')){
	return;
}

define('PAYCART_CORE_LOADED', true);

// include defines
include_once dirname(__FILE__).'/defines.php';
include_once dirname(__FILE__).'/functions.php';

// load frontend language file on both end
$filename = 'com_paycart';
$language = JFactory::getLanguage();
$language->load($filename, JPATH_SITE);

//Load language file for plugins
$filename = 'com_paycart_plugins';
$language = JFactory::getLanguage();
$language->load($filename, JPATH_SITE);


// set autoload for all classes
$classes = require_once 'classes.php';
foreach ($classes as $className => $filePath) {
	
	// load backend files
	if ('admin/' === substr($filePath, 0, 6)) {
		$filePath = substr($filePath,6);
		Rb_HelperLoader::addAutoLoadFile(PAYCART_PATH_ADMIN.'/'.$filePath, $className);
		continue; 
	}
	
	// load front end files
	if ('site/' === substr($filePath, 0, 5)) {
		$filePath = substr($filePath,5);
		Rb_HelperLoader::addAutoLoadFile(PAYCART_PATH_SITE.'/'.$filePath, $className);
		continue; 
	}	
	
	// load specific intaller script file
	if ( 'com_paycartinstallerscript' === strtolower($className)) {
		Rb_HelperLoader::addAutoLoadFile(PAYCART_PATH_ADMIN.'/'.$filePath, $className);
		continue; 
	}
	
	throw new RuntimeException('File is not exist : '.$filePath );
}

//@PCFIXME : move to proper location
Rb_HelperJoomla::loadPlugins('paycart');

