<?php
/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @contact		suppor+paycart@readybytes.in
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//This file will have only function, which can be used to get data from paycart, without loading the whole system

if(!function_exists('paycart_getCurrentLanguage')){
	function paycart_getCurrentLanguage()
	{
		$lang = Rb_Factory::getLanguage()->getTag();
		$config = paycart_getConfig();
		
		if(in_array($lang, $config['localization_supported_language'])){
			return $lang;			
		}
		
		return $config['localization_default_language'];
	}
}

if(!function_exists('paycart_getConfig')){
	function paycart_getConfig()
	{
		static $config = null;
		
		if($config == null){
			$db = Rb_Factory::getDbo();
			$query = $db->getQuery(true);
			
			$query->select('*')
				->from('#__paycart_config');
				
			$db->setQuery($query);
			$paycartConfig = $db->loadObjectList();
			
			$config	=	Array();
			foreach ($paycartConfig as $record) {
				$value = $record->value;
				
				// if $value is a json string then convert decode it and use
				json_decode($value);
	 			if(json_last_error() == JSON_ERROR_NONE){
	 				$value = json_decode($value);
	 			}
				
				$config[$record->key] = $value;
			}
			
		}
		
		return $config;
	}
}

