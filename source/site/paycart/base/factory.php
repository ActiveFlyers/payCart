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
		$string = JString::strtolower($prefix.$type.$name);
		if(isset(self::$_mocks[$string])) {
			return self::$_mocks[$string];
		}
		// Real world instance
		return parent::getInstance($name, $type, $prefix, $refresh);
	}
	
	static function getHelper($name)
	{
		return self::getInstance($name, 'helper');
	}
	
	/**
	 * 
	 * Method invoke to get {Paycart + Site global} configuration object
	 * 
	 * @return JRegistry object
	 */
	public static function getConfig($file = null, $type = 'PHP', $namespace = '')
	{
		if(self::$_config) {
			return self::$_config;
		}

		// load  Joomla Config
		self::$_config = parent::getConfig($file , $type, $namespace);

		$paycartModelConfig = self::getInstance('config', 'model');
		
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
