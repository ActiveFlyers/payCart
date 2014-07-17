<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Buyer Address Lib
 * @author manish
 *
 */
class PaycartBuyeraddress extends PaycartLib
{
	protected $buyeraddress_id;
	protected $buyer_id;
	protected $to;
	protected $address;
	protected $city;
	protected $state_id;
	protected $country_id;
	protected $zipcode;
	protected $phone1;
	protected $phone2;
	protected $vat_number;
	protected $md5;
	

	/**
	 * 
	 * PaycartBuyeraddress Instance
	 * @param  $id, existing PaycartCartparticular id
	 * @param  $data, required data to bind on return instance	
	 * @param  $dummy1, Just follow code-standards
	 * @param  $dummy2, Just follow code-standards
	 * 
	 * @return PaycartBuyeraddress lib instance
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('buyeraddress', $id, $bindData);
	}
	
	public function reset() 
	{
		$this->buyeraddress_id	=	0;
		$this->buyer_id			=	0;
		$this->to				=	'';
		$this->address			=	'';
		$this->city				=	'';
		$this->state_id			=	'';
		$this->country_id			=	'';
		$this->zipcode			=	'';
		$this->phone1			=	'';
		$this->phone2			=	'';
		$this->vat_number		=	'';
		
		return $this;
	}
	
	public function bind($data, $ignore=Array()) 
	{		
		parent::bind($data, $ignore);
		
		$this->md5 = $this->getMD5();
		
		return $this;
	}
	
	public function getMD5()
	{
		$string = 	$this->buyer_id.$this->to.$this->address.$this->city.
					$this->state_id.$this->country_id.$this->zipcode.
					$this->phone1.$this->phone2.$this->vat_number;
					
		//remove all white spaces
		$string = preg_replace('/\s+/','',$string);
		
		//string should be lower beofre md5
		return md5(strtolower($string));
	}
	
	/**
	 * 
	 * Return Buyer-Lib instance Or buyer-Id
	 * @param Boolean $instance : true, if you need buyer-lib instance. default buyer-Id 
	 * 
	 * @return Buyer-Lib instance Or buyer-Id
	 */
	public function getBuyer($instance = false)
	{
		if(!$instance) {
			return $this->buyer_id;
		}

		return PaycartBuyer::getInstance($this->buyer_id);
	}
	
	/**
	 * 
	 * Return buyeraddress to
	 * 
	 * @return Name of person,company etc
	 */
	public function getTo()
	{
		return $this->to;
	}	
	
	/**
	 * @return buyeraddress address field
	 */
	public function getAddress()
	{
		return $this->address;
	}
	
	/**
	 * 
	 * @return buyeraddress city field
	 */
	public function getCity()
	{
		return $this->city;
	}
	
	/**
	 * @return buyeraddress state field
	 */
	public function getStateId()
	{
		return $this->state_id;
	}
	
	/**
	 * @return buyeraddress zipcode field
	 */
	public function getZipcode()
	{
		return $this->zipcode;
	}
	
	/**
	 * @return buyeraddress phone1 field
	 */
	public function getPhone1()
	{
		return $this->phone1;
	}
	
	/**
	 * @return buyeraddress phone2 field
	 */
	public function getPhone2()
	{
		return $this->phone2;
	}
	
	/**
	* @return buyeraddress country field
	 */
	public function getCountryId()
	{
		return $this->country_id;
	}
	
	/**
	 * @return buyeraddress Vat number field
	 */
	public function getVatnumber()
	{
		return $this->vat_number;
	}
	
	public function setBuyerId($buyer_id)
	{
		$this->buyer_id = $buyer_id;
	}
	

}