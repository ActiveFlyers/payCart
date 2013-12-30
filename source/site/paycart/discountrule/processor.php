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
	protected $config ;
	
	public function __construct($config = Array())
	{
		parent::__construct();
		$this->set('config', $config);
		
		return $this;
	}
	
	/**
	 * Method to invoke on Processor edit view
	 * Render extra stuff which is used in processor config
	 *  
	 */
	public function getProcessorHtml(PaycartDiscountRuleRequest $request, PaycartDiscountRuleResponse $response)
	{
		$response->configHtml =  "<div></div>";
		return $response;
	}
	
	/**
	 * 
	 * Applicability check  
	 * @param PaycartDiscountRuleRequest $discountRule
	 * 
	 * @return boolean type if applicable otherwise false
	 */
	protected function isApplicable(PaycartDiscountRuleRequest $request, PaycartDiscountRuleResponse $response)
	{	
		// if discount is already applied and current discount is non-clubbale
		// then return false 
		if (!empty($request->entity_previousAppliedRule) && !$request->rule_isClubbable) {
			$response->message 		= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_NON_CLUBBABLE');
			return false; 
		}
				
		// stop further rule-processing, if usage limit exceeded
		if (count($request->rule_usageLimit) >= $request->rule_consumption) {
			$response->message = Rb_Text::_('COM_PAYCART_DISCOUNTRULE_USAGE_LIMIT_EXCEEDED');
			return false;
		}
		
		// stop further processing, if rule's buyer-usage limit exceeded
		if ($request->buyer_consumption >= $request->rule_buyerUsageLimit) {
			$response->message = Rb_Text::_('COM_PAYCART_DISCOUNTRULE_BUYER_USAGE_LIMIT_EXCEEDED');
			return false;
		}

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
	public function process(PaycartDiscountRuleRequest $request, PaycartDiscountRuleResponse $response) 
	{
		try {
				
			// Step-1: check applicibility
			if (!$this->isApplicable($request, $response)) {
				return $response;
			}
			
			// Step-2: Get Price for discount
			 
			// Price on which discount will applied
			$price = $request->entity_price;
			
			// If discount is successive/row total then applied on total amount.
			// It will use on multi discount
			if ($request->rule_isSuccessive) {
				$price = $request->entity_total;
			}
			
			// Step-3: Calculate discount on Price
			$response->amount =  $this->calculate($price, $request->rule_amount, $request->rule_isPercentage);
			
			// if applied discount is non-clubbable 
			// then stop next all multiple rules
			if (!$request->rule_isClubbable) {
				$response->stopFurtherRules = true;
			}
						
		} catch (Exception $e) {
			$response->message 		= $e->getMessage();
			$response->messageType	= Paycart::MESSAGE_TYPE_ERROR;
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
}


/**
 * 
 * DiscountRuleRequest class required for discounrule processing 
 * @author mManishTrivedi
 *
 */
class PaycartDiscountRuleRequest
{
	// Request Field : Discount speicifc
	public $rule_isPercentage		=	1; 		 
	public $rule_amount	  			=	0;
	public $rule_isSuccessive 		=	1;
	public $rule_isClubbable 		=	1;
	public $rule_usageLimit			=	1;		// rule usage limit
	public $rule_buyerUsageLimit	=	1;		// buyer usage limit as per rule
	
	// Request Field : Cart/Product/Shipping specific
	public $entity_price	 		=	0;	// unitPrice * quantity
	public $entity_total 			=	0;
	public $entity_previousAppliedRule;
	
	// Request Field : Usage data
	public $rule_consumption;				//	rule used counter
	public $buyer_consumption;				//	rule used by buyer
}


/**
 * 
 * PaycartDiscountRuleResponse required after discount rule processing
 * @author mManishTrivedi
 *
 */
class PaycartDiscountRuleResponse
{
	// Response Field : Discounted-Amount
	public $amount 				=	0;
	
	// Response Field : stop all next rules processing. 
	public $stopFurtherRules 	=	false;
	
	// Response Field : need to display any kind of msg for user/admin 	
	public $message				=	null;
	
	// Response Field : {'message', 'warning', 'notice', 'error' }	
	public $messageType			=	null;
	
	// Response Field : Processor config html  	
	public $configHtml				=	'';
	
	
	/**
	 * 
	 * set default value here
	 */
	public function __construct()
	{
		// default system message
		$this->messageType = Paycart::MESSAGE_TYPE_MESSAGE;
		
		return $this;
	}
}
