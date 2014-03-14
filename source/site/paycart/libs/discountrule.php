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
 * PaycartDiscountRule
 * @author mManishTrivedi
 * 
 * Assumptions: 
 *	1#. Always applied on cart-particulars.
 * 	2#. Multiple-DiscountRule on one Particular :
 * 			## All Discountrules process either "Before-Taxrules" Or "After-Taxrules"
 *	 		## Rule-Priority decides by sequence field
 * 			## Discount calculate on row-total or actual-price, will be dicided by is_successive field
 * 	 
 */
class PaycartDiscountRule extends PaycartLib
{
	//Table fields : System-specific fields
	protected $discountrule_id; 
	protected $title;
	protected $description;
	protected $published;
	protected $start_date;					
	protected $end_date;
	protected $created_date;	
	protected $modified_date;
	
	//Table fields : Discount-Rule specific
	protected $amount;						// Discount amount
	protected $is_percentage;				// is percentage or flat discount @var boolean
	protected $is_clubbable;				// Is clubbable, applicable with otherdiscount or not, When multiple discount applied
	protected $is_successive;				// Is successive, applicable on row total
	protected $coupon;						// coupon code
	protected $sequence;					// Priority of discount rule
	protected $apply_on;					// entity on which discount rule will be applied. Either cart or Product 
	
	//Table fields : Usage spacific
	protected $buyer_usage_limit;			//Total usage Counter per buyer default=1
	protected $usage_limit;					//Total usage counter of discount-rule 
		
	//Table fields : Processor specific
	protected $processor_classname;				// Processor class name and should be small-caps
	protected $processor_config;
	
	//Lib Specific Fields
	protected $_stopFurtherRules = true; 		//multiple discount further process or not.  
	protected $message;							// Have mapped data. (language id => message) 
		
	
	public function reset() 
	{		
		$this->discountrule_id	=	0; 
		$this->title 		 	=	null;
		$this->description		=	0;
		$this->published		=	1;
		$this->start_date		=	Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->end_date			=	Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->created_date		= 	Rb_Date::getInstance();
		$this->modified_date	=	Rb_Date::getInstance();
		
		$this->amount			=	0;
		$this->is_percentage	=	1;
		$this->is_clubbable		=	1;
		$this->is_successive	=	0;
		$this->coupon			=	NULL;
		$this->sequence			=	0;
		$this->apply_on			= 	'';	
				
		$this->buyer_usage_limit =	1;
		$this->usage_limit		 =	1;
		
		$this->processor_classname	= Null;
		$this->processor_config		= new Rb_Registry();
				
		return $this;
	}
	
