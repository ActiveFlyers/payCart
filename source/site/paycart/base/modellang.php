<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Base Model for multilingual Entity
 * @author Gaurav Jain
 */
class PaycartModelLang extends PaycartModel
{
	public $lang_code = null;
	
	public function getLanguageTable()
	{
		$tableName = $this->getName().'lang';
		return $this->getTable($tableName);
	}
	
	public function getLanguageTableName()
	{
		return $this->getLanguageTable()->getTableName();
	}
	
	protected function _buildQueryFields(Rb_Query &$query)
    {
    	parent::_buildQueryFields($query);
    			
		$key_name 	= $this->getTable()->getKeyName();
		$columns 	= $this->getLanguageTable()->getProperties();
		unset($columns[$key_name]);
		
		foreach($columns as $key => $value){		
			$query->select('lang_tbl.'.$key);
		}
    }
    
    /**
     * (non-PHPdoc)
     * @see plugins/system/rbsl/rb/rb/Rb_AbstractModel::_populateGenericFilters()
     * 
     * Overridden this function so that we can add filters of lang table as well
     */
    public function _populateGenericFilters(Array &$filters=array())
	{
		parent::_populateGenericFilters($filters);
		
    	$table = $this->getTable();
		if(!$table)
			return;

		$key_name 	= $table->getKeyName();
		$columns 	= $this->getLanguageTable()->getProperties();
		unset($columns[$key_name]);
			
		$app  = Rb_Factory::getApplication();

		//$data = array();
		$context = $this->getContext();

		foreach($columns as $k => $v)
		{
			$filterName  = "filter_{$context}_{$k}";
			$oldValue= $app->getUserState($filterName);
			$value = $app->getUserStateFromRequest($filterName ,$filterName);
			
			//offset is set to 0 in case previous value is not equals to current value
			//otherwise it will filter according to the pagination offset
			if(!empty($oldValue) && $oldValue != $value){
				$filters['limitstart']=0;
			}

			$filters[$context][$k] = $value;
		}

		return;
    }

    /**
     * (non-PHPdoc)
     * @see plugins/system/rbsl/rb/rb/Rb_Model::_buildQueryFilter()
     * 
     * Overridden this function so that table alias of proper table can be added  
     */
	protected function _buildQueryFilter(Rb_Query &$query, $key, $value, $tblAlias='`tbl`.')
    {   	
    	// Only add filter if we are working on bulk reocrds
		if($this->getId()){
			return $this;
		}

		//change alias, if key is related to langauge table
		$lang_keys = array_keys($this->getLanguageTable()->getProperties());
    	if(in_array($key, $lang_keys)){
    		$tblAlias='`lang_tbl`.';
    	}
		
    	Rb_Error::assert(isset($this->filterMatchOpeartor[$key]), "OPERATOR FOR $key IS NOT AVAILABLE FOR FILTER");
    	Rb_Error::assert(is_array($value), JText::_('PLG_SYSTEM_RBSL_VALUE_FOR_FILTERS_MUST_BE_AN_ARRAY'));

    	$cloneOP    = $this->filterMatchOpeartor[$key];
    	$cloneValue = $value;
    	
    	while(!empty($cloneValue) && !empty($cloneOP)){
    		$op  = array_shift($cloneOP);
    		$val = array_shift($cloneValue);

			// discard empty values
    		if(!isset($val) || '' == trim($val))
    			continue;

    		//trim value before adding it to condition
    		$val = trim($val);
    		
    		if(strtoupper($op) == 'LIKE'){
	    	  	$query->where("$tblAlias`$key` $op '%{$val}%'");
				continue;
	    	}

    		$query->where("$tblAlias`$key` $op '$val'");
	    		
    	}
    }
    
 	protected function _buildQueryJoins(Rb_Query &$query)
    {
    	parent::_buildQueryJoins($query);
    	
    	if(empty($this->lang_code)){
			$currentLanguage = PaycartFactory::getPCCurrentLanguageCode();
			//if current language is empty then use default language code
    		$this->lang_code = empty($currentLanguage)?PaycartFactory::getPCDefaultLanguageCode():$currentLanguage;
    	}
    	
    	$key = $this->getTable()->getKeyName();
    	$condition = $this->getLanguageTableName().' as lang_tbl ON (tbl.'.$key.' = lang_tbl.'.$key.' AND lang_tbl.lang_code = '.$this->_db->quote($this->lang_code).')';
		$query->leftJoin($condition);		
    }
    
    protected function _save($table, $data, $pk=null, $new=false)
    {
    	$return = parent::_save($table, $data, $pk, $new);
    	if(!$return){
    		return $return;
    	}
    	
    	if(!$this->_saveLanguageData($data, $return, $new)){
    		return false;
    	}
    	
    	return $return;
    }
    
