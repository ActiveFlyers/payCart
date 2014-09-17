<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Cartparicular Base
 * @author Gaurav Jain
 *
 */
abstract class PaycartCartparticular extends JObject
{
	// Table fields :
	protected $cartparticular_id; 
	protected $particular_id;
	protected $type;
	protected $unit_price;
	protected $buyer_id;
	protected $quantity;
	protected $price;
	protected $tax;
	protected $discount;
	protected $total;
	protected $title;
	protected $message;
	
	protected $_usage		=	Array();
	
	protected $_rule_apply_on = '';
	protected $_applied_promotions = array();
	
	//multiple discount further process or not.
	protected $_stopFurtherDiscounts  = false; 		
	
	// Product specific field	
	protected $height 	= 0;
	protected $width 	= 0;
	protected $length 	= 0;
	protected $weight 	= 0;
	
	
	/**
	 * 
	 * Invoke to get particular instance   
	 * @param unknown_type $type 		: Particular type {shipping, duties, promotion, discount}
	 * @param unknown_type $bind_data	: data bine on particulare instance
	 * 
	 * @since 1.0
	 * @author mManishTrivedi
	 * 
	 * @return  PaycartCartparticular extends instance
	 */
	public static function getInstance($type, $bind_data = array())
	{
		$classname 	= 'PaycartCartparticular'.$type;
		$object 	= 	new $classname();		
	
		$object->bind($bind_data);
		
		return $object;
	}
	
	/**
	 * 
	 * Reinitialize Particular Total with updated values
	 */
	public function updateTotal() 
	{
		$this->price = $this->getUnitPrice() * $this->getQuantity();
		$this->total = $this->getPrice() + $this->getTax() + $this->getDiscount();
				
		return $this;
	}
	
