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

	/**
	 * 
	 * Class Constructor
	 * @param Array $config
	 */
	public function __construct($config = array())
	{
		// PCTODO :: remove it
		$this->app = PaycartFactory::getApplication();
		parent::__construct();
	}
	
	static public function getInstance($name, $id=0, $data=null, $dummy = null)
	{
		return parent::getInstance(PAYCART_COMPONENT_NAME, $name, $id, $data);
	}
	
	/**
	 * Get unique object alias string.
	 *
	 * @param string $id The object id
	 * @param string $alias The object alias
	 *
	 * @return string The unique object alias string
	 * PCTODO :: Should be move into helper or might be base class
	 */
	public function getUniqueAlias() 
	{
		// alias replace with title if alias name is empty
		if (empty($this->alias)) {
			$this->alias = $this->getTitle(); 
		}

		//Sluggify the input string
		$this->alias = PaycartHelper::sluggify($this->alias);
		
		// @IMP :: Here category_id is mandatory params  
		while ($this->isAliasExists($this->alias, $this->getId())) {
			$this->alias = JString::increment($this->alias, 'dash');
		}
		
		return $this->alias;
	}
	
	/**
	 * Method to check if an alias already exists.
	 *
	 * @param string $alias The object alias
	 * @param string $id The category id, When you re-save existing alias then should not be generated new alais. 
	 *   
	 * @return string The object id if found, or 0
	 */
	public function isAliasExists($alias, $id = 0) 
	{
		$xid = intval($this->translateAliasToID($alias));
		if ($xid && $xid != intval($id)) {
			return true;
		}
		return false;
	}
}
