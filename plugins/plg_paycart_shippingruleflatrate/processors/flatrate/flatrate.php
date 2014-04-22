<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartShippngrule.Processor
* @subpackage       FlatRate
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Flat Rate Processor
 * 
 * Below are the list of parameters, which are part of this processor
 * billing_price
 *  	According to weight : 'WEIGHT'
 *  	According to price	: 'PRICE'
 *  
 * weight_range : min <= to < max, will be used when "billing_price = PRICE"
 * 		RANGE : array(min => '', max => '', price => '');
 * 		convert it into object before using it.
 * 
 * price_range  : min <= to < max, will be used when "billing_price = PRICE"
 * 		RANGE : array(min => '', max => '', price => '');
 * 		convert it into object before using it.
 * 
 * Out of Range
 * 		Apply the cost of highest defined range : HIGHEST_RANGE_PRICE
 * 		Do not Apply : DO_NOT_APPLY
 * 
 * @author Gaurav Jain
 */
class PaycartShippingruleProcessorFlatRate extends PaycartShippingruleProcessor
{
	const HIGHEST_RANGE_PRICE 	= "HIGHEST_RANGE_PRICE";
	const DO_NOT_APPLY 			= "DO_NOT_APPLY";
	const WEIGHT 				= "WEIGHT";
	const PRICE 				= "PRICE";
	
	public function getPackageShippingCost(PaycartShippingruleRequest $request, PaycartShippingruleResponse $response)
	{
		if($this->processor_config->billing_price === self::WEIGHT){
			$shipping_cost = $this->getTotalPackageShippingCostByWeight($request);
		}		
		else{
			$shipping_cost = $this->getTotalPackageShippingCostByPrice($request);
		}
		
		// set error resosnse if there is error 
		if($shipping_cost === false){
			$response->amount = false;
			return $response;
		}
		
		$response->amount = $shipping_cost + $this->rule_config->handling_charge;		
		return $response;
	}
	
	protected function getTotalPackageShippingCostByWeight(PaycartShippingruleRequest $request)
	{
		//IMP: currently support only for one pckage
		
		// get packaging weight
		$weight = $this->rule_config->packaging_weight;
		
		$products = $request->cartparticulars;
		foreach($products as $product){
			/* @var $product PaycartShippingruleRequestProduct */
			$weight += $product->weight * $product->quantity;
		}
		 
		// if range is empty empty then return false
		$ranges = $this->processor_config->weight_range;
		if($ranges === false || empty($ranges)){
			return false;		
		}
		
		$shipping_cost = false;
		$max_range_price = 0;
		$max_range 		= 0;
		
		//compare mim as >= and max as <
		foreach($ranges as $range){
			$range = (object)$range;
			if($weight >= $range->min && $weight < $range->max){
				return $range->price;
			}			
			
			if($range->max >= $max_range){
				$max_range_price = $range->price;
				$max_range = $range->max;
			}
		}
		
		// out of range behaviour
		if($this->processor_config->out_of_range == self::HIGHEST_RANGE_PRICE){
			return $max_range_price;
		} 
		
		return false;		
	}
	
	protected function getTotalPackageShippingCostByPrice(PaycartShippingruleRequest $request)
	{
		$products = $request->cartparticulars;
		$price = 0;
		foreach($products as $product){
			/* @var $product PaycartShippingruleRequestProduct */
			$price += $product->total * $product->quantity;
		}
		 
		// if range is empty empty then return false
		$ranges = $this->processor_config->price_range;
		if($ranges === false || empty($ranges)){
			return false;		
		}
		
		$shipping_cost = false;
		$max_range_price = 0;
		$max_range 		= 0;
		
		//compare mim as >= and max as <
		foreach($ranges as $range){			
			$range = (object)$range;
			if($price >= $range->min && $price < $range->max){
				return $range->price;
			}			
			
			if($range->max >= $max_range){
				$max_range_price = $range->price;
				$max_range = $range->max;
			}
		}
		
		// out of range behaviour
		if($this->processor_config->out_of_range == self::HIGHEST_RANGE_PRICE){
			return $max_range_price;
		} 
		
		return false;
	}
}