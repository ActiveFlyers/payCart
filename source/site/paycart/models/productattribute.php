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

class PaycartModelformAttribute extends PaycartModelform {}

class PaycartProductAttributeOption extends PaycartModel
{
	function loadOptions($attributeId, $languageCode)
	{
		$query = $this->_db->getQuery(true);
		
	 	return $query->select('*')
		 		     ->from('#__paycart_productattribute_option as ao')
		 		     ->join('INNER', '#__paycart_productattribute_option_lang as aol ON ao.productattribute_option_id = aol.productattribute_option_id')
		 		     ->where('ao.attribute_id = '.$attributeId)
		 		     ->where('aol.lang_code = '.$languageCode)
		 		     ->order('ao.option_ordering')
		 		     ->dbLoadQuery()
		 		     ->loadAssocList();
	}
	
	/**
	 * delete options data from both option and option_lang table
	 */
	function deleteOptions($attributeId)
	{
		$query = $this->_db->getQuery(true);
		
		return $query->delete('a,b')
					 ->from('#_paycart_productattribute_option_lang` as a')
					 ->join('inner','#__paycart_productattribute_option as b on a.productattributeoption_id = b.productattributeoption_id')
					 ->where('b.productattribute_id = '. $attributeId)
					 ->dbLoadQuery()
					 ->query();
	}
}

class PaycartProductAttributeOptionLang extends PaycartModel
{
}
