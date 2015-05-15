<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Request Helper
 * @since 1.0.0
 * @author Gaurav Jain
 */
class PaycartHelperRequest extends PaycartHelper
{	
	/**
	 * @return PaycartRequestBuyeraddress
	 */
	public function getBuyeraddressObject(PaycartBuyeraddress $buyer_address)
	{		
		$object = new PaycartRequestBuyeraddress();
		
		$country = PaycartFactory::getModel('country')->loadRecords(array('country_id'=>$buyer_address->getCountryId()));
		$state   = PaycartFactory::getModel('state')->loadRecords(array('state_id'=>$buyer_address->getStateId()));
		
		$object->to			= $buyer_address->getTo();
		$object->address 	= $buyer_address->getAddress();
		//add full details of country and state
		$object->country	= (!empty($country))?array_shift($country):0;
		$object->state		= (!empty($state))?array_shift($state):0;
		$object->city		= $buyer_address->getCity();
		$object->zipcode	= $buyer_address->getZipcode();
		$object->phone		= $buyer_address->getPhone();		
		$object->vat_number = $buyer_address->getVatNumber();
		
		return $object;
	}
	
	/**
	 * @return PaycartRequestBuyer
	 */
	public function getBuyerObject(PaycartBuyer $buyer)
	{
		$object = new PaycartRequestBuyer();
		
		$object->email 		= $buyer->getEmail();
		$object->username 	= $buyer->getUsername();
		$object->name		= $buyer->getRealname();
		
		return $object;
	}
	
	/**
	 * @return PaycartRequestCatparticular
	 */
	public function getCartparticularObject(PaycartCartparticular $particular)
	{
		$object = new PaycartRequestCartparticular();
		
		// core
		$object->title			= $particular->getTitle();
		$object->type			= $particular->getType();
		
		// pricing
		$object->unit_price		= $particular->getUnitPrice();
		$object->quantity		= $particular->getQuantity();
		$object->price			= $particular->getPrice();
		$object->total			= $particular->getTotal();
		$object->discount		= $particular->getDiscount();
		$object->tax			= $particular->getTax();
		
		// dimensions
		$object->height			= $particular->getHeight();
		$object->weight			= $particular->getWeight();
		$object->width			= $particular->getWidth();
		$object->length			= $particular->getLength();
		
		return $object;
	}
}