	/**
	 * 
	 * PaycartDiscountRule instance
	 * @param  $id, existing DiscountRule id
	 * @param  $data, required data to bind on return instance	
	 * @param  $dummy1, Just follow code-standards
	 * @param  $dummy2, Just follow code-standards
	 * 
	 * @return PaycartDiscountRule lib instance
	 */
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('discountrule', $id, $data);
	}	
	
	
	/**
	 * 
	 * Process discountrule
	 * @param Paycartcart $cart
	 * @param PaycartCartparticular $particular
	 * @throws InvalidArgumentException
	 * 
	 * @return DiscountRule lib object
	 */
	public function process(Paycartcart $cart, PaycartCartparticular $particular)
	{
		// get Processor instanse. Processor should be autoloaded
		$processor = PaycartFactory::getProcessor(Paycart::PROCESSOR_TYPE_DISCOUNTRULE, $this->processor_classname, $this->processor_config);
		
		// create request and reponse object then process discount-rule
		$request	= $this->createRequest($cart, $particular);
		$response	= $this->createResponse();
		
		$processor->process($request, $response);
		
		// check if exception is occured
		if ($response->exception && $response->exception instanceof Exception) {
			//@PCTODO : Exception handling. better if we will introduce our discount type exception
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

		$total 	= $particular->getTotal();
		
		// @NOTE: Stop next all-rule processing for $particular if meet following any one conditions in current rule
		
		// Check limit of discount 
		if ($total+($response->amount) < 0) {
			$this->_stopFurtherRules = true;
			//@PCTODO :: notify to user or Log it
			return $this;
		} 
		
		// @NOTE: should not fall behind of minimum-price limit.
//		if ($total+($response->amount) <= $particular->getMinimumPrice() ) {
//			$this->_stopFurtherRules = true;
//			// @PCTODO :: notify to user or Log it
//			return $this;
//		}

		// apply discounted amount
		$particular->addDiscount($response->amount);
		
		//@PCTODO :: auto reinitailize cart price  when add discount
		
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
		
		
		// not further-rules processing 
		if ($response->stopFurtherRules) {
			$this->_stopFurtherRules = true;
		}
		
		return $this;
	}
	
	/**
	 * 
	 * create request object 
	 * @param PaycartCart $cart
	 * @param PaycartCartparticular $particular
	 * 
	 * @return PaycartDiscountRuleRequest object
	 */
	protected function createRequest(PaycartCart $cart, PaycartCartparticular $particular)
	{
		$request 	= new PaycartDiscountruleRequest();
		
		// rule specific data
		$request->rule_isPercentage 	= $this->is_percentage;
		$request->rule_isSuccessive		= $this->is_successive;
		$request->rule_amount			= $this->amount;
		$request->rule_isClubbable		= $this->is_clubbable;
		$request->rule_usageLimit		= $this->usage_limit;
		$request->rule_buyerUsageLimit	= $this->buyer_usage_limit;
		$request->rule_coupon			= $this->coupon;
		
		// Particular specific data
		$request->particular_unit_price				= $particular->getUnitPrice();
		$request->particular_quantity				= $particular->getQuantity();
		$request->particular_price					= $particular->getPrice();		//basePrice = unitPrice * Quantity
		$request->particular_total				 	= $particular->getTotal();
		$request->particular_coupon				 	= $cart->coupon;				// @PCTODO: get Posted coupon code from cart  
		$request->particular_previousAppliedRules 	= $particular->_appliedDiscountRules;
		
		//@PCTODO:: set following stuff
		$request->cart_particular_quantity	=	10;
		$request->cart_total				=	$cart->getTotal();
		$request->cart_shipping_address_id	=	$cart->getShippingAddress();
		$request->cart_billing_address_id	=	$cart->getBillingAddress();
		
		//@PCTODO:: set following stuff
		$request->buyer_id			=	$cart->getBuyer();
		
		// usage specific data
		//@PCTODO :: call to get used counter of Rule
		$request->usage_rule_consumption	= 50;
		$request->usage_buyer_consumption	= 0;
		
		return $request;
	}

	/**
	 * 
	 * create response object
	 * 
	 * @return PaycartDiscountRuleResponse object
	 */
	protected function createResponse()
	{
		return new PaycartDiscountruleResponse();
	}
	
	/**
	 * @PCTODO :: Discountrule-Helper.php 
	 * @param PayacartCart $cart
	 * @param PaycartCartparticular $particular
	 * @param array $ruleIds Applicable rules
	 * 
	 * @return bool value
	 */
	protected function _processDiscountRule(PayacartCart $cart, PaycartCartparticular $particular, Array $ruleIds)
	{
		//@PCTODO : define constant for applicable_on
		// {product_price, shipping_price, cart-price}
		$appliedOn = 'product_price';
		
		//@PCTODO :: move into model 
		// get applicable rules
		$condition = ' 	`discountrule_id` IN ('.array_values($ruleIds).') AND '.
					 '	`published` = 1 AND '.
					 '	`start_date` <= now() AND '.
					 '	`end_date` >= now() AND '. 
					 '	`applied_on` LIKE '."'$appliedOn'" ;
		
		// sort applicable rule as per sequence.
		$records = PaycartFactory::getModel('discountrule')
							->getData($condition, '`sequence`');

		// no rule for processing
		if (!$records) {
			return true;
		}
		
		foreach ($records as $id=>$record) {
			
			$discountRule = PaycartDiscountRule::getInstance($id, $record);
			
			// process discount
			$discounRule->process($cart, $particular);
			
			//@PCTODO:: set previous applied rules on particular
			$particular->_appliedDiscountRules[] = $discountRule; 
			
			// no further process
			if ($discountRule->get('_stopFurtherRules')) {
				break;
			}			
		}
		
		return true;
	}	
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::_save()
	 */
	protected function _save($previousObject) 
	{
		// save core table data
		$id = parent::_save($previousObject);
		
		if(!id) {
			return false;
		}
		
		$data = Array();
		
		// Save multilanguage stuff
		$modelLang = PaycartFactory::getModel('discountrulelang');
		
		foreach ($this->message as $langCode => $message) {
			$data['discountrule_id'] = $id;
			$data['lang_code'] 		 = $langCode;
			$data['message'] 		 = $message;
		}
		
		// Before save you need to delete previous data
		$modelLang->deleteMany(Array('discountrule_id'=>$id));
		
		//@PCTODO:: notify to admin if save failed
		// save language specific content
		$modelLang->save($data);
		
		return $id;
	}
}
