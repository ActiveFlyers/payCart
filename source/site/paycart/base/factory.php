<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Factory
 * @author Team Readybytes
 */
class PaycartFactory extends Rb_Factory
{ 
 	protected static $_config;
 	
 	
 	/**
 	 * Used for Unit test cases 
	 */
 	private static $_mocks 	= Array();
 	 
	static function getInstance($name, $type='', $prefix='Paycart', $refresh=false)
	{
		// Mocked instance for unit test cases
		if(isset(self::$_mocks[$prefix.$type.$name])) {
			return self::$_mocks[$prefix.$type.$name];
		}
		// Real world instance
		return parent::getInstance($name, $type, $prefix, $refresh);
	}
	
	/**
	 * 
	 * Method invoke to get {Paycart + Site global} configuration object
	 * 
	 * @return JRegistry object
	 */
	public static function getConfig($file = null, $type = 'PHP', $namespace = '', $paycartModelConfig = false)
	{
		if(self::$_config) {
			return self::$_config;
		}

		// load  Joomla Config
		self::$_config = parent::getConfig($file , $type, $namespace);

		// For unit test case, inject mock-object from outside 
		if(!$paycartModelConfig) { 
			$paycartModelConfig = self::getInstance('config', 'model');
		}

		$paycartConfig = $paycartModelConfig->loadRecords();

		$records	=	Array();
		foreach ($paycartConfig as $record) {
			$records[$record->key] = $record->value;
		}

		// Bind paycart config to joomla config
		self::$_config->loadArray($records);
		return self::$config;
	}
}
