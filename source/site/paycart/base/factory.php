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
	
	/**
	 * 
	 * Invoke to get Paycart Helper instance
	 * @param string $name : Entity name
	 * 
	 * @return PaycartHelper Entity 
	 */
	public static function getHelper($name)
	{
		return self::getInstance($name, 'helper');
	}
	
	/**
	 * 
	 * Invoke to get Paycart Model instance
	 * @param string $name : Entity name
	 * 
	 * @return PaycartModel Entity 
	 */
	public static function getModel($name)
	{
		return self::getInstance($name, 'model');
	}
	
	/**
	 * 
	 * Invoke to get Paycart Modelform instance
	 * @param string $name : Entity name
	 */
	public static function getModelForm($name)
	{
		// Make sure $name Model is already loaded otherwise it will generate issue  
		return self::getInstance($name, 'modelform');
	}
	
	
	/**
	 * 
	 * Invoke to get Paycart Modellang instance
	 * @param string $name : Entity name
	 */
	public static function getModelLang($name)
	{
		return self::getInstance($name, 'modellang');
	}
	
	/**
	 * 
	 * Invoke to get Paycart Table instance
	 * @param string $name : Entity name
	 */
	public static function getTable($name)
	{
		return self::getInstance($name, 'table');
	}
	
	/**
	 * 
	 * Invoke to get Paycart Table instance
	 * @param string $name : Entity name
	 */
	public static function getTableLang($name)
	{
		return self::getInstance($name, 'tablelang');
	}
	
	/**
	 * 
	 * Invoke to get query object
	 * @return Rb_query object
	 */
	public static function getQuery() 
	{
		return new Rb_Query();
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
			$value = $record->value;
			
			// if $value is a json string then convert decode it and use
			json_decode($value);
 			if(json_last_error() == JSON_ERROR_NONE){
 				$value = json_decode($value);
 			}
			
			$records[$record->key] = $value;
		}

		// Bind paycart config to joomla config
		self::$_config->loadArray($records);
		return self::$config;
	}
	
	public static function setConfig($data, $file = null, $type = 'PHP', $namespace = '')
	{	
		$paycartModelConfig = self::getInstance('config', 'model');
		if($paycartModelConfig->save($data)){
			foreach($data as $key => $value){
				self::$_config->set($key, $value);
			}
			
			return true;
		}
		
		return false;				
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
	public static function getProcessor($type, $className, $config = Array()) 
	{
		return self::getHelper('processor')->getInstance($type, $className, $config);
	}
	
	/**
	 * 
	 * Invoke to get group rule instance
	 * @param  string $type, Group type {user, product}
	 * @param  string $className, Group class name 
	 * @param  Array $config, group configuration
	 * @throws RuntimeException
	 * 
	 * @return Group Instance
	 */
	public static function getGrouprule($type, $className, $config = Array()) 
	{
		return self::getHelper('group')->getInstance($type, $className, $config);
	}
	
	protected static $_pcCurrentLanguageCode = null; 
	public static function getPCCurrentLanguageCode()
	{
		return self::$_pcCurrentLanguageCode;
	}
	
	public static function setPCCurrentLanguageCode($lang_code)
	{
		if(self::$_pcCurrentLanguageCode != null && self::$_pcCurrentLanguageCode != $lang_code){
			throw Exception('Can not change current language of Paycart in same request.');
		}
		
		self::$_pcCurrentLanguageCode = $lang_code;
	}
	
	public static function getPCDefaultLanguageCode()
	{
		return PaycartFactory::getConfig()->get('localization_default_language');
	}
	
	public static function getPCSupportedLanguageCode()
	{
		return PaycartFactory::getConfig()->get('localization_supported_language');
	}
	
	public static function getJoomlaCurentLanguageCode()
	{
		return PaycartFactory::getLanguage()->getTag();
	}
	
	public static $validator = null;
	public static function getValidator()
	{	
		if(!self::$validator){
			self::$validator = new PaycartValidator();
		}
		
		return self::$validator;
	}
}
