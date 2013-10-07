<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		support+paycart@readybytes.in
  * @author     mManishTrivedi
 */

// no direct access
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Base Table
 * @author mManishTrivedi
 */
class PaycartTable extends Rb_Table
{
	public $_component = PAYCART_COMPONENT_NAME;
	
	/**
	 * 
	 * Method call to Get unique column vlaue string .
	 * If vlaue is not unique then increments a trailing number in a value string.
	 * 
	 * @param String $column	: column name where value should be unique
	 * @param String $vlaue		: A value which is store into column 
	 * @param Integer $id		: Except checking on this id
	 * @param String $style		: The style (default|dash).
	 *  							{ default	: "Title" becomes "Title (2)"
	 * 								  dash		:    "Title" becomes "Title-2" }
	 *
	 * @return string The unique string. (Bond, James Bond)
	 */
	public function getUniqueValue($column, $value, $id=0, $style='dash')
	{
		if (!$value) {
			throw InvalidArgumentException(Rb_Text::_('COM_PAYCART_ALIAS_TITLE_REQUIRED'));
		}
		
		//Sluggify the input string
		$value = PaycartHelper::sluggify($value);
		
		// if title value already exist on same id or title value is available for use 
		// then return sluggify alias
		if(!$this->isValueExist($column, $value,$id)) {
			return $value;
		}
		
		//@PCTODO:: move to helper
		// if Value already have '-'(dash) with numeric-data then remove numeric-data 
		$string = $value;
		if (preg_match('#-(\d+)$#', $string, $matches)) {
			$string = preg_replace('#-(\d+)$#', sprintf('-', ''), $string);
		}
		
		$query 	= new Rb_Query();
		$result = $query->select($column)
						->where("`$column` LIKE '$string%'")
			  			->from($this->_tbl)
			  			->dbLoadQuery()->loadcolumn();

		// build new column value
		while (in_array($value, $result)) {
			$value = JString::increment($value, $style);
		}
		
		return $value;
	}
	
	/**
	 * 
	 * Method to check if an value already exists.
	 *
	 * @param String $column	: Column where $value will check. 
	 * @param String $value 	: Value is a String/integer which is check into $column
	 * @param Integer $id 		: Ignorable id. The table key, When you re-save existing value then should not be generated new value. 
	 *   
	 * @return (bool) True, If alias not exist
	 */
	public function isValueExist($column, $value, $id = 0) 
	{
		$xid = intval($this->translateValueToKey($column, $value));
		if ($xid && $xid != intval($id)) {
			return true;
		}
		return false;
	}
	
	/**
	 * Translate value to key. { Mostly convert value to id }
	 *
	 * @param String $column : Colmn name where match will perform
	 * @param String $value	 : The Matching string
	 * @param String $key	 : Expected column name, default table key (Primary key) 
	 *
	 * @return Key value. Default. The table key like category_id, product_id if found, or false/empty
	 */
	public function translateValueToKey($column, $value, $key = '') 
	{	
		if (!$key) {
			$key = $this->getKeyName();
		}
		
		$query 	= new Rb_Query();
		$result = $query->select($key)
						->where("`$column` = '$value'")
			  			->from($this->_tbl)
			  			->dbLoadQuery()->loadResult();
			  			
		return $result;	
	}
	
	/**
	 * 
	 * Method call to Get unique alias string on title base
	 * @param string $title
	 * 
	 * @return string The unique alias string.
	 */
	public function getUniqueAlias($title, $id=0)
	{
		return $this->getUniqueValue('alias',$title, $id);
	}
}
