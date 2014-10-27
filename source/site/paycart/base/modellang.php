<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 */

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Base Model for multilingual Entity
 * @author Gaurav Jain
 */
class PaycartModelLang extends PaycartModel
{
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
    
 	protected function _buildQueryJoins(Rb_Query &$query)
    {
    	parent::_buildQueryJoins($query);
    	
    	// @PCTODO : will be removed when multi language support will be given
    	$lang_code = PaycartFactory::getCurrentLanguageCode();    	 
    	
    	if(empty($lang_code)){
    		throw new Exception(JText::_('COM_PAYCART_ERROR_LANG_CODE_MISSING'));
    	}
    	
    	$key = $this->getTable()->getKeyName();
    	$condition = $this->getLanguageTableName().' as lang_tbl ON (tbl.'.$key.' = lang_tbl.'.$key.' AND lang_tbl.lang_code = '.$this->_db->quote($lang_code).')';
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
}