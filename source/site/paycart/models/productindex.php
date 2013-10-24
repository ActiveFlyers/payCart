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
 * Indexer Model
 */
class PaycartModelProductIndex extends PaycartModel
{

	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Model::_buildWhereClause()
	 */
	protected function _buildWhereClause(Rb_Query $query, Array $queryFilters) 
	{
		foreach($queryFilters as $key=>$value){
			//support id too, replace with actual name of key
			$key = ($key==='id')? $this->getTable()->getKeyName() : $key;
			
			// only one condition for this key
			if(is_array($value)==false){
				$query->where("`tbl`.`$key` =".$this->_db->Quote($value));
				continue;
			}
			
			// multiple keys are there
			foreach($value as $condition){
				// not properly formatted
				if(is_array($condition)==false){
					continue;
				}
				// first value is condition, second one is value
				$glue = 'AND';
				list($operator, $val)= $condition;
				
				if (3 == count($condition)) {
					list($operator, $val, $glue)= $condition;
				}
				
				$query->where("`tbl`.`$key` $operator ".$this->_db->Quote($val), $glue);
			}
		}
	}
	
	/**
	 * 
	 * Add column into PayCart Indexer table
	 * @param Array $columns => Array('_COLUMN_NAME_' => '_COLUMN_DEFINITIONS_')
	 * 
	 * @return (bool) true on sucess
	 */
	public function addColumn(Array $columns)
	{	
		$tableName = $this->getTable()->getTableName();
		
		// build query
		$query = 'ALTER TABLE '.$this->_db->quoteName($tableName);
		// add multiple columns
		foreach ($columns as $column => $definition ) {
			$query .= "ADD COLUMN $column $definition"; 
		}

		//execute query
		return $this->_db->setQuery($query)->execute();
	}
	
	/**
	 * Drop columns
	 * 
	 * @param Array $columns, Array of columns
	 */
	public function dropColumn($columns)
	{
		if (!is_array($columns)) {
			$columns = Array($columns);
		}
		
		$tableName = $this->getTable()->getTableName();
		
		// build query
		$query = 'ALTER TABLE '.$this->_db->quoteName($tableName);
		// drop multiple columns
		foreach ($columns as $column  ) {
			$query .= "DROP $column "; 
		}
		
		//execute query
		return $this->_db->setQuery($query)->execute();
	}
	
	
	public function XX_getData($content)
	{
		$db = $this->_db;
		
		$whereClause = 	" WHERE MATCH (content) ".
						" AGAINST (".$db->nameQuote($content)." WITH QUERY EXPANSION)";
		
		$query = $this->getQuery();
		
		$query->select('product_id')	//  ->from($this->getTable()->getTableName())
			  ->where($whereClause);
		
		return $db->setQuery($query)->loadobjectList();
	}
}
