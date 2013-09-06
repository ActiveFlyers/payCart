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
class PaycartModelAttributeValue extends PaycartModel
{
	/**
	 * 
	 * Return specific product records
	 * @param numeric $productId
	 */
	public function loadProductRecords($productId)
	{
		// @PCTODO:: Should be cached 
		$query = $this->_db->getQuery(true);
		$query->select('*')->from($this->getTable()->get('_tbl'))
			  			  ->where($this->_db->quoteName('product_id') .' = '.$productId)
			  			  ->order($this->_db->quoteName('order'));		// Order is most important thing for position of attributes
		try	{
			$records =	$this->_db->setQuery($query)->loadAssocList('attribute_id');
		}
		catch (RuntimeException $e) {
			//@PCTODO::proper message propagates
			Rb_Error::raiseError(500, $e->getMessage());
		}
		
		return $records;
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
		// 2#. Valiadate Data
		if(!$this->validate($data)) {
			return false;
		}
		
		// 3#. Delete existing data
		//$this->deleteMany(Array('product_id'=>$productId));
		
		// 4#. Insert new data
		$query = $this->_db->getQuery(true);
		
		// build inert query
		$query->insert($this->getTable()->get('_tbl'))
					->columns(
						array(
							$this->_db->quoteName('product_id'), $this->_db->quoteName('attribute_id'),
							$this->_db->quoteName('value'), $this->_db->quoteName('order')
						)
					);
		//@PCTODO :: Order must be check before save data	
		//@PCTODO Discuss:: available data should be formated  		
		foreach ($data as $row) {
			$query->values("
							{$this->_db->quote($row['product_id'])},{$this->_db->quote($row['attribute_id'])},
							{$this->_db->quote($this->_format($row['value']))},{$this->_db->quote($row['order'])}
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
	 * Format attribute value before save it
	 * @param unknown_type $value
	 */
	protected function _format($value) 
	{
		if(is_array($value)) {
			$value = implode(',', $value);
		}
		return $value;
	}
	
}