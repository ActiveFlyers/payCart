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
	
	public function __construct($config = null)
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
			//set amount on response
			$response->taxAmount = $this->_calculateTax($request->taxableAmount, $request->taxRate);
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
		if(!$taxableAmount){
			throw new InvalidArgumentException(Rb_Text::_('COM_PAYCART_TAXRULE_CANT_BE_PROCESSED_ON_ZERO'));
		}
		
		if(!$taxRate){
			throw new InvalidArgumentException(Rb_Text::_('COM_PAYCART_TAXRULE_RATE_CANT_BE_ZERO'));
		}
		
		return ($taxableAmount * floatval($taxRate) / 100);
	}	
}


class PaycartTaxruleRequest
{
	// Request Field : Discount speicifc
	public $rule_amount	  			=	0;
	
	// Request Field : Particular Cart/Product/Shipping specific
	public $particular_unit_price	 		=	0;			// unitPrice * quantity
	public $particular_quantity		 		=	1;			// quantity
	public $particular_price		 		=	0;			// (unitPrice * quantity)
	public $particular_total		 		=	0;			// (unitPrice * quantity)+(Applied Tax)
	
	// Request Field : cart data
	public $cart_total					=	0;
	public $cart_shipping_address_id	=	0;
	public $cart_billing_address_id		=	0;
	
	// Request Field : buyer data
	public $buyer_id			=	0;
	public $buyer_vatnumber		=	0;
}


class PaycartTaxruleResponse
{
	// actual tax amount 
	public $amount   = 0;
	
	// message that will be displayed to users 
	public $message     = '';
	
	// type of message like error, warning or acknowledgement message
	public $messageType = Paycart::MESSAGE_TYPE_MESSAGE;
	
	// stores exception object
	public $exception   = null;
	
	//store configuration html of rule
	public $configHtml  = '';
}
