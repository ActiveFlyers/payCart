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
	 * Processo discount rule
	 * @param $entity : @TODO:: $entity : entity-object  Product/cart-particulars,cart and shipping. 
	 * 
	 * @return DiscountRule lib object 
	 */
	public function process($entity)
	{
		// get Processor instanse. Processor should be autoloaded
		$processor = PaycartFactory::getProcessor(Paycart::PROCESSOR_TYPE_DISCOUNTRULE, $this->processor_classname, $this->processor_config);
		
		// create request and reponse object then process discount-rule
		$request	= $this->createRequest($entity);
		$response	= $this->createResponse();
		
		$processor->process($request, $response);
		
		if ($response->message) {
			//@PCTODO : Exception/Error/Message handling
			return $this;
		}
		
		$total 				= $entity->get('total');
		$discountedAmount 	= $response->amount;
		
		// Stop next all-rule processing if meet following any one conditions in current rule
		
		// Check limit of discount 
		if ($total-$discountedAmount < 0) {
			$this->_stopFurtherRules = true;
			// @PCTODO :: notify to user or Log it
			return $this;
		} 
		// should not fall behind of minimum-price limit.
		if ($total-$discountedAmount <= $entity->minimumPrice ) {
			$this->_stopFurtherRules = true;
			// @PCTODO :: notify to user or Log it
			return $this;
		}

		// apply discounted amount
		$entity->set('total', $total-$discountedAmount);
		//@PCTODO :: invoke method to track usage
		
		// not further-rules processing 
		if ($response->stopFurtherRules){
			$this->_stopFurtherRules = true;
		}
		
		return $this;
	}
	
	
	/**
	 * 
	 * create build request object
	 * @param unknown_type $entity {product, cart, shipping}
	 * 
	 * @return PaycartDiscountRuleRequest object
	 */
	protected function createRequest($entity)
	{
		$request 	= new PaycartDiscountRuleRequest();
		
		// rulespecific data
		$request->rule_isPercentage 	= $this->is_percentage;
		$request->rule_isSuccessive		= $this->is_successive;
		$request->rule_amount			= $this->amount;
		$request->rule_isClubbable		= $this->is_clubbable;
		$request->rule_usageLimit		= $this->usage_limit;
		$request->rule_buyerUsageLimit	= $this->buyer_usage_limit;
		
		//$entity must be have total and basePrice
		$request->entity_total				 = $entity->total;
		$request->entity_price				 = $entity->price;	//basePrice = unitPrice * Quantity
		$request->entity_previousAppliedRule = $entity->previousDiscount;
		
		// usage specific data
		//@PCTODO :: call to get used counter of Rule
		$request->rule_consumption	= 50;
		$request->buyer_consumption	= 0;
		
		return $request;
	}

	/**
	 * 
	 * create response object
	 * 
	 * @return PaycartDiscountRuleRequest object
	 */
	protected function createResponse()
	{
		return new PaycartDiscountRuleResponse();
	}
	
	/**
	 * @PCTODO :: TDS-Helper.php 
	 * Before execution :: get all applicable ruleid
	 * @param array $rulesId  : have all applicable discount rule id
	 * @param unknown_type $entity : $entity data
	 * @param unknown_type $entityType : aplicable on
	 */
	protected function _processDiscountRule(Array $ruleIds, $entity, $appliedOn)
	{
		//PCTODO :: move into model 
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
			// entity have following stuff::
			// # product/cart /shipping details(include minimumPrice of entity)
			// # total and basePrice
			// # buyerdetail like location, id etc
			// # previousDiscount : where save previous-discount stuff
			$discounRule->process($entity);
			
			//$entity->previousdiscount[] = $discountRule; 
			
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
