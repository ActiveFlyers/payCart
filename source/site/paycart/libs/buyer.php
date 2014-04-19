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
 * Buyer Lib
 */
class PaycartBuyer extends PaycartLib
{
	protected $buyer_id;
	protected $realname;
	protected $username;
	protected $email;
	protected $password;
	protected $usertype;
	protected $mobile;
	protected $params;

	/**
	 * 
	 * Enter description here ...
	 * @param int $id
	 * @param $bindData
	 * @param $dummy1
	 * @param $dummy2
	 * 
	 * @return PaycartBuyer
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('buyer', $id, $bindData);
	}
	
	public function reset() 
	{		
		$this->buyer_id	 			=	0;
		$this->realname					=	null;
		$this->username				=	null;
		$this->email				=	null;
		$this->password				= 	0;
		$this->usertype				= 	null;	
		$this->mobile				=	0;
		$this->params				=  	new Rb_Registry();
		$this->_addresses			=	array();
		
		return $this;
	}
	
	public function bind($data, $ignore=array())
	{
		parent::bind($data, $ignore);
		$buyer_id = $this->getId();
		
		return $this->_loadAddresses($buyer_id);
	}
	
	protected function _loadAddresses($buyer_id = 0)
	{
		$this->_addresses = PaycartFactory::getInstance('address', 'model')->loadRecords(array('buyer_id' => $buyer_id));
		return $this;
	}
		
	public function getUsername()
	{
		return $this->username;
	}
	
	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}
	
	public function getRealname()
	{
		return $this->realname;
	}
	
	public function setRealname($realname)
	{
		$this->realname = $realname;
		return $this;
	}
	
	public function getUsertype()
	{
		return $this->usertype;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}
	
	public function getMobile()
	{
		return $this->mobile;
	}
	
	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
		return $this;
	}
	
	
	public function getParams($object = true)
	{
		if($object){
			return $this->params->toObject();
		}

		return $this->params->toArray();
	}
	
	public function getShippingAddress()
	{
		// @TODO :
	}
	
	public function getBillingAddress()
	{
		// @TODO :
	}
}
