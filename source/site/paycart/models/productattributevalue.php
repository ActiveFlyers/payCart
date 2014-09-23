<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @authoe		mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Attribute Value Table
 * @author mManishTrivedi
 */
class PaycartModelProductAttributeValue extends PaycartModel
{
	/**
	 * 
	 * Return specific product records
	 * @param numeric $productId
	 */
	public function loadProductRecords($productId)
	{
		// @PCTODO:: Should be cached 
		$query = new Rb_Query();
		$query->select('*')
			  ->from($this->getTable()->get('_tbl'))
			  ->where($this->_db->quoteName('product_id') .' = '.$productId);
			  			  
		try	{
			$records =	$this->_db->setQuery($query)->loadAssocList();
		}
		catch (RuntimeException $e) {
			//@PCTODO::proper message propagates
			Rb_Error::raiseError(500, $e->getMessage());
		}
		
		$result = array();
		//process records and create 
		foreach ($records as $record){
			$result[$record['productattribute_id']] = $record['productattribute_value'];
		}
		
		return $result;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Model::save()
	 * 
	 * create new records
	 */
	public function save($data, $pk=null, $new=false)
	{
		// 1#.no data then return true without any action
		if(empty($data)) {
			return true;
		}
		
		// 2#. Insert new data
		$query = $this->_db->getQuery(true);
		
		// build inert query
		$query->insert($this->getTable()->get('_tbl'))
					->columns(
						array(
							$this->_db->quoteName('product_id'), $this->_db->quoteName('productattribute_id'),
							$this->_db->quoteName('productattribute_value')
						)
					);
	
		foreach ($data as $row) {
			$query->values("
							{$this->_db->quote($row['product_id'])}, {$this->_db->quote($row['productattribute_id'])},
							{$this->_db->quote($row['productattribute_value'])}
							");
		}
		
		$this->_db->setQuery($query);
		try	{
			$this->_db->execute();
		}
		catch (RuntimeException $e) {
			//@PCTODO::proper message propagates
			Rb_Error::raiseError(500, $e->getMessage());
		}
		
		return true;			
	}
	
	/**
	 * 
	 * return attribute records for which product and its variants have different values
	 * @param Array $productIds : product ids for which to select attribute records
	 * 
	 * @return array containing following data :
	 * 					productattribute_id => attribute id,	 
	 * 					totalValues 		=> total number of different value for the attribute
	 * 					totalProducts		=> total number of product that is having any value of attribute
	 * 					values				=> comma separated values of attribute for mentioned products
	 */
	public function loadSelectorAttributes(Array $productIds = array())
	{
		if(empty($productIds)){
			return array();
		}
		
		$query   =  new Rb_Query();
		$records = $query->select('`productattribute_id`, COUNT(DISTINCT `productattribute_value`) as "totalValues", 
		            			    COUNT(DISTINCT `product_id`) as "totalProducts"')
			  			 ->from($this->getTable()->getTableName())
			  			 ->group('`productattribute_id`')
			  			 ->where('`product_id` IN ('.implode(",", $productIds).')')
			  			 ->having('totalValues > 1')
			  			 ->dbLoadQuery()
			  			 ->loadAssocList('productattribute_id');
			  			 
		return $records;
	}

	/**
	 * 
	 * Loads product and it's value for current attribute
	 * @param int $attributeId : attribute id for which to load value of mentioned productids
	 * @param Array $productIds : product ids for which to load value
	 * @param string $extraCondition (optional) : subquery that can be added in where condition
	 */
	public function loadProductAttributeValue($attributeId, Array $productIds = array(), $extraCondition = '')
	{
		if(empty($productIds)){
			return array();
		}
		
		$query = new Rb_Query();
		
		if(!empty($extraCondition)){
				$query->where($extraCondition);
		}
		
		return $query->select('product_id, productattribute_value')
					 ->from($this->getTable()->getTableName())
					 ->where('`productattribute_id` = '.$attributeId)
					 ->where('`product_id` IN('.implode(",", $productIds).')')
					 ->group('productattribute_value')
					 ->dbLoadQuery()
					 ->loadAssocList('productattribute_value');
	}
	
	/**
	 * invoke to get product id from the given products that is matching 
	 * each attribute value (given in @param $attributes)
	 *  
	 * @param Array $products 
	 * @param Array $attributes
	 * @return product id  
	 */
	public function loadProduct($products, $attributes)
	{
		$productIds = implode(',', $products);
		
		$prefix		= 'Select `product_id` from (';
		$query      = 'Select `product_id` from '.$this->getTable()->getTableName().
							' where product_id IN('.$productIds.') AND productattribute_id = '.key($attributes).' AND productattribute_value = '.$attributes[key($attributes)];
		$count 		= count($attributes);
				  
		unset($attributes[key($attributes)]);
		
		if(!empty($attributes)){
			$query	= $prefix.$query;
			 
			foreach ($attributes as $key => $value){				    
				$subQuery = ' Union all'.
							' Select `product_id` from '.$this->getTable()->getTableName().
							' where product_id IN('.$productIds.') AND productattribute_id = '.$key.' AND productattribute_value = '.$value;
				$query .= $subQuery;
			}
			$query .= ' ) tbl GROUP BY `product_id` HAVING count(*) = '.$count;
		}
		
		return PaycartFactory::getDbo()->setQuery($query)->loadResult();
	}
	
}


/** 
 * ProductAttributeValue Table
 * @author rimjhim
 */
class PaycartTableProductAttributeValue extends PaycartTable
{
	function __construct($tblFullName='#__paycart_productattribute_value', $tblPrimaryKey='', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}