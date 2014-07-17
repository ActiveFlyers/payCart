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
	 * @var PaycartTaxruleRequestGlobalconfig
	 */
	public $global_config = null;
	
	/**
	 * @var PaycartTaxruleRequestRuleconfig
	 */
	public $rule_config = null;
	
	/**
	 * @var stdclass
	 */
	public $processor_config = null;
	
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
		$response->configHtml =  "<div></div>";
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
			$response->amount = $this->_calculateTax($request->taxable_amount, $this->rule_config->tax_rate);
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