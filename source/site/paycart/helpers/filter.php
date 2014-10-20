<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	paycartHelper
 * @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Format Helper
 * @author Gaurav Jain
 */

class PaycartHelperFilter extends JObject
{  
	public static function attributecode($value)
	{		
		// this function is called from filter, so need to be static
		$value = JApplicationHelper::stringURLSafe($value);
		$value = strtoupper($value);
		return str_replace('-', '_', $value); 
	}
        
}
