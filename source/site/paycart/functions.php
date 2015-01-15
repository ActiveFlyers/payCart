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


if(!function_exists('paycart_getProductCategories')){
	function paycart_getProductCategories($lang_code)
	{
		static $categories = array();
		if(array_key_exists($lang_code, $categories)){
			return $categories[$lang_code];
		}
		
		$db = Rb_Factory::getDbo();		
		$query = "SELECT 1, tbl.*,lang_tbl.productcategory_lang_id,lang_tbl.title,lang_tbl.alias,lang_tbl.lang_code,lang_tbl.description,lang_tbl.metadata_title,lang_tbl.metadata_keywords,lang_tbl.metadata_description
				  FROM #__paycart_productcategory AS tbl
				  LEFT JOIN #__paycart_productcategory_lang as lang_tbl 
  				  ON (tbl.productcategory_id = lang_tbl.productcategory_id 
  				  AND lang_tbl.lang_code = ".$db->quote($lang_code).")";
		
		$categories[$lang_code] = $db->setQuery($query)->loadObjectList('productcategory_id');
		return $categories[$lang_code];
	}
}

