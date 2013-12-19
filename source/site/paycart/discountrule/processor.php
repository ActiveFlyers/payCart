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
abstract class PaycartDiscountRuleProcessor extends JObject
{
	// processor config
	protected $_config ;
	
	/**
	 * 
	 * Applicability check  
	 * @param PaycartDiscountRuleRequest $discountRule
	 * 
	 * @return boolean type if applicable otherwise false
	 */
	protected function isApplicable(PaycartDiscountRuleRequest $request, PaycartDiscountRuleResponse $response)
	{
		return true;
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
	public function process(PaycartDiscountRuleRequest $requestedData) 
	{
		$response = new PaycartDiscountRuleResponse();
		
		try {
				
			// Step-1: check applicibility
			if (!$this->isApplicable($requestedData, $response)) {
				return $response;
			}
			
			// Step-2: Get Price for discount
			 
			// Price on which discount will applied
			$price = $requestedData->price;
			
			// If discount is successive/row total then applied on total amount.
			// It will use on multi discount
			if ($requestedData->isSuccessive) {
				$price = $requestedData->total;
			}
			
			// Step-3: Calculate discount on Price
			$response->amount =  $this->calculate($price, $requestedData->amount, $requestedData->isPercentage);
						
		} catch (Exception $e) {
			$response->error = $e->getMessage();
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
			throw new UnexpectedValueException(Rb_Text::_('COM_PAYCART_DISCOUNTRULE_NOT_ON_ZERO')); 
		}

		// validation
		if (!$discountAmount) {
			throw new UnexpectedValueException(Rb_Text::_('COM_PAYCART_DISCOUNTRULE_IS_NOT_ZERO')); 
		}
		
		if($isPercentage) {
			$discountAmount = ($price*$discountAmount/100);
		}
		
		return $discountAmount;
	}
	
	
	/**
	 * 
	 * Check core applicable condition
	 * @param PaycartDiscountRuleRequest $requestedData
	 * 
	 * @return (boolean) true if core accessibility is ok otherwise false
	 */
	public function coreCheckup(PaycartDiscountRuleRequest $requestedData) 
	{
		$discountRule = $requestedData->discountRule;
		
		// discount rule should be published
	//	if (!$discountRule->published) {
	//		return false;
	//	}
		
		$entity = $requestedData->entity;
		// check precalculate discount exist
		if ($entity->discount) {
			//@PCTODO:: Check clubbale discount
		}
		
		$now 		= Rb_Date::getInstance();
		$startDate  = Rb_Date::getInstance($discountRule->start_date);
		
		// if discount rule is not published
	//	if($startDate->toUnix() > $now->toUnix()) {
	//		return false;
	//	}

	//	$endDate = Rb_Date::getInstance($discountRule->end_date);
		
		// exceeded to end date
	//	if($endDate->toUnix() < $now->toUnix()){
	//		return false;
	//	}
		
		//@PCTODO:: get usage data
		$usage = new stdClass();
		
		// stop further processing, if usage limit exceeded
		if (count($usage) >= $discountRule->usage_limit) {
			return false;
		}
		
		//@PCTODO : get unique usage of this discount-rule on behalf of buyer  
		$buyerUsage = 100;
		// stop further processing, if buyer_usage limit exceeded
		if (count($buyerUsage) >= $discountRule->usage_limit) {
			return false;
		}
		
		return true;
	}
	
}
