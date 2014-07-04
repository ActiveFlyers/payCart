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
 * State Model
 * @author manish
 *
 */
class PaycartModelState extends PaycartModel
{
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_Model::delete()
	 */
	public function delete($pk=null)
	{
		//get Primary key
		if(!$pk ){
			$pk = $this->getId();
		 }
		 
		// first we will delete all depende information
		// delete language specific data
		if (!PaycartFactory::getModelLang('state')->deleteMany(Array('state_id' => $pk))) {
			// @PCTODO:: Specified error 
			return false;
		}
		// now we will delete state
		if (!parent::delete()) {
			return false;
		}
		
		return true;		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_Model::deleteMany()
	 */
	public function deleteMany($condition, $glue='AND', $operator = '=')
	{
		//get all state from this condition
		$query = new Rb_Query();
		$query->select($this->getTable()->getKeyName())
			  ->from($this->getTable()->getTableName());

		$db = JFactory::getDbo();
		
		foreach($condition as $key => $value) {
			$query->where(" $key $operator ". $db->quote($value) , $glue);
		}

		$state_ids = $query->dbLoadQuery()->loadColumn();
		
		// no state available for delete
		if(empty($state_ids)) {
			return true;
		}
		// first we will delete all dependent information
		// delete language specific data
		$in_condition = implode(',', $state_ids);
		
		if (!PaycartFactory::getModelLang('state')->deleteMany(Array('state_id' => "($in_condition)"), 'AND' , 'IN')) {
			// @PCTODO:: Specified error 
			return false;
		}
		
		//remove select clause and add delete clause into query
		$query->clear('select');
		
		$query->delete();
		
		return $query->dbLoadQuery()->query();
		
	}
	/**
	 * (non-PHPdoc)
	 * @see/plugins/system/rbsl/rb/rb/Rb_Model::save()
	 */
	public function save($data, $pk=null, $new=false)
    {		
    	// get state id
    	$state_id = parent::save($data, $pk, $new);
    	
		if ( !$state_id ) {
			return false;
		};
		
		$data['state_id'] = $state_id;
		
		// store languagae data
		$state_lang_id = PaycartFactory::getModelLang($this->getName())->save($data);
		
		return $state_id;
    }
    
    /**
     * (non-PHPdoc)
     * @see /plugins/system/rbsl/rb/rb/Rb_Model::loadRecords()
     */
    public function loadRecords(Array $queryFilters=array(), Array $queryClean = array(), $emptyRecord=false, $indexedby = null)
    {
    	// load all state table records 
    	$records = parent::loadRecords($queryFilters, $queryClean, $emptyRecord, $indexedby);
    	
    	//@PCTODO : bind lang property when you will call for $emptyRecord

    	// bind lang specific data on state 
    	if ( !empty($records) ) {
    		$this->attachLanguageData($records);
    	}
    	return $records;
    }

    /**
     * 
     * Attache language data on records 
     * @param Array $records => array of stdclass 
     * 
     * @return (bool) true
     */
    protected function attachLanguageData(Array $records)
    {
    	$state_Ids = array_keys($records);
    	
    	$in_condition_data	=	implode(',', $state_Ids);
    	$current_lang_code	=	PaycartFactory::getLanguage()->getTag();

    	$filter = Array(
	    	 	'state_id'		=> 	Array(Array('IN', "( $in_condition_data )")),
	    		'lang_code'		=>	$current_lang_code
    		);
    	
    	// getting only one language records
    	$lang_records = PaycartFactory::getModelLang($this->getName())->loadRecords($filter, array(), false, 'state_id');

    	// bind lang property on state record
    	foreach ($records as $state_id => $record) {
    		//@PCTODO :: attache default lang stuff
    		$record->title 				= '';
    		$record->state_lang_id 	= '';
    		
    		if( isset($lang_records[$state_id]) && !empty($lang_records[$state_id]->title)) {
    			$record->title	 			=	$lang_records[$state_id]->title;
    			$record->state_lang_id 	= 	$lang_records[$state_id]->state_lang_id;
    		}
    	}
    	
    	return true;    	
    }
    
    
}
    
 /**
 * 
 * State Lang model
 * @author mManishTrivedi
 *
 */
class PaycartModelLangState extends PaycartModel
{}

class PaycartModelformState extends PaycartModelform 
{}