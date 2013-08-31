<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		Puneet Singhal 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * CartParticulars Model
 */
class PaycartModelCartParticulars extends PaycartModel
{
	var $filterMatchOpeartor = Array(
									'buyer_id' 	=> array('='),
									'product_id'	=> array('=')
									);
									
	public function save($data, $pk=null, $new=false)
	{
		// no data then return true without any action
		if(empty($data)) {
			return true;
		}
		
		// Insert new data
		$query 		= $this->_db->getQuery(true);
		$tableName 	= $this->getTableName();
		$columns 	= $this->table()->getFields();
		$default	= $this->getDefault();
		
		// build inert query
		$query->insert($this->getTable()->get('_tbl'))
					->columns($columns);

		foreach ($data as $row) {
			$values = array();
			
			foreach ($columns as $column){
				$value	  = isset($row[$column]) ? $row[$column] : $default[$column];
				$values[] = $this->_db->quote($value);
			}
			
			$values = implode(',', $values);
			$query->values("$values");
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
	 * set the dafault values of table columns ...
	 */
	protected function getDefault()
	{
		$data['cart_particulars_id']=	0;
		$data['cart_id']	 		=	0; 
		$data['buyer_id'] 		 	=	0;
		$data['product_id'] 		=	0;
		$data['title']		 		=	null;
		$data['quantity']	 		=	0;
		$data['unit_cost'] 		 	=	0.00;
		$data['tax']	 			=	0.00; 	
		$data['discount'] 			=	0.00;
		$data['price']				=	0.00;
		$data['shipment_date']  	=	'0000-00-00 00:00:00';	
		$data['reversal_date'] 		=	'0000-00-00 00:00:00';
		$data['delivery_date'] 		=	'0000-00-00 00:00:00';
		$data['params']				=  	'{}';
 
		return $data;
	}
	
}

class PaycartModelformCartParticulars extends PaycartModelform { }
