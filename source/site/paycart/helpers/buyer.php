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
class PaycartHelperBuyer extends PaycartHelper
{	

	// @PCTODO : remove this function
	public static function setPreferredAddress($buyer_id, $address_id)
	{
		$addresses = self::getAddresses($buyer_id);
		
		if(!array_key_exists($address_id, $addresses)){
			return false;
		}
		
		$query = new Rb_Query();
		$query->update("#__paycart_address")
			  ->set('preferred = 0')
			  ->where("buyer_id = $user_id");
			  
		if(!$query->dbLoadQuery()->query()){
			return false;
		}
		
		return true;
	}
	
	public static function getAddresses($buyer_id)
	{
		$filter = array('buyer_id' => $buyer_id);
		$records = PaycartFactory::getInstance('address', 'model')->loadRecords($filter);

		return $records;
	}
}