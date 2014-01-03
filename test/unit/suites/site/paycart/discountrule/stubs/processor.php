<?php

/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/

/**
 * 
 * Stub for PaycartDiscountRuleProcessorTest 
 * @author mManishTrivedi
 *
 */
class PaycartDiscountRuleProcessorStub extends PaycartDiscountRuleProcessor
{
	/**
	 * 
	 * Stub method for testIsApplicable
	 * @param unknown_type $request
	 * @param unknown_type $response
	 */
	public function stub_testIsApplicable($request, $response)
	{
		return parent::isApplicable($request, $response);
	}
}
