<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in 
*/


$file = JPATH_SITE.'/plugins/paycart/shippingruleusps/processors/usps/usps.php';

if(file_exists($file)) {
	require_once $file;
	function PaycartPluginsShippingruleUspsTest(){};
}


/**
 * 
 * Shipping Rule USPS Processor Test
 * @author Gaurav Jain
 * @requires function PaycartPluginsShippingruleUspsTest
 */
class PaycartPluginsShippingruleUspsTest extends PayCartTestCase
{
	public static function providerTestGetPackageShippingCost()
    {
    	// arg format :: array($processor_config, $processor_request, $response);
    	
    	$case[] = array(
    					array(	'user_id'			=> '464GAURA0535',
    							'calculation_mode' 	=> 'ONEPACKAGE', 
    							'service_code' 		=> 'FIRST CLASS',
    							'packaging_type' 	=> 'VARIABLE',
    							'packaging_size' 	=> 'REGULAR',
    							'machinable'		=> true),
    					array('packaging_weight' 	=> 0,
    							'handling_charge'	=> 0),
    					array('delivery_address'	=> array('zipcode' => '27892'),
    					      'origin_address'		=> array('zipcode' => '98001'),
    						  'products'			=> array(
    													'1' => array(
    																'weight' 	=> .25, 
    																'quantity' 	=> 1,
    																'length'	=> 1,
    																'width'		=> 1,
    																'height'	=> 1))
    					),
    					3.4
    	);
  
    	$case[] = array(
    					array(	'user_id'			=> '464GAURA0535',
    							'calculation_mode' 	=> 'ONEPACKAGE', 
    							'service_code' 		=> 'FIRST CLASS',
    							'packaging_type' 	=> 'VARIABLE',
    							'packaging_size' 	=> 'REGULAR',
    							'machinable'		=> true),
    					array('packaging_weight' 	=> 0,
    							'handling_charge'	=> 0),
    					array('delivery_address'	=> array('zipcode' => '27892'),
    					      'origin_address'		=> array('zipcode' => '98001'),
    						  'products'			=> array(
    													'1' => array(
    																'weight' 	=> .25, 
    																'quantity' 	=> 1,
    																'length'	=> 1,
    																'width'		=> 1,
    																'height'	=> 1),
    													'2' => array(
    																'weight' 	=> .25, 
    																'quantity' 	=> 1,
    																'length'	=> 1,
    																'width'		=> 1,
    																'height'	=> 1))
    					),
    					false
    	);
    	
    	$case[] = array(
    					array(	'user_id'			=> '464GAURA0535',
    							'calculation_mode' 	=> 'PERITEM', 
    							'service_code' 		=> 'FIRST CLASS',
    							'packaging_type' 	=> 'VARIABLE',
    							'packaging_size' 	=> 'REGULAR',
    							'machinable'		=> true),
    					array('packaging_weight' 	=> 0,
    							'handling_charge'	=> 5),
    					array('delivery_address'	=> array('zipcode' => '27892'),
    					      'origin_address'		=> array('zipcode' => '98001'),
    						  'products'			=> array(
    													'1' => array(
    																'weight' 	=> .25, 
    																'quantity' 	=> 1,
    																'length'	=> 1,
    																'width'		=> 1,
    																'height'	=> 1),
    													'2' => array(
    																'weight' 	=> .25, 
    																'quantity' 	=> 1,
    																'length'	=> 1,
    																'width'		=> 1,
    																'height'	=> 1))
    					),
    					11.8
    	);
    	
    	return $case;
    }
    
    /**
     * @dataProvider providerTestGetPackageShippingCost
     */	
	public function testGetPackageShippingCost($processor_config, $rule_config, $processor_request, $response) 
	{		
		list($processor, $request) = $this->_getProcessorXrequest($processor_config, $rule_config, $processor_request);
		 
		// @PCTODO: Rmeove this code once autoloading to response classes is done  
		$r = new PaycartShippingruleResponse();
		
		$result = $processor->getPackageShippingCost($request, $r);
		$this->assertEquals($response, $result->amount);		
	}		
	
	private function _getProcessorXrequest($processor_config, $rule_config, $processor_request)
	{
		$processor = new PaycartShippingruleProcessorUsps();
		
		$processor->processor_config = new stdClass();
		// load processor config
		foreach($processor_config as $key => $value){
			$processor->processor_config->$key = $value;	
		}
				
		// create request 
		$request = new PaycartShippingruleRequest();
		
		// load request config
    	$processor->rule_config = new PaycartShippingruleRequestRuleconfig();
		foreach($rule_config as $key => $value){
			$processor->rule_config->$key = $value;	
		}
		   	
    	
    	foreach($processor_request['products'] as $product){
    		$req_product = new PaycartRequestCartparticular();
    		
    		foreach($product as $key => $value){
    			$req_product->$key = $value;
    		}
    		
			$request->products[] = $req_product;
		}
		
		// delivery address
    	$request->delivery_address = new PaycartRequestBuyeraddress();    		
    	foreach($processor_request['delivery_address'] as $key => $value){
    		$request->delivery_address->$key = $value;
    	}
    		
		// origin address
    	$request->origin_address = new PaycartRequestBuyeraddress();    		
    	foreach($processor_request['origin_address'] as $key => $value){
    		$request->origin_address->$key = $value;
    	}
    	
		return array($processor, $request);
	}
}