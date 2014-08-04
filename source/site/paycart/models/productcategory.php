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
 * 
 * Productcategory Model
 * @author manish
 *
 */
class PaycartModelProductcategory extends PaycartModelLang
{
	/**
	 * 
	 * Check if the current alias is valid else create new
	 * @param sting $alias 		: alias to be checked for validity
	 * @param integer $parentId : parent id of the current category
	 * @param integer $pk 		: Except checking on this id while validating alias
	 * @param String $style		: The style (default|dash).
	 *  							{ default	: "Title" becomes "Title (2)"
	 * 								  dash		:    "Title" becomes "Title-2" }
	 *
	 * @return string The valid string
	 */
	public function getValidAlias($alias, $parentId, $pk, $style='dash')
	{	
		if (!$alias) {
			throw InvalidArgumentException(Rb_Text::_('COM_PAYCART_ALIAS_REQUIRED'));
		}
		
		$query 	= new Rb_Query();		
		if($pk){
			$query->where("cl.`productcategory_lang_id` <>".$pk);
		}
		
		$helper = new PaycartHelper();
		$alias  = $helper->sluggify($alias);//Sluggify the input string
		
		$result = $query->select('cl.`productcategory_lang_id`')
						->where("cl.`alias` = '".$alias."'")
						->where('cl.`productcategory_id`= c.`productcategory_id`')
			  			->from($this->getLanguageTable()->get('_tbl'). ' as cl')
			  			->join('inner', '`#__paycart_productcategory` as c on c.`parent_id` = '.$parentId)
			  			->dbLoadQuery()->loadResult();
			
		if(!$result){
			return $alias;
		}
		
		// if Value already have '-'(dash) with numeric-data then remove numeric-data 
		$string = $alias;
		if (preg_match('#-(\d+)$#', $string, $matches)) {
			$string = preg_replace('#-(\d+)$#', sprintf('-', ''), $string);
		}
		
		$query 	= new Rb_Query();
		$result = $query->select('alias')
						->where("`alias` LIKE '$string%'")
			  			->from($this->getTable()->get('_tbl'))
			  			->dbLoadQuery()->loadcolumn();

		// build new column value
		while (in_array($alias, $result)) {
			$alias = JString::increment($alias, $style);
		}
		
		return $alias;
	}

	/**
	 * Overring this method to order categories according to lft proper 
	 * i.e parent->child ordering  
	 * @see plugins/system/rbsl/rb/rb/Rb_Model::_buildWhereClause()
	 */
	function _buildWhereClause(Rb_Query $query, Array $queryFilters)
	{
		parent::_buildWhereClause($query,$queryFilters);
		return $query->order('lft');
	}
}

class PaycartModelformProductCategory extends PaycartModelform { }

class PaycartTableProductcategory extends PaycartTableNested
{
	public function __construct($tblFullName='#__paycart_productcategory', $tblPrimaryKey='productcategory_id', $db=null)
	{
		$return  = parent::__construct('#__paycart_productcategory', 'productcategory_id', $db);
		
		// IMP : need to unset alias field as ou table does not have this column
		unset($this->alias);
		return $return;
	}
}

class PaycartTableProductcategorylang extends PaycartTable
{	
	function __construct($tblFullName='#__paycart_productcategory_lang', $tblPrimaryKey='productcategory_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}