    protected function _saveLanguageData($data, $return, $new)
    {
    	// get language table
    	$langTable = $this->getLanguageTable();
    	if(!$langTable){
			$this->setError(Rb_Text::_('COM_PAYCART_TABLE_DOES_NOT_EXIST'));
			return false;
		}
		
    	// modify the entity id, in the data    	
    	$data[$this->getTable()->getKeyName()] = $return;
    	
    	// find the primary key value if set in data
    	$langPk = null;   	
    	if(isset($data[$langTable->getKeyName()])){
    		$langPk = $data[$langTable->getKeyName()];
    	}
    	
    	if(!parent::_save($langTable, $data, $langPk, $new)){
    		//@PCTODO : what should I return here
    		return false;
    	}
    	
    	// if it is a new record then copy the data in all supported langauge    	
    	if(!$langPk){
    		$current_language = $data['lang_code'];
    		$old_languages	= PaycartFactory::getPCSupportedLanguageCode();
    		$languagesToBeAdded = array_diff($old_languages, array($current_language));
    		foreach ($languagesToBeAdded as $newLang){
				if(!$this->copyDefaultLanguageData($current_language, $newLang)){
					//@PCTODO : What to do if any error occurs
				}
			}
    	}
    	return true;
    }
    
    public function delete($pk=null)
	{
		//try to calculate automatically
		if($pk === null){
			$pk = (int) $this->getId();
		}
		
		if(!$pk){
			$this->setError('Invalid itemid to delete for model : '.$this->getName());
			return false;
		}
		
		$key_name = $this->getTable()->getKeyName();
		
		$query = new Rb_Query();
		$query->select('*')
				->from($this->getLanguageTableName())
				->where('`'.$key_name.'` = '.$this->_db->quote($pk));
				
		$records 	= $query->dbLoadQuery()->loadObjectList();

		$lang_table = $this->getLanguageTable();
		$lang_key 	= $lang_table->getKeyName();
		foreach($records as $record){
			//try to delete
			// Load table so as to get all the available table data for other processing
			// Like joomla tag untagging works on table data
		    if($lang_table->load($record->$lang_key) && $lang_table->delete($record->$lang_key)){
		    	continue;
		    }
		    
		    //some error occured
			//@PCTODO : should we continue to deleted or not
			$this->setError($lang_table->getError());
		}
		
		// afte deleting all other data not delete main data
		return parent::delete($pk);	
	}
	
	public function loadRecords(Array $queryFilters=array(), Array $queryClean = array(), $emptyRecord=false, $indexedby = null)
	{	
		// clean where clause becasue of caching
		// if the query is executed then it won't be executed againg after change language
		$query = $this->getQuery();
		$query->clear('join');
		$this->_buildQueryJoins($query); 
		return parent::loadRecords($queryFilters, $queryClean, $emptyRecord, $indexedby);
		
//		$defaultLanguage = PaycartFactory::getPCDefaultLanguageCode();
//		
//		if($defaultLanguage == $this->_language_code){
//			return $currentLanguageRecords;
//		}
//		
//		$this->_language_code = $defaultLanguage; 
//		
//		$query = $this->getQuery();
//		$query->clear('join');
//		$this->_buildQueryJoins($query);
//
//		$defaultLanguageRecords = parent::loadRecords($queryFilters, $queryClean, $emptyRecord, $indexedby);
//
//		$keyName = $this->getLanguageTable()->getKeyName();
//		foreach($currentLanguageRecords as $key => &$record ){
//			if(!isset($record->product_lang_id) && empty($record->product_lang_id)){
//				if(!isset($defaultLanguageRecords[$key])){
//					throw new Exception('Default Language data is missing');
//				}
//				
//				$record = $defaultLanguageRecords[$key];
//				$record->lang_code = $currentLanguage;
//				$record->$keyName = 0;
//			}
//		}
//		
//		return $currentLanguageRecords;
	}
	
	public function copyDefaultLanguageData($prev_lang, $new_lang)
	{
		$db = PaycartFactory::getDbo();
		
		$langTable 		= $this->getLanguageTable();
		$langTableName 	= $langTable->getTableName();
		$fields 		= $langTable->getFields();		
		// unset primey key 
		unset($fields[$langTable->getKeyName()]);
		
		// query for inserting value
		$insertPart = '';
		$selectPart = '';		
		foreach($fields as $fieldname => $field){
			$insertPart[] = '`'.$fieldname.'`';
			
			if($fieldname == 'lang_code'){
				$selectPart[] = "'".$new_lang."'";				
			}
			else{
				$selectPart[] = '`'.$fieldname.'`';
			}
		}
		$sql = 'INSERT IGNORE INTO `'.$langTableName.'` ('.implode(', ', $insertPart).') '.
				'SELECT '.implode(', ', $selectPart).' FROM `'.$langTableName.'` '.
				'WHERE `lang_code` = '.$db->quote($prev_lang);
		
		$db->setQuery($sql);
		return $db->query();		
	}
	
	
	public function deleteLanguageData($languagesToBeRemoved)
	{
		$languagesToBeRemoved = is_array($languagesToBeRemoved) ? $languagesToBeRemoved : array($languagesToBeRemoved);
		
		$tablename = $this->getLanguageTableName();
		
		$db 	= Rb_Factory::getDbo();		
		$query 	= $db->getQuery(true);
		$query->delete($tablename)
				->where('`lang_code` IN ("'.implode('", "', $languagesToBeRemoved).'")');
				
		$db->setQuery($query);
		return $db->query();						
	}
}
