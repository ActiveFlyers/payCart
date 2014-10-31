<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

Rb_HelperTemplate::loadSetupEnv();

Rb_HelperTemplate::loadMedia(array('jquery', 'bootstrap', 'rb', 'font-awesome'));

// load bootsrap css
Rb_Html::_('bootstrap.loadcss');

Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/paycart.js');
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/validate.js');
Rb_Html::script(PAYCART_PATH_ADMIN_MEDIA.'/admin.js');

Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/paycart.css');
Rb_Html::stylesheet(PAYCART_PATH_ADMIN_MEDIA.'/admin.css');

Rb_Html::_('formbehavior.chosen', 'select');