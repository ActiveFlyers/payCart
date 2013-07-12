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
 * Base Table
* @author Team Readybytes
 */
class PaycartTable extends Rb_Table
{
	public $_component = PAYCART_COMPONENT_NAME;
	
	/**
	 * 
	 * Method call to Get unique alias string on title base
	 * @param string $title
	 * 
	 * @return string The unique alias string.
	 */
	public function getUniqueAlias($title, $id=0)
	{
		if (!$title) {
			throw InvalidArgumentException(Rb_Text::_('COM_PAYCART_ALIAS_TITLE_REQUIRED'));
		}
		
		//Sluggify the input string
		$alias = PaycartHelper::sluggify($title);
		
		// if alias already exist on same id or alias is availble for use 
		// then return sluggify alias
		if(!$this->isAliasExist($alias,$id)) {
			return $alias;
		}
		
		$query 	= new Rb_Query();
		$result = $query->select('alias')
						->where("`alias` LIKE '$alias%'")
			  			->from($this->_tbl)
			  			->dbLoadQuery()->loadcolumn();

		// build new alias
		while (in_array($alias, $result)) {
			$alias = JString::increment($alias, 'dash');
		}
		
		return $alias;
	}
	
	/**
	 * Translate alias to id.
	 *
	 * @param string $alias The alias string
	 *
	 * @return numeric value The Category id if found, or false/empty
	 */
	public function translateAliasToID($alias) 
	{	
		$query 	= new Rb_Query();
		$result = $query->select($this->getKeyName())
						->where("`alias` = '$alias'")
			  			->from($this->_tbl)
			  			->dbLoadQuery()->loadResult();
			  			
		return $result;	
	}
	
	/**
	 * Method to check if an alias already exists.
	 *
	 * @param string $alias The object alias
	 * @param string $id The table key, When you re-save existing alias then should not be generated new alais. 
	 *   
	 * @return (bool) True, If alias not exist
	 */
	public function isAliasExist($alias, $id = 0) 
	{
		$xid = intval($this->translateAliasToID($alias));
		if ($xid && $xid != intval($id)) {
			return true;
		}
		return false;
	}
}
