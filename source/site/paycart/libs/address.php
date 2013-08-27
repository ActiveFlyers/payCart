<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		Puneet Singhal
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Cart Lib
 */
class PaycartAddress extends PaycartLib
{
	protected $address_id;
	protected $user_id;
	protected $address;
	protected $city;
	protected $state;
	protected $country;
	protected $zipcode;
	protected $longitude;
	protected $latitude;
	protected $preferred;

	/**
	 * 
	 * Enter description here ...
	 * @param int $id
	 * @param $bindData
	 * @param $dummy1
	 * @param $dummy2
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('address', $id, $bindData);
	}
	
	public function reset() 
	{
		$this->address_id			=	0;
		$this->user_id	 			=	0;
		$this->address				=	null;
		$this->city					=	null;
		$this->state				=	null;
		$this->country				= 	null;
		$this->zipcode				=	0;
		$this->longitude			=  	0;
		$this->latitude				=	0;
		$this->preferred			=	0;
		
		return $this;
	}
	
	public function _save($previousObject)
	{
		// If preferred address value is changed then do ensure that 
		// all other addresses are non-preferred and then 
		// save the current address as preferred. 
		if($this->preferred == $previousObject->preferred){
			return parent::_save($previousObject);
		}
		
		$this->getModel()->setNonPreferred($this->user_id);
		return parent::_save($previousObject);
	}
	
		
	/**
	 * 
	 * @param boolean $instance
	 */
	public function getUser($instance = false)
	{
		if($instance){
			return PaycartUser::getInstance($this->user_id);
		}
		return $this->user_id;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function setAddress($address)
	{
		$this->address = $address;
		return $this;
	}
	
	public function getCity()
	{
		return $this->city;
	}
	
	public function setCity($city)
	{
		$this->city = $city;
		return $this;
	}
	
	public function getState()
	{
		return $this->state;
	}
	
	public function setState($state)
	{
		$this->state = $state;
		return $this;
	}
	
	public function getCountry()
	{
		return $this->country;
	}
	
	public function setCountry($country)
	{
		$this->country = $country;
		return $this;
	}
	
	public function getZipcode()
	{
		return $this->zipcode;
	}
	
	public function setZipcode($zipcode)
	{
		$this->zipcode = $zipcode;
		return $this;
	}
	
	public function getLongitude()
	{
		return $this->longitude;
	}
	
	public function setLongitude($longitude)
	{
		$this->longitude = $logitude;
		return $this;
	}
	
	public function getLatitude($latitude)
	{
		return $this->latitude;
	}
	
	public function setLatitude()
	{
		$this->latitude = $latitude;
		return $this;
	}
	
	public function getPreferredAddress()
	{
		return $this->preferred;
	}
	
}