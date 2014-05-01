<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Country Model
 * @author manish
 *
 */
class PaycartModelCountry extends PaycartModel
{
	/**
	 * (non-PHPdoc)
	 * @see/plugins/system/rbsl/rb/rb/Rb_Model::save()
	 */
	function save($data, $pk=null, $new=false)
    {
    	$new = $this->getTable()->load($pk)? false : true;
		
		if(!parent::save($data, $pk, $new)){
			return false;
		};
		
		// store languagae data
		return PaycartFactory::getModelLang($this->getName())->save($data); 
    }
    
    /**
     * (non-PHPdoc)
     * @see /plugins/system/rbsl/rb/rb/Rb_Model::loadRecords()
     */
    public function loadRecords(Array $queryFilters=array(), Array $queryClean = array(), $emptyRecord=false, $indexedby = null)
    {
    	// load all country table records 
    	$records = parent::loadRecords($queryFilters, $queryClean, $emptyRecord, $indexedby);
    	
    	//@PCTODO : bind lang property when you will call for $emptyRecord

    	// bind lang specific data on country 
    	if ( !empty($records) ) {
    		$this->attachLanguageData($records);
    	}
    	
    	return $records;
    	
    }
    
    protected function attachLanguageData($records)
    {
    	$country_Ids = array_keys($records);
    	
    	// county id have string value so we need to add quote (') as suffix and prefix for MySql query
    	$country_Ids = array_map(function($id){ return "'$id'"; }, $country_Ids);
					   


    	$in_condition_data	=	implode(',', $country_Ids);
    	$current_lang_code	=	PaycartFactory::getLanguageTag();

    	$filter = Array(
	    	 	'country_id'	=> 	Array(Array('IN', "( $in_condition_data )")),
	    		'lang_code'		=>	$current_lang_code
    		);
    	
    	$lang_records = PaycartFactory::getModelLang($this->getName())->loadRecords($filter, array(), false, 'country_id');

    	// bind lang property on country record
    	foreach ($records as $country_id => $record) {
    		//@PCTODO :: attache default lang stuff
    		$record->title 				= '';
    		$record->country_lang_id 	= '';
    		
    		if( isset($lang_records[$country_id]) && !empty($lang_records[$country_id]->title)) {
    			$record->title	 			=	$lang_records[$country_id]->title;
    			$record->country_lang_id 	= 	$lang_records[$country_id]->country_lang_id;
    		}
    	}
    	
    	return true;    	
    }
    
    
}
    
 /**
 * 
 * Country Lang model
 * @author mManishTrivedi
 *
 */
class PaycartModelLangCountry extends PaycartModel
{}

class PaycartModelformCountry extends PaycartModelform 
{}