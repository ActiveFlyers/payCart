<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Taxrule lib
 * @author rimjhim
 *
 *
 *	Assumptions: 
 *	1#. Always applied on cart-particulars.
 * 	2#. Multiple-Taxrule on one Particular :
 * 		## All Taxrule process either "Before-Discuntrules" Or "After-discountrules"
 * 		## Taxrule's amount always calculate on actual-price of particular.
 * 
 */
class PaycartTaxrule extends PaycartLib
{
	protected $taxrule_id     		= 0;
	protected $title	      		= '';
	protected $published      		= 1;
	protected $description    		= '';
	protected $amount	      		= 0;
	protected $apply_on				= '';
	protected $processor_classname	= '';
	protected $processor_config		= '';
	protected $created_date			= null;
	protected $modified_date		= null;
	protected $ordering				= 0;
	
	protected $message				= '';
	
	
	function reset()
	{
		$this->taxrule_id 			= 0;
		$this->title				= '';
		$this->published			= 1;
		$this->description			= '';
		$this->amount				= 0;
		$this->apply_on				= '';
		$this->processor_classname	= '';
		$this->processor_config		= '';
		$this->created_date			= new Rb_date();
		$this->modified_date		= new Rb_date();
		$this->ordering				= 0;
		
		$this->message				= '';
	}
	
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('taxrule', $id, $data);
	}
	
	/**
	 * 
	 * Do start processing tax request
	 * @param Paycartcart $cart
	 * @param PaycartCartparticular $particular
	 * @throws InvalidArgumentException
	 * 
	 * @return Taxrule lib object
	 */
	public function process(Paycartcart $cart, PaycartCartparticular $particular)
	{
		$request   = $this->createRequest($cart, $particular);
		$response  = $this->createResponse();
		
		$processor = PaycartFactory::getProcessor(paycart::PROCESSOR_TYPE_TAX, $this->processor_classname, $this->getProcessorConfig());
		
		//process current request
		$processor->process($request, $response);
		
		
		// check if exception is occured
		if ($response->exception && $response->exception instanceof Exception) {
			//@PCTODO : Exception handling. better if we will introduce our tax type exception
			//$this->_errors = $response->exception->getMessage();
			return $this;
		}
		
		// notify to admin
		if ( Paycart::MESSAGE_TYPE_ERROR == $response->messageType) {
			//@PCTODO : Error propagate to admin and log it 
			//$this->_errors = $response->exception->getMessage();
			return $this;
		}
		
		// show system message to end user 
		if ( Paycart::MESSAGE_TYPE_NOTICE == $response->messageType || Paycart::MESSAGE_TYPE_WARNING == $response->messageType || Paycart::MESSAGE_TYPE_MESSAGE == $response->messageType) {
			//@PCTODO:: Show msg to end user with msgtype
		}
		

		$particular->addTotal($response->amount);
		
		//@PCTODO :: auto reinitailize cart price when add tax
		
		//create usage data
		$usage = new stdClass();
		
		$usage->rule_type			=	Paycart::PROCESSOR_TYPE_DISCOUNTRULE;
		$usage->rule_id				=	$this->getId();
		$usage->cart_id				=	$cart->getId();
		$usage->buyer_id			=	$cart->getBuyer();
		$usage->carparticular_id	=	$particular->getId();
		$usage->price				=	$response->amount;
		$usage->applied_date		=	Rb_Date::getInstance();
		$usage->realized_date		=	'';
		$usage->message				=	'';
		$usage->title				=	'';
		
		//invoke method to track usage
		PaycartFactory::getModel('usage')->save((array)$usage);
		
		return $response;
	}
	
	/**
	 * 
	 * Create Request object to be processed 
	 * @param PaycartCart $cart
	 * @param PaycartCartparticular $particular
	 * 
	 * @return PaycartTaxruleRequest object
	 */
	protected function createRequest(PaycartCart $cart, PaycartCartparticular $particular)
	{
		$request 	= new PaycartTaxruleRequest();
		
		//rule specific data
		$request->rule_amount			= $this->amount;
		
		//particular specific stuff
		$request->particular_unit_price		= $particular->getUnitPrice();
		$request->particular_quantity		= $particular->getQuantity();
		$request->particular_price			= $particular->getPrice();		//basePrice = unitPrice * Quantity
		$request->particular_total			= $particular->getTotal();
		
		//cart specific stuff
		$request->cart_total				=	$cart->getTotal();
		$request->cart_shipping_address_id	=	$cart->getShippingAddress();
		$request->cart_billing_address_id	=	$cart->getBillingAddress();
		
		//@PCTODO:: Buyer specific stuff
		$request->buyer_id			=	$cart->getBuyer();
		$request->buyer_vatnumber	=	'';
		
		return $request;
	}
	
	/**
	 * 
	 * Create a response object
	 * 
	 * @return PaycartTaxruleResponse
	 */
	protected function createResponse()
	{
		return new PaycartTaxruleResponse();
	}
	
	/**
	 * Get processor config
	 */
	function getProcessorConfig($inArray = false)
	{		
		if($inArray){
			return $this->processor_config->toArray();			
		}
		
		return $this->processor_config->toObject();
	}
	

	/**
	 * @PCTODO :: Taxrule-Helper.php 
	 * @param PayacartCart $cart
	 * @param PaycartCartparticular $particular
	 * @param array $ruleIds Applicable rules
	 * 
	 * @return bool value
	 */
	protected function _processTaxrule(PayacartCart $cart, PaycartCartparticular $particular, Array $ruleIds)
	{
		//@PCTODO : define constant for applicable_on
		// {product_price, shipping_price, cart-price}
		$appliedOn = 'product_price';
		
		//@PCTODO :: move into model 
		// get applicable rules
		$condition = ' 	`taxrule_id` IN ('.array_values($ruleIds).') AND '.
					 '	`published` = 1 AND '. 
					 '	`applied_on` LIKE '."'$appliedOn'" ;
		
		// sort applicable rule as per sequence.
		$records = PaycartFactory::getModel('taxrule')
							->getData($condition, '`sequence`');

		// no rule for processing
		if (!$records) {
			return true;
		}
		
		foreach ($records as $id=>$record) {
			
			$taxrule = PaycartTaxrule::getInstance($id, $record);
			
			// process taxrule
			$taxrule->process($cart, $particular);
		}
		
		return true;
	}	
	
}
