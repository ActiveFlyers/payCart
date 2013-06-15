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

//load core
Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_CORE.'/base',		     '',		 'Paycart');

Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_CORE.'/models',		'Model',	 'Paycart');
Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_CORE.'/models',		'Modelform', 'Paycart');

Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_CORE.'/tables',		'Table',	 'Paycart');
Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_CORE.'/libs',			'',			 'Paycart');
Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_CORE.'/helpers',		'Helper',	 'Paycart');
Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_CORE.'/payment',		'',	 		 'Paycart');

//html
Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_CORE.'/html/html',		'Html',		 'Paycart');
Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_CORE.'/html/fields',	'FormField', 'Paycart');

// site
Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_SITE.'/controllers',	'Controller',		'PaycartSite');
Rb_HelperLoader::addAutoLoadViews(PAYCART_PATH_SITE.'/views', RB_REQUEST_DOCUMENT_FORMAT,  'PaycartSite');

// admin
Rb_HelperLoader::addAutoLoadFolder(PAYCART_PATH_ADMIN.'/controllers',	'Controller',		'PaycartAdmin');
Rb_HelperLoader::addAutoLoadViews(PAYCART_PATH_ADMIN.'/views', RB_REQUEST_DOCUMENT_FORMAT, 'PaycartAdmin');