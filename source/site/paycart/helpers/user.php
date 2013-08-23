<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 * @author 		Puneet Singhal
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * User Helper
 */
class PaycartHelperUser extends PaycartHelper
{	

	// @PCTODO : remove this function
	public static function setPreferredAddress($user_id, $address_id)
	{
		$addresses = self::getAddresses($user_id);
		
		if(!array_key_exists($address_id, $addresses)){
			return false;
		}
		
		$query = new Rb_Query();
		$query->update("#__paycart_address")
			  ->set('preferred = 0')
			  ->where("user_id = $user_id");
			  
		if(!$query->dbLoadQuery()->query()){
			return false;
		}
		
		return true;
	}
	
	public static function getAddresses($user_id)
	{
		$filter = array('user_id' => $user_id);
		$records = PaycartFactory::getInstance('address', 'model')->loadRecords($filter, array(), false, 'address_id');

		return $records;
	}
}