	public function getTotal($is_calculated_amount = false)
	{
		return $this->total;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getTax()
	{
		return $this->tax;
	}
	
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	public function getUnitPrice()
	{
		return $this->unit_price;
	}
	
	public function getPrice()
	{
		return $this->price;
	}
	
	public function getParticularId()
	{
		return $this->particular_id;
	}
	
	public function getDiscount()
	{
		return $this->discount;
	}
	
	public function getHeight()
	{
		return $this->height;
	}

	public function getWeight()
	{
		return $this->weight;
	}

	public function getWidth()
	{
		return $this->width;
	}
	
	public function getLength()
	{
		return $this->length;
	}
	
	function getCartparticularId()
	{
		return $this->cartparticular_id;
	}
	
	function getCartId()
	{
		return $this->cart_id;
	}
	
	/**
	 * 
	 * Invoke to get cartpartiocular usage
	 * 
	 * @return Array of std class
	 */
	public function getUsage()
	{
		return $this->_usage;
	}
	
	public function setQuantity($value)
	{
		$this->quantity = $value;
		$this->updateTotal();

		return  $this;
	}
	
	/**
	 * 
	 * set discount on cart-particular
	 * @param double $value
	 * 
	 */
	public function addDiscount($value)
	{
		// discount must be -ive number or Zero
		if ($value > 0) {
			throw new InvalidArgumentException(Rb_Text::sprintf('COM_PAYCART_DISCOUNT_VALUE_MUST_BE_NEGETIVE', $value)); 
		}
		
		// add value with current discount
		$this->discount += $value;
		
		$this->updateTotal();
	
		return $this;
	}
	
	/**
	 * 
	 * set discount on cart-particular
	 * @param double $value
	 */
	public function addTax($value)
	{
		// Tax must be +ive number or Zero
		if ($value < 0) {
			throw new InvalidArgumentException(Rb_Text::sprintf('COM_PAYCART_TAX_VALUE_MUST_BE_POSITIVE', $value)); 
		}
		
		// add value with current tax
		$this->tax += $value;

		$this->updateTotal();

		return $this;
	}
	
	/**
	 * 
	 * set unit price of particular
	 * @param $value
	 */
	public function setUnitPrice($value)
	{
		$this->unit_price = $value;
		
		$this->updateTotal();
		
		return $this;
	}
	
	/**
	 * 
	 * Invoke to apply discount on cart-particular
	 * @param PaycartCart $cart
	 * 
	 * @return $this
	 */
	public function applyDiscountrules(PaycartCart $cart)
	{
		// get appliable group rule on buyer and product bases
		$applicableGrouprules = $this->getApplicableGrouprules($cart);
		
		// get discount rule according to group applicability
		$discountrules = $this->getDiscountrules($applicableGrouprules);
		
		foreach($discountrules as $discountruleId) {
			// @PCTODO :: error handling 
			$discountrule 	= PaycartDiscountrule::getInstance($discountruleId);
			
			$this->_applyDiscountrule($cart, $discountrule);
			
			// if discount rule not allow further process
			if ($this->_stopFurtherDiscounts) {
				break;
			}
		}
		
		return $this;
	} 
	
	public function _applyDiscountrule(PaycartCart $cart, PaycartDiscountrule $discountrule)
	{
		//1#. first check its applicabiliy
		if( !$this->isApplicableDiscountRule($cart, $discountrule) ){
			return false;
		} 
	
		$processor	  	= $discountrule->getProcessor();
		$request	  	= $discountrule->getRequestObject($cart, $this);
		$response		= $discountrule->getResponseObject();
		
		//2#. Process discount rule
		$processor->process($request, $response);
		
		//3#. check if exception is occured
		if ($response->exception && $response->exception instanceof Exception) {
			throw new Exception($response->message);
		}
		
		$messageKey = $discountrule->getId();
		$coupon = $cart->getPromotions();

		if(!empty($coupon)){
			$messageKey = $coupon;
		}
		
		// notify to admin
		if ( Paycart::MESSAGE_TYPE_ERROR == $response->messageType) {
			$cart->addMessage($messageKey, Paycart::MESSAGE_TYPE_ERROR, $response->message, $this);
			return false;
		}
		
		// show system message to end user 
		// IMP : do not return in case of notice, warning and message
		if (in_array($response->messageType, array(Paycart::MESSAGE_TYPE_NOTICE, Paycart::MESSAGE_TYPE_WARNING, Paycart::MESSAGE_TYPE_MESSAGE))){
			$cart->addMessage($messageKey, $response->messageType, $response->message, $this);
		}

		// if response amount is not set or equal to zero then do nothing
		if(!isset($response->amount) || $response->amount >= 0){
			return false;
		}
		
		$total 	= $this->getTotal();
		
		// @NOTE: Stop next all-rule processing for $cartparticular if meet following any one conditions in current rule
		
		// Check limit of discount 
		if ($total+($response->amount) < 0) { // @PCTODO: should not less than minimum-price limit.
			//@PCTODO :: It should be convey to end user.
			//$cart->addMessage();
			return false;
		}
		
		// apply discounted amount
		$this->addDiscount($response->amount); 
		$this->addUsage($cart, $discountrule, $response);
		return true;
	}
	
	/**
	 * 
	 * Invoke to set usage into particular object
	 * @param PaycartCart $cart
	 * @param PaycartLib  $applied_rule
	 * @param rule response $response
	 */
	public function addUsage(PaycartCart $cart, $rule,  $response)
	{
		//create usage data
		$usage = new stdClass();
		$usage->rule_type			= $rule->getType();
		$usage->rule_id				= $rule->getId();
		$usage->cart_id				= $cart->getId();
		$usage->buyer_id			= $cart->getBuyer();
		$usage->price				= $response->amount;
		$usage->message				= $rule->getMessage();
		$usage->title				= $rule->getTitle();
		
		$this->_usage[] = $usage;
		
		return $this;
	}
	
	public function saveUsage()
	{
		$model = PaycartFactory::getModel('usage');

		foreach ($this->_usage as $usage) {
			$usage->cartparticular_id = $this->particular_id;
			
			$date = new Rb_Date();
			$usage->applied_date 	= $date->toSql();
			$usage->buyer_id		= $this->buyer_id;

			$id = $model->save($usage);
			if(!$id){
				throw new Exception('Error on Usage-Save');
			}
			
			$usage->usage_id = $id;
		}
		
		return $this;
	} 
	
	public function applyTaxrules(PaycartCart $cart)
	{
		$applicableGrouprules = $this->getApplicableGrouprules($cart);
				
		$taxrules = $this->getTaxrules($applicableGrouprules);
		
		foreach($taxrules as $taxruleId){
			// discount rule is appied successfully then create its usage 
			$this->_applyTaxrule($cart, $taxruleId);
		}
		
		return $this;
	} 
	
	public function _applyTaxrule(PaycartCart $cart, $ruleId)
	{
		$taxrule 	= PaycartTaxrule::getInstance($ruleId);			
		
		$processor	  	= $taxrule->getProcessor();
		$request	  	= $taxrule->getRequestObject($cart, $this);
		$response		= $taxrule->getResponseObject();
		
		// Process to apply tax
		$processor->process($request, $response);
		
		// check if exception is occured
		if ($response->exception && $response->exception instanceof Exception) {
			throw new Exception($response->message);
		}
		
		$messageKey = $ruleId;
		
		// notify to admin
		if ( Paycart::MESSAGE_TYPE_ERROR == $response->messageType) {
			$cart->addMessage($messageKey, Paycart::MESSAGE_TYPE_ERROR, $response->message, $this);
			return false;
		}
		
		// show system message to end user 
		// IMP : do not return in case of notice, warning and message
		if (in_array($response->messageType, array(Paycart::MESSAGE_TYPE_NOTICE, Paycart::MESSAGE_TYPE_WARNING, Paycart::MESSAGE_TYPE_MESSAGE))){
			$cart->addMessage($messageKey, $response->messageType, $response->message, $this);
		}

		// add tax
		$this->addTax($response->amount);		
		$this->addUsage($cart, $taxrule, $response);
		return true;
	}
	
	/**
	 * 
	 * Invoke to get taxrules which will apply on all product/cart (Statically) and accroding to applicable grouperules 
	 * @param array $groupRules
	 * 
	 */
	public function getTaxrules(Array $groupRules = Array())
	{
		$query    = new Rb_Query();
		$subquery = new Rb_Query();
		
		if(!empty($groupRules[paycart::GROUPRULE_TYPE_BUYER]) || 
		   !empty($groupRules[paycart::GROUPRULE_TYPE_PRODUCT]) || 
		   !empty($groupRules[paycart::GROUPRULE_TYPE_CART])){
			   	$subquery->select('DISTINCT(`taxrule_id`)')
					 	 ->from('#__paycart_taxrule_x_group');
					 	 
			  	foreach ($groupRules as $ruleType => $rules){
			  		$allGroupIds = implode(',', $rules);

					// if grouprule of the current type exists
                    // then add condition to find matching records of individual grouptype
                    // (i.e. cart,product and buyer)
					if(!empty($groupRules[$ruleType])){
						$subquery->where("`taxrule_id` IN (
						                   SELECT `taxrule_id`  FROM `#__paycart_taxrule_x_group`
						                   WHERE `group_id` in(".implode(',', $rules)."))");
					}
					// if grouprule of the current type doesn't exist
					// then add condition to discard those taxes which has any applicable rule of this type
					else{
						$subquery->where("`taxrule_id` NOT IN (
						                   SELECT  `taxrule_id`  FROM `#__paycart_taxrule_x_group`
						                   WHERE `type` = '".$ruleType."')");
					}
				}
			$joinCondition  = '('.$subquery->__toString(). ') AS `rule_group` ON ( `rule`.`taxrule_id` = `rule_group`.`taxrule_id`)';
			$query->innerJoin($joinCondition);
		}
		
		// get al rules
		$query = new Rb_Query();
		$query->select('DISTINCT `rule`.`taxrule_id`')
				->from('`#__paycart_taxrule` as `rule`')
				->where('`rule`.`apply_on` = "'.$this->_rule_apply_on.'"', 'AND')
				->where('`rule`.`published` =  1', 'AND')							// rule must be publish
				->order('`rule`.`ordering`');
		
		$taxrules = $query->dbLoadQuery()->loadColumn();
		
		if (empty($taxrules)) {
			$taxrules = Array();
		} 
		
		return $taxrules;
	}
	
	/**
	 * 
	 * Invoke to get applvcable discount rule
	 * @param Array $groupRules grouprules_id
	 * 
	 * @return Array()
	 */
	public function getDiscountrules(Array $groupRules = Array())
	{
		$query    = new Rb_Query();
		$subquery = new Rb_Query();

		if(!empty($groupRules[paycart::GROUPRULE_TYPE_BUYER]) || 
		   !empty($groupRules[paycart::GROUPRULE_TYPE_PRODUCT]) || 
		   !empty($groupRules[paycart::GROUPRULE_TYPE_CART])){
			   	$subquery->select('DISTINCT(`discountrule_id`)')
					 	 ->from('#__paycart_discountrule_x_group');
					 	 
			  	foreach ($groupRules as $ruleType => $rules){
			  		$allGroupIds = implode(',', $rules);

					// if grouprule of the current type exists
                    // then add condition to find matching records of individual grouptype
                    // (i.e. cart,product and buyer)
					if(!empty($groupRules[$ruleType])){
						$subquery->where("`discountrule_id` IN (
						                   SELECT `discountrule_id`  FROM `#__paycart_discountrule_x_group`  
						                   WHERE `group_id` in(".implode(',', $rules)."))");
					}

					// if grouprules of the current type don't exist
					// then add condition to discard those discounts which has any applicable rule of this type
					else{
						$subquery->where("`discountrule_id` NOT IN (
						                   SELECT  `discountrule_id`  FROM `#__paycart_discountrule_x_group`  
						                   WHERE `type` = '".$ruleType."')");
					}
				}

				$joinCondition  = '('.$subquery->__toString().') AS `rule_group` ON ( `rule`.`discountrule_id` = `rule_group`.`discountrule_id`)';
				$query->innerJoin($joinCondition);
		}

		// get al rules
		$query->select('DISTINCT `rule`.`discountrule_id`')
				->from('`#__paycart_discountrule` as `rule`')
				->where('`rule`.`apply_on` = "'.$this->_rule_apply_on.'"', 'AND')	// rule apply on condition {product,cart, shipping}
				->where('`rule`.`published` =  1', 'AND')							// rule must be publish
				->where('`rule`.`start_date` <=  NOW()', 'AND')						// rule's start-date should be either today or past day
				// rule's end-date should be either today or future-day. If its 0000-00-00 00:00:00 it means infinite day
				->where('(`rule`.`end_date` >=  NOW() OR `rule`.`end_date` =  "0000-00-00 00:00:00")', 'AND')		
				->order('`rule`.`sequence`');
				
		$where  = "`rule`.`coupon` = ''";
		
		$applied_promotions = $this->_applied_promotions;
		
		if (is_object($applied_promotions)) {
			$applied_promotions = (array)$applied_promotions; 
		}
		
		if(!empty($applied_promotions)){
			//@PCFIXME :: coupon code must be clean with $quote method
			$query->where('( '.$where.' OR `rule`.`coupon` IN ("'.implode('", "', $applied_promotions).'"))');
		}else {
			//get without coupon code discount rules 
			$query->where($where);
		}		
		
		$discountrules =  $query->dbLoadQuery()->loadColumn();
		
		if (empty($discountrules)) {
			$discountrules = Array();
		} 
		
		return $discountrules;
	}
	
	/**
	 * 
	 * Invoke to get applicable discount rule on particular
	 * @param PaycartCart $cart
	 */
	public function getApplicableGrouprules(PaycartCart $cart)
	{
		/* @var $groupHelper PaycartHelperGroup */
		$groupHelper = PaycartFactory::getHelper('group');
		
		$groups = array();
		
		//@PCTODO : caching
		$groups[Paycart::GROUPRULE_TYPE_BUYER] = $groupHelper->getApplicableRules(Paycart::GROUPRULE_TYPE_BUYER, $cart->getBuyer());
		$groups[Paycart::GROUPRULE_TYPE_CART]  = $groupHelper->getApplicableRules(Paycart::GROUPRULE_TYPE_CART, $cart->getId());
		
		$productCartparticulars = $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		$groups[Paycart::GROUPRULE_TYPE_PRODUCT] = array();
		foreach($productCartparticulars as $productCartparticular){
			$groups[Paycart::GROUPRULE_TYPE_PRODUCT] = array_merge($groups[Paycart::GROUPRULE_TYPE_PRODUCT],$groupHelper->getApplicableRules(Paycart::GROUPRULE_TYPE_PRODUCT, $productCartparticular->particular_id));	
		}
		
		return $groups;	
	}
	
	public function save(PaycartCart $cart)
	{
		$data['cart_id'] 		= $cart->getId();
		$data['buyer_id']		= $cart->getBuyer();
		$data['particular_id']	= $this->particular_id;
		$data['type']			= $this->type;
		$data['unit_price']		= $this->unit_price;
		$data['quantity']		= $this->quantity;
		$data['price']			= $this->price;
		$data['tax']			= $this->tax;
		$data['discount']		= $this->discount;
		$data['total']			= $this->getTotal(true);
		$data['title']			= $this->title;
		$data['message']		= $this->message;
		$data['params']			= isset($this->params)?json_encode($this->params):null;
		
		$this->buyer_id			= $cart->getBuyer();
		$model = PaycartFactory::getModel('cartparticular');
		$id = $model->save($data);
		if(!$id){
			throw new Exception('Error on Particular Save');
		}
		
		$this->cartparticular_id = $id;
		
		$this->saveUsage();
		return $this;
	}
	
	/**
	 * 
	 * Invoke to copy current object into stdClass object
	 * 
	 *  @return stdClass object
	 */
	public function toObject() 
	{
		$object = new stdClass();
		
		foreach (get_object_vars($this) as $key => $value)
		{
			// ignore extra variables
			if(preg_match('/^_/',$key)){
				continue;
			}
			
			$object->$key	=	$value;
		}
		
		return $object;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * 
	 * Invoke to get if any specific rule-type already applied on particular
	 * @param $rule_type Processor type {discountrule,taxrule}
	 * 
	 * @return Boolen value
	 */
	public function isAnyRuleApplied($rule_type)
	{
		foreach ($this->_usage as $usage) {
			if ($usage->rule_type === $rule_type) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 
	 * Invoke to check discountrule Applicability  
	 * @param Paycartcart $cart
	 * @param PaycartDiscountrule $discountrule
	 * 
	 * @return boolean type 
	 */
	public function isApplicableDiscountRule(Paycartcart $cart, PaycartDiscountrule $discountrule)
	{		
		
		// if $discountrule is a first discount which in non-clubbable 
		// then it should be applied and then say stop further discountrules.
		if ( !$this->isAnyRuleApplied($this->getType()) && !$discountrule->get('is_clubbable') ) {
			// stop further discountrule processing
			$this->_stopFurtherDiscounts = true;
		}
		 
		// if few discount is already applied and current discount is non-clubbale
		// then return false 
		if ($this->isAnyRuleApplied($discountrule->getType()) && !$discountrule->get('is_clubbable')) {		
			$cart->addMessage(	$discountrule->getId(), Paycart::MESSAGE_TYPE_MESSAGE, 
								JText::_('COM_PAYCART_DISCOUNTRULE_NON_CLUBBABLE'), $this);
			return false;
		}
				
		// stop further rule-processing, if usage limit exceeded		
		if ( $discountrule->getTotalConsumption() >= $discountrule->get('usage_limit') ) {
			$cart->addMessage(	$discountrule->getId(), Paycart::MESSAGE_TYPE_WARNING, 
								JText::_('COM_PAYCART_DISCOUNTRULE_USAGE_LIMIT_EXCEEDED'), $this);
			return false;
		}
		
		// stop further processing, if rule's buyer-usage limit exceeded
		if ($discountrule->getTotalConsumptionByBuyer($cart->getBuyer()) >= $discountrule->get('buyer_usage_limit')) {
			$cart->addMessage(	$discountrule->getId(), Paycart::MESSAGE_TYPE_WARNING, 
								JText::_('COM_PAYCART_DISCOUNTRULE_BUYER_USAGE_LIMIT_EXCEEDED'), $this);
			return false;
		}

		return true;
	}
	
}