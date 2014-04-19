<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Color Model
 * @author rimjhim
 *
 */
class PaycartModelColor extends PaycartModel
{
	function loadOptions($languageCode)
	{
		$query = new Rb_Query();
		
		return $query->select('*')
		 		     ->from('#__paycart_color as ac')
		 		     ->join('INNER', '#__paycart_color_lang as acl ON ac.color_id = acl.color_id')
		 		     ->where('acl.lang_code = '.$languageCode)
		 		     ->dbLoadQuery()
		 		     ->loadAssocList();
	}
}

/**
 * 
 * Color language Model
 * @author rimjhim
 *
 */
class PaycartModelLangColor extends PaycartModel{}
