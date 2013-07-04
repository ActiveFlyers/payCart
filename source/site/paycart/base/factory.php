<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Factory
 * @author Team Readybytes
 */
class PaycartFactory extends Rb_Factory
{
	static function getInstance($name, $type='', $prefix='Paycart', $refresh=false)
	{
		return parent::getInstance($name, $type, $prefix, $refresh);
	}
	
	/**
	 * 
	 * Method invoke to get {Paycart + Site global} configuration object
	 * 
	 * @return Rb_registry object
	 */
	static function getConfig($file = null, $type = 'PHP', $namespace = '')
	{
		static $config;
		if($config) {
			return $config;
		}
		$config = parent::getConfig();
		$config = self::_loadConfig($config->toArray());
		return $config;
	}
	
	/**
	 * Private method for load Paycart global configuration
	 */
	private function _loadConfig($data = Array())
	{
		// PCTODO :: Load Config from Model
		$config = new Rb_Registry($data);
		return $config;
	}
}
