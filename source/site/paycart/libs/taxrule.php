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
	
	protected $message				= '';
	
	
	function reset()
	{
		$this->taxrule_id 			= 0;
		$this->title				= '';
		$this->published			= 1;
		$this->description			= '';
		$this->amount				= 0;
		$this->apply_on				= '';
		$this->processor_classname	= '';
		$this->processor_config		= '';
		$this->created_date			= new Rb_date();
		$this->modified_date		= new Rb_date();
		$this->ordering				= 0;
		
		$this->message				= '';
	}
	
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('taxrule', $id, $data);
	}
	
	/**
	 * Do start processing tax request
	 * @param $entity : product, cart or shipping or any combined object
	 */
	function process($entity)
	{
		$request   = $this->createRequest($entity);
		$response  = $this->createResponse();
		
		$processor = PaycartFactory::getProcessor(paycart::PROCESSOR_TYPE_TAX, $this->processor_classname, $this->getProcessorConfig());
		
		//process current request
		$processor->process($request, $response);
		
		if($response->error){
			//Either handle error here or return response as it is
			return $response;
		}
		
		//PCTODO: update the desired amount(with tax) on which tax has been calculated
		$entity->set('total', ($entity->total+$response->taxAmount) );
		
		return $response;
		
	}
	
	/**
	 * 
	 * Create Request object to be processed
	 * @param $entity
	 */
	function createRequest($entity)
	{
		//build request 
		$request = new PaycartTaxruleRequest();
		
		$request->taxRate 		   = $this->amount;
		
		//PCTODO: Set these details through entity
		$request->taxableAmount    = 0;
		
		$request->buyerCountry     = '';
		$request->buyerVatNumber   = '';
		
		$request->productBasePrice = 0;
		$request->productQuantity  = 0;
			
		$request->cartTax          = 0;
		$request->cartShipping     = 0;
		
		return $request;
	}
	
	/**
	 * 
	 * Create a response object
	 */
	function createResponse()
	{
		return new PaycartTaxruleResponse();
	}
	
	/**
	 * Get processor config
	 */
	function getProcessorConfig($inArray = false)
	{		
		if($inArray){
			return $this->processor_config->toArray();			
		}
		
		return $this->processor_config->toObject();
	}
}
