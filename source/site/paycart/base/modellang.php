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
    	
    	$lang_code = $this->get('lang_code');
    	//@PCTODO : Remove it 
    	$lang_code= 'en-GB';
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
    	
    	return $return;
    }
    
    public function delete($pk=null)
	{
		if(!parent::delete($pk)){
			return false;
		}
		
		$lang_table = $this->getLanguageTable();		
		$key_name = $this->getTable()->getKeyName();
		
		$query = new Rb_Query();
		$query->delete()
				->from($lang_table->getTableName());
		
		$query->where(" {$key_name} = $pk");

		return $query->dbLoadQuery()->execute();			
	}
}