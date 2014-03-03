<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	paycartHelper
 * @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Group Helper
 * @author Gaurav Jain
 */

class PaycartHelperGroup extends JObject
{
	/** 
	 * Holds list of avaialable Group Rules
	 * @var Array
	 */
	private $_rules = array();
	
	/**
	 * 
	 * set processor list	 
	 * @param string $className = Group Rule class name
	 * @param array $data  		= GroupRule-info like, file location etc
	 * 			$data = Array (	'title'		  => RULE_NAME,
	 * 							'type'		  => product or buyer
	 * 							'location'	  => FILE_LOCATION,
	 * 							'icon'	  	  => IMAGE_FILE_LOCATION, 
	 * 							'description' => GROUP_RULE_DESC
	 * 						  )
	 */
	public function push($className, $data)
	{		
		$className 	= JString::strtolower($className);
		
		if (!is_object($data)) {
			$data = (object)$data;
		}

		$this->_rules[$className] = $data;
	} 
	
	/**
	 * Get list of all avaialable rules
	 * 
	 * @return Array of all rules
	 */
	public function getList()
	{
		return $this->_rules;
	}
	
	/**
	 * 
	 * Gets instance of Group rule	 
	 * @param  string $className, Group Rule class name 
	 * @param  Array $config, Rule configuration
	 * @throws RuntimeException
	 * 
	 * @return PaycartGrouprule Rule Instance
	 */
	public function getInstance($className, $config = Array())
	{
		$className 	= JString::strtolower($className);
		
		// get all loaded group rules
		if(!isset($this->_rules[$className])) {
			throw new RuntimeException(Rb_Text::sprintf('COM_PAYCART_GROUP_RULE_NOT_EXIST'), $className);
		}
		
		if(!class_exists($className)) {
			// if instance is not exist then need to autoload
			require_once $processorList[$className]['filepath'];
		}
		
		// create rule instance 
		return new $className($config);		
	}	
}