<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Product Custom Attribute  Model
 */
class PaycartModelAttribute extends PaycartModel
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
			if(!is_array($value)){
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
				
				$query->where("`tbl`.`$key` $operator ".$val, $glue);
			}
		}
	}
	
}

class PaycartModelformAttribute extends PaycartModelform 
{
	 
	// Load specific atrribute type configuration xml
	protected function preprocessForm($form, $data)
	{
		if($data['type']) {
			// @PCTODO :: Path should be injected from outside.
			// All Attribute config will be availble with form object { params->attribute_config}
			$form->loadFile(PAYCART_PATH_CUSTOM_ATTRIBUTES.'/'.$data['type'].'.xml', false);
		}
		return parent::preprocessForm($form, $data);
	} 
}