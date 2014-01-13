<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 */

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Base Lib
* @author Team Readybytes
 */
class PaycartLib extends Rb_Lib
{
	public	$_component	= PAYCART_COMPONENT_NAME;

	static public function getInstance($name, $id=0, $bindData=null, $dummy = null)
	{
		return parent::getInstance(PAYCART_COMPONENT_NAME, $name, $id, $bindData);
	}
	
	/**
	 * @return : Rb_Model
	 */
	public function getModel()
	{
		return PaycartFactory::getInstance($this->getName(), 'Model');
	}

	/**
	 * 
	 * Reload current object
	 * 
	 * @return PaycartLib instance
	 */
	public function reload()
	{
		$data = $this->getModel()->loadRecords(array('id'=>$this->getId()));
		return $this->bind($data[$this->getId()]);
	}
}
