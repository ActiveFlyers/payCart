<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in 
*/

require_once JPATH_SITE.'/plugins/paycart/shippingruleflatrate/processors/flatrate/flatrate.php';
/**
 * 
 * Shipping Rule Flat Rate Processor Test
 * @author Gaurav Jain
 */
class PaycartPluginsShippingruleFlatrateTest extends PayCartTestCase
{
	public static function providerTestGetPackageShippingCostByWeight()
    {
    	// arg format :: array($processor_config, $processor_request, $response);
    	
    	// Weight, no range given
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::WEIGHT, 
    							'out_of_range' => PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE, 
    							'weight_range' => array(), 
    							'price_range' => array()),
    					array('config' 			=> array(
    													'packaging_weight' 	=> 0,
    													'handling_charge'	=> 0),
    						  'products'		=> array(
    													'1' => array('weight' => 1, 'quantity' => 1))
    					),
    					false
    	);
    	    	
    	// weight, range given
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::WEIGHT, 
    						  'out_of_range' => PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE, 
    						  'weight_range' => array(array('min'=>0, 'max'=>5, 'price' => 5)), 
    						  'price_range' => array()),
    					array('config' 			=> array(
    													'packaging_weight' 	=> 0,
    													'handling_charge'	=> 0),
    						  'products'		=> array(
    													'1' => array('weight' => 1, 'quantity' => 1))
    					),
    					5
    	);
    	
    	// weight, range given, lower range limit
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::WEIGHT, 
    						  'out_of_range' => PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE, 
    						  'weight_range' => array(array('min'=>1, 'max'=>5, 'price' => 5)), 
    						  'price_range' => array()),
    					array('config' 			=> array(
    													'packaging_weight' 	=> 0,
    													'handling_charge'	=> 5),
    						  'products'		=> array(
    													'1' => array('weight' => 1, 'quantity' => 1))
    					),
    					10
    	);
    	
    	// weight, mutlirange
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::WEIGHT,
    						  'out_of_range' => PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE, 
    						  'weight_range' => array(array('min'=>1, 'max'=>5, 'price' => 5),
    												  array('min'=>5, 'max'=>10, 'price' => 10)), 
    						  'price_range' => array()), 
    					array('config' 			=> array(
    													'packaging_weight' 	=> 4,
    													'handling_charge'	=> 5),
    						  'products'		=> array(
    													'1' => array('weight' => 1, 'quantity' => 1))
    					),
    					15
    	);
    	
    	// weight, out of range
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::WEIGHT, 
    						  'out_of_range' => PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE, 
    						  'weight_range' => array(array('min'=>1, 'max'=>5, 'price' => 5),
    												 array('min'=>5, 'max'=>15, 'price' => 15),
    												 array('min'=>5, 'max'=>10, 'price' => 10)), 
    						  'price_range' => array()),
    					array('config' 			=> array(
    													'packaging_weight' 	=> 4,
    													'handling_charge'	=> 5),
    						  'products'		=> array(
    													'1' => array('weight' => 12, 'quantity' => 1))
    					),
    					20
    	);
    	
    	// weight, out of range : not applicable
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::WEIGHT, 
    						  'out_of_range' => PaycartShippingruleProcessorFlatRate::DO_NOT_APPLY, 
    						  'weight_range' => array(array('min'=>1, 'max'=>5, 'price' => 5),
    												  array('min'=>5, 'max'=>15, 'price' => 15),
    												  array('min'=>5, 'max'=>10, 'price' => 10)), 'price_range' => array()),
    					array('config' 			=> array(
    													'packaging_weight' 	=> 4,
    													'handling_charge'	=> 5),
    						  'products'		=> array(
    													'1' => array('weight' => 12, 'quantity' => 1))
    					),
    					false
    	);
    	
    	// weight, multiple product
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::WEIGHT, 
    						  'out_of_range' => PaycartShippingruleProcessorFlatRate::DO_NOT_APPLY, 
    						  'weight_range' => array(array('min'=>1, 'max'=>5, 'price' => 5),
    												  array('min'=>5, 'max'=>15, 'price' => 15),
    												  array('min'=>5, 'max'=>10, 'price' => 10)), 'price_range' => array()),
    					array('config' 			=> array(
    													'packaging_weight' 	=> 1,
    													'handling_charge'	=> 5),
    						  'products'		=> array(
    													'1' => array('weight' => 2, 'quantity' => 3),
    													'2' => array('weight' => 2, 'quantity' => 2),
    													'3' => array('weight' => 3, 'quantity' => 1))
    					),
    					20
    	);
    	return $case;
    }
    
	 /**
     * @dataProvider providerTestGetPackageShippingCostByWeight
     */	
	public function testGetPackageShippingCostByWeight($processor_config, $processor_request, $response) 
	{		
		list($processor, $request) = $this->_getProcessorXrequest($processor_config, $processor_request);
		 
		// @PCTODO: Rmeove this code once autoloading to response classes is done  
		$r = new PaycartShippingruleResponse();
		
		$result = $processor->getPackageShippingCost($request, $r);		
		$this->assertEquals($response, $result->amount);
	}
	
	public static function providerTestGetPackageShippingCostByPrice()
    {
    	// price, no range given
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::PRICE, 
    						  'out_of_range' 	=> PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE, 
    						  'weight_range' 	=> array(), 
    						  'price_range' 	=> array()),
    					array('config' 			=> array('handling_charge'	=> 0),
    						  'products'		=> array(
    													'1' => array('total' => 1, 'quanity' => 1))
    					),
    					false
    	);
    	    	
    	// price, range given
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::PRICE, 
    						  'out_of_range' 	=> PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE, 
    						  'price_range' 	=> array(array('min'=>0, 'max'=>5, 'price' => 5)), 
    						  'weight_range'	=> array()),
    					array('config' 			=> array('handling_charge'	=> 0),
    						  'products'		=> array(
    													'1' => array('total' => 1, 'quantity' => 1))
    					),
    					5
    	);
    	
    	// price, range given, lower range limit
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::PRICE, 
    						  'out_of_range' 	=> PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE, 
    						  'price_range' 	=> array(array('min'=>1, 'max'=>5, 'price' => 5)), 
    						  'weight_range' 	=> array()),
    					array('config' 			=> array('handling_charge'	=> 5),
    						  'products'		=> array(
    													'1' => array('total' => 1, 'quantity' => 1))
    					),
    					10
    	);
    	
    	// weight, mutlirange
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::PRICE, 
    						  'out_of_range' 	=> PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE, 
    						  'price_range' 	=> array(array('min'=>1, 'max'=>5, 'price' => 5),
    													 array('min'=>5, 'max'=>10, 'price' => 10)), 
    						  'weight_range' => array()),
    					array('config' 			=> array('handling_charge'	=> 5),
    						  'products'		=> array(
    													'1' => array('total' => 1, 'quantity' => 1))
    					),
    					10
    	);
    	
    	// price, out of range
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::PRICE, 
    						  'out_of_range' 	=> PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE, 
    						  'price_range'	 	=> array(array('min'=>1, 'max'=>5, 'price' => 5),
    													array('min'=>5, 'max'=>15, 'price' => 15),
    													array('min'=>5, 'max'=>10, 'price' => 10)), 'weight_range' => array()),
    					array('config' 			=> array('handling_charge'	=> 5),
    						  'products'		=> array(
    													'1' => array('total' => 16, 'quantity' => 1))
    					),
    					20
    	);
    	
    	// price, out of range : not applicable
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::PRICE, 
    						  'out_of_range' 	=> PaycartShippingruleProcessorFlatRate::DO_NOT_APPLY, 
    						  'price_range' 	=> array(array('min'=>1, 'max'=>5, 'price' => 5),
    													 array('min'=>5, 'max'=>15, 'price' => 15),
    													 array('min'=>5, 'max'=>10, 'price' => 10)), 'weight_range' => array()),
    					array('config' 			=> array('handling_charge'	=> 5),
    						  'products'		=> array(
    													'1' => array('total' => 16, 'quantity' => 1))
    					),
    					false
    	);
    	
    	// price, multiple product
    	$case[] = array(
    					array('billing_price' 	=> PaycartShippingruleProcessorFlatRate::PRICE, 
    						  'out_of_range' => PaycartShippingruleProcessorFlatRate::DO_NOT_APPLY, 
    						  'price_range' => array(array('min'=>1, 'max'=>5, 'price' => 5),
    												array('min'=>5, 'max'=>15, 'price' => 15),
    												array('min'=>5, 'max'=>10, 'price' => 10)), 'weight_range' => array()),
    					array('config' 			=> array('handling_charge'	=> 5),
    						  'products'		=> array(
    													'1' => array('total' => 2, 'quantity' => 3),
    													'2' => array('total' => 2, 'quantity' => 2),
    													'3' => array('total' => 3, 'quantity' => 1))
    					),
    					20
    	);
    	return $case;
    }
    
    /**
     * @dataProvider providerTestGetPackageShippingCostByPrice
     */	
	public function testGetPackageShippingCostByPrice($processor_config, $processor_request, $response) 
	{		
		list($processor, $request) = $this->_getProcessorXrequest($processor_config, $processor_request);
		 
		// @PCTODO: Rmeove this code once autoloading to response classes is done  
		$r = new PaycartShippingruleResponse();
		
		$result = $processor->getPackageShippingCost($request, $r);
		$this->assertEquals($response, $result->amount);		
	}		
	
	private function _getProcessorXrequest($processor_config, $processor_request)
	{
		$processor = new PaycartShippingruleProcessorFlatRate();
		
		// load processor config
		foreach($processor_config as $key => $value){
			$processor->config->$key = $value;	
		}
				
		// create request 
		$request = new PaycartShippingruleRequest();
		
		// load request config
    	$request->config = new PaycartShippingruleRequestConfig();
		foreach($processor_request['config'] as $key => $value){
			$request->config->$key = $value;	
		}
		   	
    	
    	foreach($processor_request['products'] as $product){
    		$req_product = new PaycartShippingruleRequestProduct();
    		
    		foreach($product as $key => $value){
    			$req_product->$key = $value;
    		}
    		
			$request->products[] = $req_product;
		}
		
		return array($processor, $request);
	}
}