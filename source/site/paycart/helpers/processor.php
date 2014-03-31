<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	paycartHelper
 * @contact		support+paycart@readybytes.in
 * @author 		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Processor Helper
 * @author mManishTrivedi
 */

class PaycartHelperProcessor extends JObject
{
	/** 
	 * Holds list of avaialable processor
	 * @var Array
	 */
	private $_processors = array();
	
	/**
	 * 
	 * set processor list
	 * @param string $type 		= Processor type like discountrule,taxrule etc
	 * @param string $className = Processor class name
	 * @param array $data  		= Processor-info like class name, file location etc
	 * 			$data = Array (	'title'		  => PROCESSOR NAME,
	 * 							'location'	  => FILE_LOCATION,
	 * 							'icon'	  	  => IMAGE_FILE_LOCATION,
	 *  						'tooltip'	  => PROCESSOR_TOOLTIP, 
	 * 							'description' => PROCESSOR_DESC
	 * 						  )
	 *  
	 */
	public function push($type, $className, $data)
	{
		$type 		= JString::strtolower($type);
		$className 	= JString::strtolower($className);
		
		if (!is_object($data)) {
			$data = (object)$data;
		}

		$this->_processors[$type][$className] = $data;
	} 
	
	/**
	 * Get list of all avaialable processor
	 * 
	 * @return Array of all processors
	 */
	public function getList($type)
	{
		$type 		= JString::strtolower($type);
		
		// if any processor of this type is not available yet then return fasle
		if(!isset($this->_processors[$type])){
			return false;
		}
		
		//load specific type plugins
		return $this->_processors[$type];
	}
	
	/**
	 * 
	 * Invoke to get processor instanse
	 * @param  string $type, Processor type {taxrule, discountrule, shippingrule}
	 * @param  string $className, Processor class name 
	 * @param  Array $config, Processor configuration
	 * @throws RuntimeException
	 * 
	 * @return Processor Instance
	 */
	public function getInstance($type, $className, $config = Array())
	{
		$type 		= JString::strtolower($type);
		$className 	= JString::strtolower($className);
		
		// get all loaded processor
		if(!isset($this->_processors[$type][$className])) {
			throw new RuntimeException(Rb_Text::sprintf('COM_PAYCART_PROCESSOR_NOT_EXIST'), $className);
		}
		
		if(!class_exists($className)) {
			// if instance is not exist then need to autoload
			require_once $processorList[$type][$className]['filepath'];
		}
		
		// create processor instane 
		return new $className($config);		
	}
	
}
