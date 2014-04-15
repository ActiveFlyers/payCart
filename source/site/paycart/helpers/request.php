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
	 * @return PaycartRequestAddress
	 */
	public function getAddressObject($address)
	{
		//@PCTODO:
		// if its numeric then get instance of address
		if(is_numeric($particular)){
			$address = PaycartBuyeraddress::getInstance($address);
		}
		
		$object = new PaycartRequestAddress();
		
		$object->to			= $address->getTo();
		$object->address 	= $address->getAddress();
		$object->country	= $address->getCountry();
		$object->state		= $address->getState();
		$object->city		= $address->getCity();
		$object->zipcode	= $address->getZipcode();
		$object->phone1		= $address->getPhone1();
		$object->phone2		= $address->getPhone2();		
		$object->vat_number = $address->getVatNumber();
		
		return $object;
	}
	
	/**
	 * @return PaycartRequestBuyer
	 */
	public function getBuyerObject($buyer)
	{
		if(is_numeric($particular)){
			// get buyer instance		
			$buyer 	= PaycartBuyer::getInstance($buyer_id);
		}		
		$object = new PaycartRequestBuyer();
		
		$object->email 		= $buyer->getEmail();
		$object->username 	= $buyer->getUsername();
		$object->name		= $buyer->getRealname();
		
		return $object;
	}
	
	/**
	 * @return PaycartRequestParticular
	 */
	public function getParticularObject($particular)
	{
		// if its numeric then get instance of cart particular
		if(is_numeric($particular)){
			$particular = PaycartCartparticular::getInstance($particular);
		}
		
		$object = new PaycartRequestParticular();
		
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