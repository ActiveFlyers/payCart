<?php
/**
 * @package     Paycart
 * @subpackage  Paycart.plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      mManishTrivedi 
 */

defined('_JEXEC') or die;

/**
 * PayCart! Promotion Plugin
 *
 * @package     Paycart
 * @subpackage  Paycart.plugin
 * @author 		manish
 *
 */
class PaycartDiscountRuleProcessorCoupon extends PaycartDiscountRuleProcessor 
{
	/**
	 * (non-PHPdoc)
	 * @see components/com_paycart/paycart/discountrule/PaycartDiscountRuleProcessor::isApplicable()
	 */
	protected function isApplicable(PaycartDiscountRuleRequest $request, PaycartDiscountRuleResponse $response)
	{
		// rule have coupon code	
//		if($request->rule_coupon) {
//			return false;
//		}
		
		//entity must have coupon stuff 
		if ($request->entity_coupon){
			return false;
		}
		
		// Check Coupon is valid or not (CASE INSENSITIVE) 
		if(JString::strtolower($request->rule_coupon) != JString::strtolower($request->entity_coupon)) {
			$response->message 		= Rb_Text::_('PLG_PAYCART_DISCOUNTRULE_COUPON_INVALID');
			return false;
		}
		
		return parent::isApplicable($request, $response);
	}
	
}
