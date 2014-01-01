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
 * Taxrule processor base class
 * @author rimjhim
 *
 */
abstract class PaycartTaxruleProcessor
{
	/**
	 * @var config
	 */
	protected $config = null;
	
	public function __construct($config)
	{
		// set configuration
		$this->config = $config;
		return $this;
	}
	
	/**
	 * 
	 * Check applicablity
	 * @param PaycartTaxruleRequest $request
	 * @param PaycartTaxruleResponse $response
	 */	
	public function isApplicable(PaycartTaxruleRequest $request, PaycartTaxruleResponse $respose)
	{
		return true;
	}
	
	/**
	 * Set processor config into response
	 * @param PaycartTaxruleRequest $request
	 * @param PaycartTaxruleResponse $response
	 */
	public function getConfigHtml(PaycartTaxruleRequest $request, PaycartTaxruleResponse $response)
	{
		return $response;
	}
	
	/**
	 * Process the tax request
	 * @param PaycartTaxruleRequest $request
	 * @param PaycartTaxruleResponse $respose
	 * @return PaycartTaxruleResponse An object representing the tax response
	 */
	public function process(PaycartTaxruleRequest $request, PaycartTaxruleResponse $response)
	{
		if(!$this->isApplicable($request, $response)){
			return $response;
		}
		
		try{
			$taxableAmount = $request->taxableAmount;
			$taxAmount = $this->_calculateTax($taxableAmount, $request->taxRate);
			//set amount on response
			$response->taxAmount = $taxAmount;
		}
		catch (Exception $e){
			$response->exception = $e;
		}
		
		return $response;
	}
	
	/**
	 * return actual tax amount 
	 * @param taxableAmount : amount on which to apply tax
	 * @param taxRate : tax rate(%) to be applied
	 */
	protected function _calculateTax($taxableAmount, $taxRate)
	{
		return ($taxableAmount * floatval($taxRate) / 100);
	}	
}


class PaycartTaxruleRequest
{
	//taxrate to be applied 
	protected $taxRate			= 0; 
	
	//The amount on which to calculate and apply taxrate
	protected $taxableAmount	= 0;
	
	//country code of buyer
	protected $buyerCountryCode	= '';
	
	//vat number of buyer
	protected $buyerVatNumber   = '';
	
	//base price of product
	protected $productBasePrice = 0;
	
	//Quantity of current entity
	protected $productQuantity  = 1;
	
	//total tax applied on cart(on which duties can be applied)
	protected $cartTax   		= 0;
	
	//total shipping amount of cart
	protected $cartShipping		= 0;
}


class PaycartTaxruleResponse
{
	// actual tax amount 
	public $taxAmount   = 0;
	
	// message that will be displayed to users 
	public $message     = '';
	
	// type of message like error, warning or acknowledgement message
	public $messageType = Paycart::MESSAGE_TYPE_MESSAGE;
	
	// stores exception object
	public $exception   = null;
	
	//store configuration html of rule
	public $configHtml  = '';
}
