<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 */

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Base Model Form
* @author Team Readybytes
 */
class PaycartModelform extends Rb_Modelform
{
	public	$_component			= PAYCART_COMPONENT_NAME;	
	protected $_forms_path 		= PAYCART_PATH_CORE_FORMS;
	protected $_fields_path 	= PAYCART_PATH_CORE_FIELDS;
}