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
 * Base Model
* @author Team Readybytes
 */
class PaycartModel extends Rb_Model
{
	public	$_component	= PAYCART_COMPONENT_NAME;

	
	public function getValidator()
	{
		return PaycartFactory::getValidator();
	}
}
