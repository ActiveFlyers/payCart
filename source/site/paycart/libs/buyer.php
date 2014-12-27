<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		Puneet Singhal, Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Buyer Lib
 * @author manish
 *
 */
class PaycartBuyer extends PaycartLib
{
	/**
	 * byer table fields
	 */
	protected $buyer_id;
	protected $is_registered_by_guestcheckout;
	protected $default_address_id;
	
	/**
	 * 
	 * Cross table fields
	 */
	protected $realname;
	protected $username;
	protected $email;
	protected $usertype;
	protected $register_date;
	protected $lastvisit_date;
	protected $default_phone;
	
	/**
	 * 
	 * PaycartBuyer Instance
	 * @param  $id, existing PaycartBuyer id
	 * @param  $data, required data to bind on return instance	
	 * @param  $dummy1, Just follow code-standards
	 * @param  $dummy2, Just follow code-standards
	 * 
	 * @return PaycartBuyer lib instance
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('buyer', $id, $bindData);
	}
	
	public function reset() 
	{		
		$this->buyer_id	 						=	0;
		$this->is_registered_by_guestcheckout	=	0;
		$this->default_address_id				=	0;
		$this->default_phone					= '';
		
		return $this;
	}
	
	/**
	 * 
	 * Return Default address information
	 * @param Bool $instance
	 * 
	 * @return PaycartBuyeraddress or Buyer Default address Id
	 */
	public function getDefaultAddress($instance = false) 
	{
		if ($instance) {
			return PaycartBuyeraddress::getInstance($this->default_address_id);
		}
		
		return $this->default_address_id;
	}
	
	public function getEmail() 
	{
		return $this->email;
	}

	public function getUsername() 
	{
		return $this->username;
	}
	
	public function getRealName() 
	{
		return $this->realname;
	}
	
	public function getDefaultPhone()
	{
		return $this->default_phone;
	}
}
