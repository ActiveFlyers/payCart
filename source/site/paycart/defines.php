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

// If file is already included
if(defined('PAYCART_SITE_DEFINED')){
	return;
}

//mark core loaded
define('PAYCART_SITE_DEFINED', true);
define('PAYCART_COMPONENT_NAME','paycart');


// define versions
define('PAYCART_VERSION', '@global.version@.@global.build.number@');
define('PAYCART_REVISION','@global.build.number@');

//shared paths
define('PAYCART_PATH_CORE',					JPATH_SITE.'/components/com_paycart/paycart');
define('PAYCART_PATH_CORE_MEDIA',			JPATH_ROOT.'/media/com_paycart');
define('PAYCART_PATH_CORE_FORM',			PAYCART_PATH_CORE.'/form');

// front-end
define('PAYCART_PATH_SITE', 				JPATH_SITE.'/components/com_paycart');
define('PAYCART_PATH_SITE_CONTROLLER',		PAYCART_PATH_SITE.'/controllers');
define('PAYCART_PATH_SITE_VIEW',			PAYCART_PATH_SITE.'/views');
define('PAYCART_PATH_SITE_TEMPLATE',		PAYCART_PATH_SITE.'/templates');

// back-end
define('PAYCART_PATH_ADMIN', 				JPATH_ADMINISTRATOR.'/components/com_paycart');
define('PAYCART_PATH_ADMIN_CONTROLLER',		PAYCART_PATH_ADMIN.'/controllers');
define('PAYCART_PATH_ADMIN_VIEW',			PAYCART_PATH_ADMIN.'/views');
define('PAYCART_PATH_ADMIN_TEMPLATE',		PAYCART_PATH_ADMIN.'/templates');

// Html => form + fields
define('PAYCART_PATH_CORE_FORMS', 		PAYCART_PATH_CORE.'/html/forms');
define('PAYCART_PATH_CORE_FIELDS', 		PAYCART_PATH_CORE.'/html/fields');

// object to identify extension, create once, so same can be consumed by constructors
Rb_Extension::getInstance(PAYCART_COMPONENT_NAME, array('prefix_css'=>'paycart'));