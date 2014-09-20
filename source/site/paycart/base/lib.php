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
		return PaycartFactory::getModel($this->getName());
	}

	/**
	 * 
	 * Reload current object
	 * 
	 * @return PaycartLib instance
	 */
	public function reload()
	{
		// @PCTODO : verify, reset is required or not
		return $this->load($this->getId());
	}
	
	/**
	 * Overriding it so that model data can be populated on lib object 
	 * 
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::save()
	 */
	protected function _save($previousObject)
	{
		// save to data to table
		$id = parent::_save($previousObject);

		//if save was not complete, then id will be null, do not trigger after save
		if(!$id){
			return false;
		}
		
		// correct the id, for new records required
		$this->setId($id);
		$this->reload();
		
		return $id;
	}
}
