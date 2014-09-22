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
 * Product Model
 */
class PaycartModelProduct extends PaycartModelLang
{
	/**
	 * 
	 * Array of those column which are unique. It will be checked (uniqueness) before save Product object 
	 * @var Array
	 */
	protected $uniqueColumns = Array('sku', 'alias');
	
	/**
	 * 
	 * Validation check beofore save:
	 * 1#. Don't support variant of variant.(Multi-level variation) 
	 *
	 * @see components/com_paycart/paycart/base/PaycartModel::validate() 
	 */
//	public function validate(&$data, $pk=null,array $filter = array(),array $ignore = array()) 
//	{
//		// 1#. No need to create variant of variant
//		if ($data['variation_of']) {
//			$product  = PaycartProduct::getInstance($data['variation_of']);
//			if(!$product || ($product->getVariationOf() && $product->getVariationOf() != $product->getId())) {
//				// PCTODO :: Dont need to fire exception juts set variation_of property    
//				// Notify to user we dont support multi-level variation
//				throw new UnexpectedValueException(Rb_Text::_('COM_PAYCART_NOT_SUPPORT_MULTILEVEL_VARIATION'));
//			}
//		}
//		// Invoke parent validation
//		return parent::validate($data, $pk, $filter, $ignore);
//	}

	public function updateStock($productId, $quantity)
	{
		$query = new Rb_Query();
		
		return $query->update($this->getTable()->get('_tbl'))
					 ->set('quantity = quantity - '.$quantity)
					 ->where('product_id = '.$productId)
					 ->dbLoadQuery()
					 ->query();
			  
	}
	
	public function loadLanguageRecords($filter = array())
	{
		$query = new Rb_Query();
		$query->select('*')				
				->from($this->getLanguageTableName().' as  `tbl`');
				
		$this->_buildWhereClause($query, $filter);
		
		return $query->dbLoadQuery()->loadObjectList($this->getLanguageTable()->getKeyName());		
	}
	
	/**
	 * Update variation_of property of the given variants with the given $variationOf
	 * 
	 * @param array $variants : productIds in which variation_of is required to be updated 
	 * @param int $variationOf : product id that will be made as variation_of of all the variants
	 */
	public function updateVariationOf(Array $variants, $variationOf )
	{
		if(empty($variants)){
			return true;
		}
		
		$query = new Rb_Query();
		
		return $query->update($this->getTable()->get('_tbl'))
					 ->set('`variation_of` = '.$variationOf)
					 ->where('`product_id` IN ('.implode(',', $variants).')' )
					 ->dbLoadQuery()
					 ->query();
		
	}
}

class PaycartModelformProduct extends PaycartModelform {}

class PaycartTableProduct extends PaycartTable {}

class PaycartTableProductLang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_product_lang', $tblPrimaryKey='product_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}

	/**
	 * 
	 * Method call to Get unique alias string
	 * 
	 * @param string $alias alias 	which ned to be properly generated 
	 * @param int	 $id			optional : id of category
	 * @param string $style
	 * 
	 * @return string The unique alias string.
	 */
	public static function getNewAlias($alias, $id = 0, $style = 'dash')
	{		
		$alias  = PaycartHelper::sluggify($alias);//Sluggify the input string
	
		//@PCTODO:: move to helper
		// if Value already have '-'(dash) with numeric-data then remove numeric-data 
		$string = $alias;
		if (preg_match('#-(\d+)$#', $string, $matches)) {
			$string = preg_replace('#-(\d+)$#', sprintf('-', ''), $string);
		}
		
		$result = self::getRecordsOfAlias($alias, $id);		

		// build new column value
		while (in_array($alias, $result)) {
			$alias = JString::increment($alias, $style);
		}
		
		return $alias;		
	}
	
	public static function getRecordsOfAlias($alias, $id = 0)
	{
		$query 	= new Rb_Query();
		$query->select('`alias`')
			  ->where("`alias` LIKE '".$alias."%'");
					  
		if(!empty($id)){
			$query->where("`product_id` <> ".intval($id));
		}
					  
		return $query->from('`#__paycart_product_lang`')
					 ->dbLoadQuery()->loadcolumn();
	}
	
	public function check()
	{
		$this->alias = trim($this->alias);

		if (empty($this->alias)){
			$this->alias = self::getNewAlias($this->title, $this->product_id);
		}
		
		return true;
	}	
}