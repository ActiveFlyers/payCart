<?php

/**
* @copyright	Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		suppor+paycart@readybytes.in
* @author 		rimjhim 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * shipment Model
 * @author rimjhim
 *
 */
class PaycartModelShipment extends PaycartModel
{
	public function save($data, $pk=null, $new=false)
	{
		$products = array();
		
		//save data of the current table
		$id = parent::save($data);
		
		//save data in mapping table
		if($id && !empty($data['_products'])){
			$query = new Rb_Query();

			//delete the existing records for this shipment 
			$query->delete()
			   	  ->from('#__paycart_shipment_x_product')
				  ->where('`shipment_id` = '.$id);
			
			if(!$query->dbLoadQuery()->execute()){
				throw new Exception('Error in deleting Shipment Mapping');
			}

			$query = new Rb_Query();
			// build insert query
			$query->insert('#__paycart_shipment_x_product')
				  ->columns(
						array(
							$this->_db->quoteName('shipment_id'), $this->_db->quoteName('product_id'),
							$this->_db->quoteName('quantity')
						)
				  );
			
		
			foreach ($data['_products'] as $product) {
				$query->values("
								{$id}, {$this->_db->quote($product['product_id'])},
								{$this->_db->quote($product['quantity'])}
						      ");
			}
		}
		
		$this->_db->setQuery($query);
		try	{
			$this->_db->execute();
		}
		catch (RuntimeException $e) {
			//@PCTODO::proper message propagates
			Rb_Error::raiseError(500, $e->getMessage());
		}
	
		return $id;
	}
	
	public function loadRecords(Array $queryFilters=array(), Array $queryClean = array(), $emptyRecord=false, $indexedby = null)
	{
		$records = parent::loadRecords($queryFilters, $queryClean, $emptyRecord, 'shipment_id');
		
		if(empty($records)){
			return $records;
		}
		
		//load product details from mapping table
		$query = new Rb_Query();
		$shipmentIds = array_keys($records);
		$productDetails = $query->select('*')
							    ->from('#__paycart_shipment_x_product')
							    ->where("shipment_id IN(".implode(',', $shipmentIds).")")
							    ->dbLoadQuery()
							    ->loadObjectList();
		
		foreach ($productDetails as $key => $details){
			$key = $details->shipment_id;
			if(!isset($records[$key]->products)){
				$records[$key]->products = array();
			}
			$records[$key]->products[] = array('product_id' => $details->product_id,'quantity'=>$details->quantity); 
			
		}
		
		return $records;
	}
	
	public function delete($pk=null)
	{
		//try to calculate automatically
		if($pk === null){
			$pk = (int) $this->getId();
		}
		
		if(!$pk){
			$this->setError('Invalid itemid to delete for model : '.$this->getName());
			return false;
		}
		
		$query = new Rb_Query();
		$query->delete()
			  ->from('#__paycart_shipment_x_product')				
			  ->where('`shipment_id` = '.$pk);
				
		if(!$query->dbLoadQuery()->execute()){
			$this->setError('Error in deleting Shippingrule Group Mapping');
			return false;
		}
		
		return parent::delete($pk);
	}	
}

/**
 * 
 * shipment Table
 * @author rimjhim
 *
 */
class PaycartTableShipment extends PaycartTable
{
	function __construct($tblFullName='#__paycart_shipment', $tblPrimaryKey='shipment_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}