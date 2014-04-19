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
	 * @return PaycartTaxruleProcessor
	 */
	public function getProcessor()
	{
		$processor = PaycartFactory::getProcessor(paycart::PROCESSOR_TYPE_TAXRULE, $this->processor_classname);
		$processor->processor_config = $this->getProcessorConfig();
		$processor->rule_config 	 = $this->getRuleconfigRequestObject();
		$processor->global_config 	 = $this->getGlobalconfigRequestObject();
		return $processor;
	}
	
	/**
	 * 
	 * Do start processing tax request
	 * @param Paycartcart $cart
	 * @param PaycartCartparticular $cartparticular
	 * @throws InvalidArgumentException
	 * 
	 * @return Taxrule lib object
	 */
	public function process(Paycartcart $cart, PaycartCartparticular $cartparticular)
	{
		$request   = $this->getRequestObject($cart, $cartparticular);
		$response  = $this->createResponse();
		
		$processor = $this->getProcessor();
		
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
		

		$cartparticular->addTotal($response->amount);
		
		//@PCTODO :: auto reinitailize cart price when add tax
		
		//create usage data
		$usage = new stdClass();
		
		$usage->rule_type			=	Paycart::PROCESSOR_TYPE_TAXRULE;
		$usage->rule_id				=	$this->getId();
		$usage->cart_id				=	$cart->getId();
		$usage->buyer_id			=	$cart->getBuyer();
		$usage->carparticular_id	=	$cartparticular->getId();
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
	 * @param PaycartCartparticular $cartparticular
	 * 
	 * @return PaycartTaxruleRequest object
	 */
	public function getRequestObject(PaycartCart $cart, PaycartCartparticular $cartparticular)
	{	
		/* @var $helperRequest PaycartHelperRequest */	
		$helperRequest 			= PaycartFactory::getHelper('request');		
		
		$request 	= new PaycartTaxruleRequest();
		
		//rule specific data
		$request->taxable_amount		= $this->amount;
		$request->cartparticular 		= $helperRequest->getCartparticularObject($cartparticular);
		$request->shipping_address		= $helperRequest->getBuyeraddressObject($cart->getShippingAddress());
		$request->billing_address		= $helperRequest->getBuyeraddressObject($cart->getBillingAddress());
		$request->buyer					= $helperRequest->getBuyerObject($cart->getBuyer());
		
		return $request;
	}
	
	public function getRuleconfigRequestObject()
	{
		$object = new PaycartTaxruleRequestRuleconfig();
		$object->tax_rate = $this->amount;
		return $object;
	}
	
	public function getGlobalconfigRequestObject()
	{
		$object = new PaycartTaxruleRequestGlobalconfig();
		return $object;
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
	function getProcessorConfig()
	{
		return $this->processor_config->toObject();
	}
	

	/**
	 * @PCTODO :: Taxrule-Helper.php 
	 * @param PayacartCart $cart
	 * @param PaycartCartparticular $cartparticular
	 * @param array $ruleIds Applicable rules
	 * 
	 * @return bool value
	 */
	protected function _processTaxrule(PayacartCart $cart, PaycartCartparticular $cartparticular, Array $ruleIds)
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
			$taxrule->process($cart, $cartparticular);
		}
		
		return true;
	}	
	
}
