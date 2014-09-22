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
	
	/**
	 * 
	 * Method call to Get unique alias string
	 * 
	 * @param string $alias alias 	which ned to be properly generated 
	 * @param int	 $parent_id 	parent id of category
	 * @param int	 $id			optional : id of category
	 * @param string $style
	 * 
	 * @return string The unique alias string.
	 */
	public static function getNewAlias($alias, $parent_id, $id = 0, $style = 'dash')
	{		
		$alias  = PaycartHelper::sluggify($alias);//Sluggify the input string
	
		//@PCTODO:: move to helper
		// if Value already have '-'(dash) with numeric-data then remove numeric-data 
		$string = $alias;
		if (preg_match('#-(\d+)$#', $string, $matches)) {
			$string = preg_replace('#-(\d+)$#', sprintf('-', ''), $string);
		}
		
		$result = self::getRecordsOfAlias($alias, $parent_id, $id);		

		// build new column value
		while (in_array($alias, $result)) {
			$alias = JString::increment($alias, $style);
		}
		
		return $alias;		
	}
	
	public static function getRecordsOfAlias($alias, $parent_id, $id = 0)
	{
		$query 	= new Rb_Query();
		$query->select('cl.`alias`')
						->where("cl.`alias` LIKE '".$alias."%'")
						->where('cl.`productcategory_id`= c.`productcategory_id`');
		
		if(!empty($id)){
			$query->where("cl.`productcategory_id` <> ".intval($id));
		}
		
		return $query->from('`#__paycart_productcategory_lang`  as cl')
			  	 	 ->join('inner', '`#__paycart_productcategory` as c on c.`parent_id` = '.intval($parent_id))
			  		 ->dbLoadQuery()->loadcolumn();
	}
	
	public function check()
	{
		$this->alias = trim($this->alias);

		if (empty($this->alias)){
			$table = PaycartFactory::getInstance('productcategory', 'Table');
			$table->load($this->productcategory_id);
		
			$this->alias = self::getNewAlias($this->title, $table->parent_id, $this->productcategory_id);
		}
		
		return true;
	}	
}
