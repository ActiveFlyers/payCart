<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

Rb_HelperTemplate::loadSetupEnv();

// load bootsrap, font-awesome
Rb_HelperTemplate::loadMedia(array('jquery', 'bootstrap', 'rb', 'font-awesome'));

Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/css/paycart.css');
Rb_Html::stylesheet(dirname(__FILE__).'/_media/css/site.css');


Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/js/paycart.js');
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/js/salvattore.js');
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/js/validate.js');
Rb_Html::script(dirname(__FILE__).'/_media/js/site.js');