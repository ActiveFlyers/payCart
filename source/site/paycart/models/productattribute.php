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
 * Product Custom Attribute  Model
 */
class PaycartModelProductAttribute extends PaycartModel
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

class PaycartModelformProductAttribute extends PaycartModelform {}

class PaycartModelProductAttributeOption extends PaycartModel
{
	function loadOptions($attributeId, $languageCode)
	{
		$query = new Rb_Query();

		return $query->select('*')
		 		     ->from('#__paycart_productattribute_option as ao')
		 		     ->join('INNER', '#__paycart_productattribute_option_lang as aol ON ao.productattribute_option_id = aol.productattribute_option_id')
		 		     ->where('ao.productattribute_id = '.$attributeId)
		 		     ->where('aol.lang_code = "'.$languageCode.'"')
		 		     ->order('ao.option_ordering')
		 		     ->dbLoadQuery()
		 		     ->loadAssocList();
	}
	
	/**
	 * delete options data from both option and option_lang table
	 */
	function deleteOptions($attributeId, $optionId)
	{
		$query = new Rb_Query();
		
		if(!is_null($optionId)){
			$query->where('b.productattribute_option_id = '. $optionId);
		}
		if(!is_null($attributeId)){
			$query->where('b.productattribute_id = '. $attributeId);
		}	
		
		//Due to some limitation of joomla's delete function, here we used rb_delete to add elements
		return $query->rb_delete(null, 'a.*, b.*')
					 ->from('`#__paycart_productattribute_option_lang` as a')
					 ->from('`#__paycart_productattribute_option` as b')
					 ->where('a.productattribute_option_id = b.productattribute_option_id')
					 ->dbLoadQuery()
					 ->query();
	}
}

class PaycartModellangProductAttribute extends PaycartModel{}

class PaycartModellangProductAttributeOption extends PaycartModel{}

class PaycartModellangProductAttributeOptionLang extends PaycartModel{}
