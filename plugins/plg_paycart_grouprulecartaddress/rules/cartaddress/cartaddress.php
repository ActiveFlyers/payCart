<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          Cart Address Group Rule
* @subpackage       FlatRate
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Cart Address Group Rule
 *
 * address_type
 * 		Shipping
 * 		Billing
 * 
 * @author Gaurav Jain
 */
class PaycartGroupruleCartaddress extends PaycartGrouprule
{	
	public function isApplicable($entity_id)
	{
		// Entity id will be buyer_id
		$cart_id = $entity_id;
		
		$address_type = $this->config->get('address_type', '');
		
		if(empty($address_type)){
			return false;
		}
		
		if(!empty($cart_id)){
			$cart = PaycartCart::getInstance($cart_id);
			if($address_type === 'shipping'){
				$cart_address = (object) $cart->getShippingAddress(true)->toArray();
			}
			elseif($address_type === 'billing'){
				$cart_address = (object) $cart->getBillingAddress(true)->toArray();
			}
			else{
				return false;//TODO
			}
		}
		
		if(empty($cart_id) || empty($cart_address)){
			$cart_address = new stdClass();
			$cart_address->country_id 	= false;
			$cart_address->state_id 	= false;
			$cart_address->city 		= false;
			$cart_address->zipcode 		= false;
		}
		
		$address = $this->config->toObject();
		return $this->_isApplicable($address, $cart_address);		
	}
	
	protected function _isApplicable($address, $cart_address)
	{
		$address = (object) $address;
		/*
		 * For COUNTRY
		 */
		// retrun false
		// if countries_assignment is selected, and buyer country is not in selected countries 
		if('selected' == $address->countries_assignment && !in_array($cart_address->country_id, $address->countries) ){
			return false;
		}
		
		// retrun false
		// if countries_assignment is except, and buyer country is in selected countries 
//		if('except' == $address->countries_assignment && in_array($cart_address->country_id, $address->countries) ){
//			return false;
//		}
		
		/*
		 * For STATE
		 */
		// retrun false
		// if states_assignment is selected, and buyer state is not in selected states 
		if('selected' == $address->states_assignment && !in_array($cart_address->state_id, $address->states) ){
			return false;
		}
		
		// retrun false
		// if states_assignment is except, and buyer state is in selected states 
//		if('except' == $address->states_assignment && in_array($cart_address->state_id, $address->states) ){
//			return false;
//		}
		
//		/*
//		 * For CITY
//		 */
//		// retrun false
//		// if cities_assignment is selected, and buyer city is not in selected cities 
//		if('selected' == $address->cities_assignment && !in_array($buyer_address->city, $address->cities) ){
//			return false;
//		}
//		
//		// retrun false
//		// if cities_assignment is except, and buyer city is in selected cities 
//		if('except' == $address->cities_assignment && in_array($buyer_address->city, $address->cities) ){
//			return false;
//		}
		
		/*
		 * For ZIPCODE
		 */
		if(trim($address->min_zipcode) != ''){
			if($cart_address->zipcode < $address->min_zipcode){
				return false;
			}
		}
		
		if(trim($address->max_zipcode) != ''){
			if($cart_address->zipcode > $address->max_zipcode){
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Gets the html and js script call of parameteres 
	 * @return array() Array of Html and JavaScript functions to be called
	 */
	public function getConfigHtml($namePrefix = '')
	{
		$idPrefix = str_replace(array('[', ']'), '', $namePrefix);
		// @TODO : Use paycart helper
		$config 	= $this->config->toArray();
		
		ob_start();
		include dirname(__FILE__).'/tmpl/config.php';
		$contents = ob_get_contents();
		ob_end_clean();
		
		$scripts 	= array();
		static $scriptAdded = false;
		if(!$scriptAdded){			
			$scripts[] 	= 'paycart.jQuery("select.pc-chosen").chosen({disable_search_threshold : 10, allow_single_deselect : true });';
			$scriptAdded = true;
		}
		
		return array($contents, $scripts);
	}
}
