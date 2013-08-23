<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		Puneet Singhal 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Address Model
 */
class PaycartModelAddress extends PaycartModel
{
	public $filterMatchOpeartor = array('user_id' 	=> array('='),
										'state'		=> array('LIKE'),
										'country'	=> array('=')
										);
										
	public function setNonPreferred($user_id)
	{
		$query = new Rb_Query();
		$query->select('count(address_id)')
			  ->from('#__paycart_address')
			  ->where("user_id = $user_id");
			  
		$count = $query->dbLoadQuery()->loadColumn();

		if($count == 1){
			return true;
		}
		
		// Set all addresses of one user as Non-Preferred
		$query = new Rb_Query();
		$query->update("#__paycart_address")
			  ->set('preferred = 0')
			  ->where("user_id = $user_id");
			  
		if(!$query->dbLoadQuery()->query()){
			return false;
		}
		
		return true;
	}
}

class PaycartModelformAddress extends PaycartModelform { }