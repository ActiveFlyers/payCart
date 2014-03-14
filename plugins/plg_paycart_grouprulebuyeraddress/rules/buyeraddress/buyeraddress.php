<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartShippngrule.Processor
* @subpackage       FlatRate
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Buyer Address Group Rule
 *
 * address_type
 * 		Shipping
 * 		Billing
 * 
 * @author Gaurav Jain
 */
class PaycartGroupruleBuyeraddress extends PaycartGrouprule
{	
	public function isApplicable($entity_id)
	{
		// Entity id will be buyer_id
		$buyer_id = $entity_id;
		
		$address_type = $this->params->get('address_type', '');
		
		if(empty($address_type)){
			return false;
		}
		
		if(!empty($buyer_id)){
			$buyer = PaycartBuyer::getInstance($buyer_id);
			if($address_type === 'shipping'){
				$buyer_address = $buyer->getShippingAddress();
			}
			elseif($address_type === 'billing'){
				$buyer_address = $buyer->getBillingAddress();
			}
			else{
				return false;//TODO
			}
		}
		
		if(empty($buyer_id) || empty($buyer_address)){
			$buyer_address = new stdClass();
			$buyer->country = false;
			$buyer->state 	= false;
			$buyer->city 	= false;
			$buyer->zipcode = false;
		}
		
		// there will be multiple addresses in the parameters
		$addresses = $this->params->get('address', array());
		foreach($addresses as $address){
			$result = $this->_isApplicable($address, $buyer_address);
			if($result === true){
				return true;
			}
		}
		
		return false;
	}
	
	protected function _isApplicable($address, $buyer_address)
	{
		$address = (object) $address;
		/*
		 * For COUNTRY
		 */
		// retrun false
		// if countries_assignment is selected, and buyer country is not in selected countries 
		if('selected' == $address->countries_assignment && !in_array($buyer_address->country, $address->countries) ){
			return false;
		}
		
		// retrun false
		// if countries_assignment is except, and buyer country is in selected countries 
		if('except' == $address->countries_assignment && in_array($buyer_address->country, $address->countries) ){
			return false;
		}
		
		/*
		 * For STATE
		 */
		// retrun false
		// if states_assignment is selected, and buyer state is not in selected states 
		if('selected' == $address->states_assignment && !in_array($buyer_address->state, $address->states) ){
			return false;
		}
		
		// retrun false
		// if states_assignment is except, and buyer state is in selected states 
		if('except' == $address->states_assignment && in_array($buyer_address->state, $address->states) ){
			return false;
		}
		
		/*
		 * For CITY
		 */
		// retrun false
		// if cities_assignment is selected, and buyer city is not in selected cities 
		if('selected' == $address->cities_assignment && !in_array($buyer_address->city, $address->cities) ){
			return false;
		}
		
		// retrun false
		// if cities_assignment is except, and buyer city is in selected cities 
		if('except' == $address->cities_assignment && in_array($buyer_address->city, $address->cities) ){
			return false;
		}
		
		/*
		 * For ZIPCODE
		 */
		if(trim($address->min_zipcode) != ''){
			if($buyer_address->zipcode < $address->min_zipcode){
				return false;
			}
		}
		
		if(trim($address->max_zipcode) != ''){
			if($buyer_address->zipcode > $address->max_zipcode){
				return false;
			}
		}
		
		return true;
	}
}