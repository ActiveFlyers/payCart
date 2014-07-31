<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Taxrule lib
 * @author rimjhim
 *
 *
 *	Assumptions: 
 *	1#. Always applied on cart-particulars.
 * 	2#. Multiple-Taxrule on one Particular :
 * 		## All Taxrule process either "Before-Discuntrules" Or "After-discountrules"
 * 		## Taxrule's amount always calculate on actual-price of particular.
 * 
 */
class PaycartTaxrule extends PaycartLib
{
	protected $taxrule_id     		= 0;
	protected $title	      		= '';
	protected $published      		= 1;
	protected $description    		= '';
	protected $amount	      		= 0;
	protected $apply_on				= '';
	protected $processor_classname	= '';
	protected $processor_config		= '';
	protected $created_date			= null;
	protected $modified_date		= null;
	protected $ordering				= 0;
		
	// language specific
	protected $taxrule_lang_id		= 0;
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
		if(!class_exists('PaycartTaxruleRequest', true)){
			throw new Exception('Class PaycartTaxruleRequest not found');
		}		
	}
	
	function reset()
	{
		$this->taxrule_id 			= 0;
		$this->title				= '';
		$this->published			= 1;
		$this->description			= '';
		$this->amount				= 0;
		$this->apply_on				= '';
		$this->processor_classname	= '';
		$this->processor_config		= new Rb_Registry();
		$this->created_date			= new Rb_date();
		$this->modified_date		= new Rb_date();
		$this->ordering				= 0;
		
		$this->taxrule_lang_id		= 0;
		$this->lang_code			= PaycartFactory::getLanguage()->getTag(); //@PCFIXME
		$this->message				= '';
		
		$this->_buyergroups			= array();
		$this->_productgroups		= array();
		$this->_cartgroups			= array();
		return $this;
	}
	
	/**
	 * @return PaycartTaxrule
	 */
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('taxrule', $id, $data);
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
	
	/**
	 * @return PaycartTaxruleProcessor
	 */
	public function getProcessor()
	{
		$processor = PaycartFactory::getProcessor(paycart::PROCESSOR_TYPE_TAXRULE, $this->processor_classname);
		$processor->processor_config = $this->getProcessorConfig();
		$processor->rule_config 	 = $this->getRuleconfigRequestObject();
		$processor->global_config 	 = $this->getGlobalconfigRequestObject();
		return $processor;
	}
		
	/**
	 * 
	 * Create Request object to be processed 
	 * @param PaycartCart $cart
	 * @param PaycartCartparticular $cartparticular
	 * 
	 * @return PaycartTaxruleRequest object
	 */
	public function getRequestObject(PaycartCart $cart, PaycartCartparticular $cartparticular)
	{	
		/* @var $helperRequest PaycartHelperRequest */	
		$helperRequest 			= PaycartFactory::getHelper('request');		
		
		$request 	= new PaycartTaxruleRequest();
		
		//rule specific data
		$request->taxable_amount		= $this->amount;
		$request->cartparticular 		= $helperRequest->getCartparticularObject($cartparticular);
		$request->shipping_address		= $helperRequest->getBuyeraddressObject($cart->getShippingAddress(true));
		$request->billing_address		= $helperRequest->getBuyeraddressObject($cart->getBillingAddress(true));
		$request->buyer					= $helperRequest->getBuyerObject($cart->getBuyer(true));
		
		return $request;
	}
	
	public function getRuleconfigRequestObject()
	{
		$object = new PaycartTaxruleRequestRuleconfig();
		$object->tax_rate = $this->amount;
		return $object;
	}
	
	public function getGlobalconfigRequestObject()
	{
		$object = new PaycartTaxruleRequestGlobalconfig();
		return $object;
	}
	
	/**
	 * 
	 * Create a response object
	 * 
	 * @return PaycartTaxruleResponse
	 */
	public function getResponseObject()
	{
		return new PaycartTaxruleResponse();
	}
	
	/**
	 * Get processor config
	 */
	function getProcessorConfig()
	{
		return $this->processor_config->toObject();
	}
	
	public function getProcessorConfigHtml()
	{
		$response = $this->getResponseObject();
		$this->getProcessor()->getConfigHtml(new PaycartTaxruleRequest, $response);
		return $response->configHtml;
	}
	

	/**
	 * @PCTODO :: Taxrule-Helper.php 
	 * @param PayacartCart $cart
	 * @param PaycartCartparticular $cartparticular
	 * @param array $ruleIds Applicable rules
	 * 
	 * @return bool value
	 */
	protected function _processTaxrule(PayacartCart $cart, PaycartCartparticular $cartparticular, Array $ruleIds)
	{
		//@PCTODO : define constant for applicable_on
		// {product_price, shipping_price, cart-price}
		$appliedOn = 'product_price';
		
		//@PCTODO :: move into model 
		// get applicable rules
		$condition = ' 	`taxrule_id` IN ('.array_values($ruleIds).') AND '.
					 '	`published` = 1 AND '. 
					 '	`applied_on` LIKE '."'$appliedOn'" ;
		
		// sort applicable rule as per sequence.
		$records = PaycartFactory::getModel('taxrule')
							->getData($condition, '`sequence`');

		// no rule for processing
		if (!$records) {
			return true;
		}
		
		foreach ($records as $id=>$record) {
			
			$taxrule = PaycartTaxrule::getInstance($id, $record);
			
			// process taxrule
			$taxrule->process($cart, $cartparticular);
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
	
	/**
	 * Invoke on usage tracking 
	 */
	public function getType()
	{
		return Paycart::PROCESSOR_TYPE_TAXRULE; 
	}
}