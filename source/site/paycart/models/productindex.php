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
}
