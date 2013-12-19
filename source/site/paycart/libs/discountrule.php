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
	
	// Usage spacific
	protected $buyer_usage_limit;			//Total usage Counter per buyer default=1
	protected $usage_limit;					//Total usage counter of discount-rule 
		
	//Table fields : Processor specific
	protected $processor_type;				// Processor name, must be unique(processor class name) and small-caps
	protected $processor_config;
	
	
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
		
		$this->processor_type	= Null;
		$this->processor_config	= new Rb_Registry();
				
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
	 * Enter description here ...
	 * @param $entity : @TODO:: $entity : entity-object either Product/cart-particulars or cart ?? 
	 * 
	 * @return 
	 */
	public function process($entity)
	{
		// get Processor instanse. Processor should be autoloaded
		$processor = new $this->processor_type;
		
		//set processor configuration
		$processor->set('config', $this->processor_config);
		
		// BUILD request object
		$request = $this->buildRequest($entity);
		
		// PROCESS discount-rule if core-applicability is ok
		if ($processor->coreCheckup($request)) {
			return false;
		}
		
		$response = $processor->process($request);
		
		if ($response->error) {
			//@PCTODO : Exception/Error handling
			return false;
		}
		
		// @PCTODO :: Check limit of discount or should not fall behind of minimum price limit.
		$total = $entity->get('total');
		
		if($total-$response->amount < 0) {
			// @PCTODO :: notify to user or Log it
			return false;
		}

		$entity->set('total', $total-$response->amount);
		
		//@PCTODO :: invoke method to track usage
		
		return true;
	}
	
	
	/**
	 * 
	 * create build request object
	 * @param unknown_type $entity
	 * 
	 * @return PaycartDiscountRuleRequest object
	 */
	protected function buildRequest($entity)
	{
		$request 	= new PaycartDiscountRuleRequest();
		
		// discount data
		$request->isPercentage 	= $this->is_percentage;
		$request->isSuccessive	= $this->is_successive;
		$request->amount		= $this->amount;
		
		//@PCTODO:: validation required,  $entity must be have total and basePrice
		$request->total			= $entity->total;
		$request->price			= $entity->price;	//basePrice = unitPrice * Quantity
		
		//set entity and rule object
		$request->_entity 		= (object) $entity->toArray();
		$request->_discountRule = (object) $this->toArray();
		
		return $request;
	} 
	
	/**
	 * 
	 * Before execution :: get all applicable ruleid
	 * @param array $rulesId  : have all applicable discount rule id
	 * @param unknown_type $entity : $entity data
	 * @param unknown_type $entityType : aplicable on
	 */
	protected function _processDiscountRule(Array $ruleIds, $entity, $entityType)
	{
		
		$condition = '`dicountrule_id` IN ('.array_values($ruleIds).') AND `applied_on` LIKE '."'$entityType'" ;
		
		// sort applicable rule as per sequence.
		$records = PaycartFactory::getModel('discountrule')
							->getData($condition, '`sequence`');

		// no rule for processing
		if (!$records) {
			return true;
		}
		
		$flag = false;
		foreach ($records as $id=>$record) {
			
			$discounRule = PaycartDiscountRule::getInstance($id, $record);
			
			// any-one discount is already applied. Now checking new discount is clubbable or not 
			if($flag && !$discounRule->get(is_clubbable, true)) {
				continue;
			}
			
			// process discount
			// entity have following stuff::
			// # product / cart details
			// # total and basePrice
			// # buyerdetail like location, id etc
			// # session : where save discount stuff
			//@PCTODO :: get response and handle further processing 
			$discounRule->process($entity);
			
			// No more furthe processing if its a first discount and its non-clubbale
			if(!$flag && !$discounRule->get(is_clubbable, true)) {
				break;
			}
			
			$flag =true;
		}
		
		return true;
	}	
}
