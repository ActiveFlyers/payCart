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
 * Base Model
* @author Team Readybytes
 */
class PaycartModel extends Rb_Model
{
	public	$_component	= PAYCART_COMPONENT_NAME;	
	/**
	 * 
	 * Array of those column which are unique. It will be checked (uniqueness) before save Product object 
	 * @var Array
	 */
	protected $uniqueColumns = Array();
	
	/**
	 * This should be vaildate and filter the data
	 * 
	 * @param unknown_type $data
	 * @param string $pk
	 * @param array $filter
	 * @param array $ignore
	 *
	 * @throws UnexpectedValueException
	 * 
	 * @return boolena value if validateion success else false.
	 *  
	 * @see plugins/system/rbsl/rb/rb/Rb_Model::validate()
	 */
	public function validate(&$data, $pk=null,array $filter = array(),array $ignore = array())
	{
		// Availble column must be unique
		if (!empty($this->uniqueColumns)) {
			$table 	 	= $this->getTable();
			$tableKey 	= $table->getKeyName();
			foreach ($this->uniqueColumns as $column) {
				// if unique column is empty then fire extension
				if (!$data[$column]) {
					// unique key empty not allwoed
					throw new UnexpectedValueException(Rb_Text::sprintf('COM_PAYCART_UNIQUE_KEY_EMPTY',$column));
				}
				$data[$column] = $table->getUniqueValue($column, $data[$column], $data[$tableKey]);
			}
		}
		
		return true;
	}
}
