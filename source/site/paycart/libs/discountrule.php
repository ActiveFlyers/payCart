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
class PaycartDiscountrule extends PaycartLib
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
		
	// language specific
	protected $discountrule_lang_id		= 0;
	protected $lang_code 			= '';
	protected $message				= '';		
	
	// others
	protected $_buyergroups			= array();
	protected $_productgroups		= array();
	protected $_cartgroups			= array();
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		
		// IMP :check for class existance
		// 		if class is not loaded alread then it will autoload the class
		// 		We have done this because other request classes are dependent on it 
		if(!class_exists('PaycartDiscountRuleRequest', true)){
			throw new Exception('Class PaycartDiscountRuleRequest not found');
		}		
	}
	
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
		
		$this->discountrule_lang_id		= 0;
		$this->lang_code			= PaycartFactory::getLanguage()->getTag(); //@PCFIXME
		$this->message				= '';
		
		$this->_buyergroups			= array();
		$this->_productgroups		= array();
		$this->_cartgroups			= array();
				
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
	 * @return PaycartDiscountRuleProcessor
	 */
	public function getProcessor()
	{
		// get Processor instanse. Processor should be autoloaded
		$processor = PaycartFactory::getProcessor(Paycart::PROCESSOR_TYPE_DISCOUNTRULE, $this->processor_classname, $this->processor_config);
		/* @var $processor PaycartDiscountRuleProcessor */ 
		
		$processor->processor_config = $this->getProcessorConfig();
		$processor->rule_config 	 = $this->getRuleconfigRequestObject();
		$processor->global_config 	 = $this->getGlobalconfigRequestObject();
		
		return $processor;
	}
	
	/**
	 * Get processor config
	 */
	function getProcessorConfig()
	{		
		return $this->processor_config->toObject();
	}
		
	public function getAlreadyAppliedRules()
	{
		return array(); //@PCTODO :
	}
	
	public function getTotalConsumption()
	{
		return 0; //@PCTODO :
	}
	
	public function getTotalConsumptionByBuyer($buyer_id)
	{
		return 0; //@PCTODO :
	}
	
	/**
	 * 
	 * Applicability check  
	 * @param Paycartcart $cart
	 * @param PaycartCartparticular $cartparticular
	 * @return boolean type if applicable otherwise false
	 */
	public function isApplicable(Paycartcart $cart, PaycartCartparticular $cartparticular)
	{		
		$response = new stdClass();
		$response->error = true;
		
		// if discount is already applied and current discount is non-clubbale
		// then return false 
		$appliedRules = $this->getAlreadyAppliedRules();
		if (!empty($appliedRules) && !$this->is_clubbable) {
			$response->message 		= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_NON_CLUBBABLE');
			$response->messageType	= Paycart::MESSAGE_TYPE_MESSAGE;
			return $response; 
		}
				
		// stop further rule-processing, if usage limit exceeded		
		if ($this->getTotalConsumption() >= $this->usage_limit) { // @PCTODO: what about unlimited 
			$response->message 		= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_USAGE_LIMIT_EXCEEDED');
			$response->messageType	= Paycart::MESSAGE_TYPE_WARNING;
			return $response;
		}
		
		// stop further processing, if rule's buyer-usage limit exceeded
		if ($this->getTotalConsumptionByBuyer($cart->getBuyer()) >= $this->buyer_usage_limit) {
			$response->message 		= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_BUYER_USAGE_LIMIT_EXCEEDED');
			$response->messageType	= Paycart::MESSAGE_TYPE_WARNING;
			return $response;
		}

		$response->error = false;
		return $response;
	}
	
	/**
	 * @return PaycartDiscountRuleRequest object
	 */
	public function getRequestObject(PaycartCart $cart, PaycartCartparticular $cartparticular)
	{
		/* @var $helperRequest PaycartHelperRequest */
		$helperRequest 			= PaycartFactory::getHelper('request');		
		
		$request 							= new PaycartDiscountruleRequest();		
		$request->cartparticular 			= $helperRequest->getCartparticularObject($cartparticular);
		$request->shipping_address			= $helperRequest->getBuyeraddressObject($cart->getShippingAddress(true));
		$request->billing_address			= $helperRequest->getBuyeraddressObject($cart->getBillingAddress(true));
		$request->buyer						= $helperRequest->getBuyerObject($cart->getBuyer(true));
		
		//@PCTODO :: need coupon treatment
		$request->cartparticular->coupon		= $cart->getPromotions();
		
		// amount on which discount should be applied
		$request->discountable_amount = $request->cartparticular->price;
		
		// If discount is successive/row total then applied on particular total.
		// It will use on multi discount
		if ($this->is_successive) {
			$request->discountable_amount = $request->cartparticular->total;
		}
		
		return $request;
	}
	
	/**
	 * @return PaycartDiscountruleRequestRuleconfig
	 */
	public function getRuleconfigRequestObject()
	{
		$object = new PaycartDiscountruleRequestRuleconfig();
		// rule specific data
		$object->is_percentage 		= $this->is_percentage;
		$object->is_successive 		= $this->is_successive;
		$object->amount				= $this->amount;
		$object->is_clubbable		= $this->is_clubbable;
		$object->usage_limit		= $this->usage_limit;
		$object->buyer_usage_limit 	= $this->buyer_usage_limit;
		$object->coupon				= $this->coupon;
		
		return $object;
	}
	
	/**
	 * @return PaycartDiscountruleRequestGlobalconfig	 
	 */
	public function getGlobalconfigRequestObject()
	{
		$object = new PaycartDiscountruleRequestGlobalconfig();		
		return $object;
	}

	/**
	 * 
	 * create response object
	 * 
	 * @return PaycartDiscountRuleResponse object
	 */
	function getResponseObject()
	{
		return new PaycartDiscountruleResponse();
	}
	
	/**
	 * @PCTODO :: Discountrule-Helper.php 
	 * @param PayacartCart $cart
	 * @param PaycartCartparticular $cartparticular
	 * @param array $ruleIds Applicable rules
	 * 
	 * @return bool value
	 */
	protected function _processDiscountRule(PayacartCart $cart, PaycartCartparticular $cartparticular, Array $ruleIds)
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
			$discounRule->process($cart, $cartparticular);
			
			//@PCTODO:: set previous applied rules on particular
			$cartparticular->_appliedDiscountRules[] = $discountRule; 
			
			// no further process
			if ($discountRule->get('_stopFurtherRules')) {
				break;
			}			
		}
		
		return true;
	}	
	
	public function toArray()
	{
		$data = parent::toArray();

		$data['_buyergroups'] 	= $this->_buyergroups;
		$data['_productgroups'] = $this->_productgroups;
		$data['_cartgroups'] 	= $this->_cartgroups;

		return $data;
	}
	
	protected function _save($previousObject)
	{
		$id = parent::_save($previousObject);
		
		// if save fail
		if (!$id) { 
			return false;
		}
		
		$model = $this->getModel();
		$model->saveGroups($id, Paycart::GROUPRULE_TYPE_BUYER, $this->_buyergroups);
		$model->saveGroups($id, Paycart::GROUPRULE_TYPE_PRODUCT, $this->_productgroups);
		$model->saveGroups($id, Paycart::GROUPRULE_TYPE_CART, $this->_cartgroups);
		
		return $id;
	}
	
	function bind($data, $ignore = Array()) 
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		
		//PCTODO: Change weight, height, width, length etc in a format as per set weight/dimension unit
		
		parent::bind($data, $ignore);		
		
		if(!isset($data['_buyergroups'])) {
			$this->_buyergroups = $this->_getGroups(Paycart::GROUPRULE_TYPE_BUYER);
		}
		else{
			$this->_buyergroups = $data['_buyergroups'];
		}
		
		if(!isset($data['_productgroups'])) {
			$this->_productgroups = $this->_getGroups(Paycart::GROUPRULE_TYPE_PRODUCT);
		}
		else{
			$this->_productgroups = $data['_productgroups'];
		}
		
		if(!isset($data['_cartgroups'])) {
			$this->_cartgroups = $this->_getGroups(Paycart::GROUPRULE_TYPE_CART);
		}
		else{
			$this->_cartgroups = $data['_cartgroups'];
		}	
		
		return $this;
	}	
	
	protected function _getGroups($type)
	{
		if(!$this->getId()){
			return array();
		}
		
		return $this->getModel()->getGroups($this->getId(), $type);		
	}
	
	public function getProcessorConfigHtml()
	{
		$response = $this->getResponseObject();
		$this->getProcessor()->getConfigHtml(new PaycartDiscountRuleRequest, $response);
		return $response->configHtml;
	}
	
	/**
	 * Invoke on usage tracking 
	 */
	public function getType()
	{
		return Paycart::PROCESSOR_TYPE_DISCOUNTRULE; 
	}
	
}