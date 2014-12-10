<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Color Model
 * @author rimjhim
 *
 */
class PaycartModelColor extends PaycartModelLang
{
	/**
	 * Load options of color attribute
	 * 
	 * @param $languageCode : language code for which options' data are to be loaded
	 * @param $optionIds(optional) : option ids for which data to be loaded,
	 * 								 otherwise all the options of color attribute will be loaded 
	 *
	 * @return array of resultant rows 
	 */
	function loadOptions($productattribute_id, $languageCode = '', Array $optionIds = array())
	{
		$query = new Rb_Query();
		
		if(!empty($optionIds)){
			$query->where('ac.color_id IN('.implode(',', $optionIds).')');
		}
		
		if(!empty($languageCode)){
			$query->where('acl.lang_code = "'.$languageCode.'"');
		}
		
		return $query->select('*')
		 		     ->from('#__paycart_color as ac')
		 		     ->join('INNER', '#__paycart_color_lang as acl ON ac.color_id = acl.color_id')
		 		     ->where('ac.productattribute_id = '.intval($productattribute_id))
		 		     ->dbLoadQuery()
		 		     ->loadAssocList('color_id');
	}
	
	/**
	 * delete options data from both option and option_lang table
	 */
	function deleteOptions($attributeId, $optionId)
	{
		$query = new Rb_Query();
		
		if(!is_null($optionId)){
			$query->where('b.color_id = '. $optionId);
		}
		if(!is_null($attributeId)){
			$query->where('b.productattribute_id = '. $attributeId);
		}	
		
		//Due to some limitation of joomla's delete function, here we used rb_delete to add elements
		return $query->multiDelete(null, 'a.*, b.*')
					 ->from('`#__paycart_color_lang` as a')
					 ->from('`#__paycart_color` as b')
					 ->where('a.color_id = b.color_id')
					 ->dbLoadQuery()
					 ->query();
	}
}

/** 
 * color Table
 * @author rimjhim
 */
class PaycartTableColor extends PaycartTable
{
	function __construct($tblFullName='#__paycart_color', $tblPrimaryKey='color_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}

/** 
 * color language Table
 * @author rimjhim
 */
class PaycartTableColorlang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_color_lang', $tblPrimaryKey='color_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}