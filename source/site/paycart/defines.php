<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		suppor+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// If file is already included
if(defined('PAYCART_SITE_DEFINED')){
	return;
}

//mark core loaded
define('PAYCART_SITE_DEFINED', true);
define('PAYCART_COMPONENT_NAME','paycart');


// define versions
define('PAYCART_VERSION', '@global.version@');
define('PAYCART_REVISION','@global.build.number@');

//shared paths
define('PAYCART_PATH_CORE',					JPATH_SITE.'/components/com_paycart/paycart');
define('PAYCART_PATH_CORE_MEDIA',			'com_paycart');
define('PAYCART_PATH_ADMIN_MEDIA',			'com_paycart/admin');
define('PAYCART_PATH_CORE_FORM',			PAYCART_PATH_CORE.'/form');
define('PAYCART_PATH_CORE_IMAGES',  		'images/cart');
define('PAYCART_PATH_MEDIA_DIGITAL_TEASER', JPATH_ROOT.'/media/com_paycart/digital/teaser/');
define('PAYCART_PATH_MEDIA_DIGITAL_MAIN',   JPATH_ROOT.'/media/com_paycart/digital/main/');
define('PAYCART_SITE_PATH_CSV_IMPEXP' , 	'/media/com_paycart/csv_impexp/');
define('PAYCART_ATTRIBUTE_PATH_CSV_IMPEXP', JPATH_ROOT.PAYCART_SITE_PATH_CSV_IMPEXP);



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
define('PAYCART_PATH_ADMIN_LAYOUTS',		PAYCART_PATH_ADMIN_TEMPLATE.'/default/_layouts');

// Html => form + fields
define('PAYCART_PATH_CORE_FORMS', 			PAYCART_PATH_CORE.'/html/forms');
define('PAYCART_PATH_CORE_FIELDS', 			PAYCART_PATH_CORE.'/html/fields');

// Paycart Custom Attributes
define('PAYCART_PATH_CUSTOM_ATTRIBUTES', 	PAYCART_PATH_CORE.'/attributes');
define('PAYCART_ATTRIBUTE_PATH_MEDIA',  	PAYCART_PATH_CORE_MEDIA.'/media/');

// hard coded layout path with default templt
define('PAYCART_LAYOUTS_PATH',				JPATH_SITE.'/components/com_paycart/layouts');

// object to identify extension, create once, so same can be consumed by constructors
Rb_Extension::getInstance(PAYCART_COMPONENT_NAME, array('prefix_css'=>'pc'));