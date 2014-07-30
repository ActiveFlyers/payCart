<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Base class of discount-rule's processor
 * @author mManishTrivedi
 *
 */
abstract class PaycartDiscountRuleProcessor
{
	/**
	 * @var PaycartDiscountruleRequestGlobalconfig
	 */
	public $global_config = null;
	
	/**
	 * @var PaycartDiscountruleRequestRuleconfig
	 */
	public $rule_config = null;
	
	/**
	 * @var stdclass
	 */
	public $processor_config = null;	
		
	/**
	 * Method to invoke on Processor edit view. Set configHtml property on response object
	 * 
	 * Render extra stuff which is used in processor config
	 *  
	 */
	public function getConfigHtml(PaycartDiscountRuleRequest $request, PaycartDiscountRuleResponse $response)
	{
		$response->configHtml =  "<div></div>";
		return $response;
	}
	
	
	/**
	 * Method to invoke on checkout flow. 
	 * 
	 */
	public function getProcessorHtml(PaycartDiscountRuleRequest $request, PaycartDiscountRuleResponse $response)
	{
		return $response;
	}
	
	/**
	 * 
	 * Process, is a method that recive/get request object and execute set of instruction on behalf request variables, 
	 * then create response object for return purpose.
	 * Process discount rule 
	 * @param $requestedData, PaycartDiscountRuleRequest
	 * 
	 * @return PaycartDiscountRuleResponse
	 */
	public function process(PaycartDiscountRuleRequest $request, PaycartDiscountRuleResponse $response) 
	{
		try {
			// Calculate discount on discountable_amount
			// amount must be negative as it is a discount
			$response->amount =  -$this->calculate($request->discountable_amount, $this->rule_config->amount, $this->rule_config->is_percentage);			
						
		} catch (Exception $e) {
			$response->exception = $e;
		}
		
		return $response;
	}

	/**
	 * 
	 * Calculate is a state-less method to invoke for calculate discount ammount 
	 * @param $price, Discount-amount calculate on it
	 * @param $discountAmount, amount of discount 
	 * @param $isPercentage, $discountAmount is percentage or not
	 * @throws UnexpectedValueException 
	 * 
	 * @return calculated new discounted amount.
	 */
	protected function calculate($price, $discountAmount, $isPercentage = true) 
	{
		// validation
		if (!$price) {
			throw new InvalidArgumentException(JText::_('COM_PAYCART_DISCOUNTRULE_NOT_ON_ZERO')); 
		}

		// validation
		if (!$discountAmount) {
			throw new InvalidArgumentException(JText::_('COM_PAYCART_DISCOUNTRULE_IS_NOT_ZERO')); 
		}
		
		if($isPercentage) {
			$discountAmount = ($price*$discountAmount/100);
		}
		
		return $discountAmount;
	}	
}