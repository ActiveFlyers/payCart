<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+readybytes@readybytes.in
* @author		rimjhim
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Cart Json View
* @author rimjhim
 */
require_once dirname(__FILE__).'/view.php';

class PaycartAdminJsonViewCart extends PaycartAdminBaseViewCart
{	
	/**
	 * Create new shipment from the current cart
	 */
	public function saveShipment()
	{
		$response = new stdClass();
		$response->valid   = true;
		$response->message = JText::_("COM_PAYCART_ADMIN_SHIPMENT_SUCCESSFULLY_SAVED");
		
		$data   = $this->input->get('shipmentDetails',array(),'ARRAY');
		
		//if no shipping rule is there then do nothing
		if(!isset($data['shippingrule_id'])){
			$response->valid   = false;
			$response->message = JText::_("COM_PAYCART_ADMIN_SHIPMENT_ERROR_WHILE_SAVING");
			$this->assign('json', $response);
			return true;
		}
		
		if(!isset($data['est_delivery_date'])){
			$shippingRule = PaycartShippingrule::getInstance($data['shippingrule_id']);
			$date          = new Rb_Date();
			$date->add(new DateInterval('P'.$shippingRule->getDeliveryMaxDays().'D'));
			$data['est_delivery_date'] = $date->toSql(); 
		}
		
		
		$temp  = array();
		$notes = $data['notes'];
		$date  = Rb_Date::getInstance();
		foreach ($notes as $note){
			if(empty($note['date'])){
				$temp[] = array('status'=>$note['status'],
								'text'=>$note['text'],
								'date'=>$date->toSql(),
								'time'=>$date->format('H:i'));
				continue;
			}
			$temp[] = array('status'=>$note['status'],
							'text'=>$note['text'],
							'date'=>$note['date'],
							'time'=>$note['time']);
		}
		
		$data['notes'] = $temp;
		
		//save shipment
		$result = PaycartShipment::getInstance(0,$data)->save();
		
		if(!$result){
			$response->valid  = false;
			$response->message = JText::_("COM_PAYCART_ADMIN_SHIPMENT_ERROR_WHILE_SAVING");
		}else{
			$data['shipment_id'] = $result->getId();
			$response->data = $data;
		}	

		$this->assign('json', $response);
		return true;
	}	
	
	/**
	 * remove shipment from the current cart
	 */
	public function removeShipment()
	{
		$response = new stdClass();
		$response->valid = true;
		$response->message = JText::_('COM_PAYCART_ADMIN_SHIPMENT_SUCCESSFULLY_DELETED');
		
		$id   = $this->input->get('shipment_id',0,'INT');
		$result = PaycartShipment::getInstance($id)->delete();
		
		if(!$result){
			$response->valid  = false;
			$response->message = JText::_('COM_PAYCART_ADMIN_SHIPMENT_ERROR_IN_DELETION');
		}

		$this->assign('json', $response);
		return true;
	}
}