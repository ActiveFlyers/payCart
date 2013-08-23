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
class PaycartUser extends PaycartLib
{
	protected $user_id;
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
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('user', $id, $bindData);
	}
	
	public function reset() 
	{		
		$this->user_id	 			=	0;
		$this->realname					=	null;
		$this->username				=	null;
		$this->email				=	null;
		$this->password				= 	0;
		$this->usertype				= 	null;	
		$this->mobile				=	0;
		$this->params				=  	new Rb_Registry();
		
		return $this;
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
	
}