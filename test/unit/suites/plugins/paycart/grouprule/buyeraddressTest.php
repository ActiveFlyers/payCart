<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in 
*/

$file = JPATH_SITE.'/plugins/paycart/grouprulebuyeraddress/rules/buyeraddress/buyeraddress.php';

if(file_exists($file)) {
	require_once $file;
	function PaycartGroupruleBuyeraddress(){};
}

/**
 * 
 * Group Rule Buyer Address Test 
 * @author Gaurav Jain
 * @requires function PaycartGroupruleBuyeraddress
 */
class PaycartPluginsGroupruleBuyeraddressTest extends PayCartTestCase
{
	public static function providerTestIsApplicable()
    {
    	// ALL ANY, buyer, with empty address
    	$case[0] = array(
    				1,
    				array('country' => '', 'state' => '', 'city' => '', 'zipcode' => ''),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'any',
	    									'countries' => array(),
	    									'states_assignment' => 'any',
	    									'states' => array(),
	    									'cities_assignment' => 'any',
	    									'cities' => array(),
	    									'countries_assignment' => 'any',
	    									'countries' => array(),
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				true    					  
    			);

    	// ALL ANY, NO Buyer, with empty address
		$case[1] = array(
    				0,
    				array('country' => '', 'state' => '', 'city' => '', 'zipcode' => ''),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'any',
	    									'countries' => array(),
	    									'states_assignment' => 'any',
	    									'states' => array(),
	    									'cities_assignment' => 'any',
	    									'cities' => array(),
	    									'countries_assignment' => 'any',
	    									'countries' => array(),
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				true    					  
    			);
    			
    			
    	// Selected country, other ANY, Buyer has Country 
		$case[2] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'selected',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'any',
	    									'states' => array(),
	    									'cities_assignment' => 'any',
	    									'cities' => array(),
	    									'countries_assignment' => 'any',
	    									'countries' => array(),
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				true    					  
    			);
    			
    	// Selected country, other ANY, Buyer does not have Country 
		$case[3] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'selected',
	    									'countries' => array('USA', 'AUS'),
	    									'states_assignment' => 'any',
	    									'states' => array(),
	    									'cities_assignment' => 'any',
	    									'cities' => array(),	    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				false    					  
    			);
    			
    			
    	// Except country, other ANY, Buyer has Country, false 
		$case[4] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'except',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'any',
	    									'cities' => array(),	    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				false    					  
    			);
    			
    	// Except country, other ANY, Buyer does not have Country, true 
		$case[5] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'except',
	    									'countries' => array('USA', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'any',
	    									'cities' => array(),	    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				true    					  
    			);
    			
    	// Selected state, other ANY, Buyer has state 
		$case[6] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'selected',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'any',
	    									'cities' => array(),	    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				true    					  
    			);
    			
    	// Selected state, other ANY, Buyer does not have state 
		$case[7] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'selected',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('MP', 'GJ'),
	    									'cities_assignment' => 'any',
	    									'cities' => array(),	    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				false    					  
    			);
    			
    			
    	// Except State, other ANY, Buyer has state, fasle 
		$case[8] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'any',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'except',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'any',
	    									'cities' => array(),	    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				false    					  
    			);
    			
    	// Except state, other ANY, Buyer does not have state, true 
		$case[9] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'any',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'except',
	    									'states' => array('MP', 'GJ'),
	    									'cities_assignment' => 'any',
	    									'cities' => array(),	    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				true    					  
    			);
    			
    	// Selected city, other ANY, Buyer has city 
		$case[10] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'selected',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'selected',
	    									'cities' => array('BHL', 'UDP'),	    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				true    					  
    			);
    			
    	// Selected city, other ANY, Buyer does not have city 
		$case[11] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'selected',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'selected',
	    									'cities' => array('JPR', 'UDP'),	    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				false    					  
    			);
    			
    			
    	// Except City, other ANY, Buyer has city, fasle 
		$case[12] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'any',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'except',
	    									'cities' => array('BHL', 'UDP'),		    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				false    					  
    			);
    			
    	// Except state, other ANY, Buyer does not have state, true 
		$case[13] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'any',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'except',
	    									'states' => array('MP', 'GJ'),
	    									'cities_assignment' => 'except',
	    									'cities' => array('JPR', 'UDP'),	    									
	    									'min_zipcode' => '',
	    									'max_zipcode' => ''),
    								)),
    				true    					  
    			);
    			
    	// Selected zipcode, other ANY, Buyer has zipcode 
		$case[14] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'selected',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'selected',
	    									'cities' => array('BHL', 'UDP'),	    									
	    									'min_zipcode' => '300000',
	    									'max_zipcode' => '400000'),
    								)),
    				true
    			);
    			
    	// Selected zipcode, other ANY, Buyer has lower zipcode 
		$case[15] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'selected',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'selected',
	    									'cities' => array('BHL', 'UDP'),	    									
	    									'min_zipcode' => '400000',
	    									'max_zipcode' => '500000'),
    								)),
    				false    					  
    			);
    			
    	// Selected zipcode, other ANY, Buyer has higher zipcode 
		$case[16] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'shipping',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'selected',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'selected',
	    									'cities' => array('BHL', 'UDP'),	    									
	    									'min_zipcode' => '200000',
	    									'max_zipcode' => '300000'),
    								)),
    				false    					  
    			);
    			
    	// billing address 
		$case[17] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'billing',
    					  'address' => 
    							array(
    									array(
	    									'countries_assignment' => 'selected',
	    									'countries' => array('IND', 'AUS'),
	    									'states_assignment' => 'selected',
	    									'states' => array('RJ', 'GJ'),
	    									'cities_assignment' => 'selected',
	    									'cities' => array('BHL', 'UDP'),	    									
	    									'min_zipcode' => '200000',
	    									'max_zipcode' => '300000'),
    								)),
    				false    					  
    			);
    			
    	// empty address type 
		$case[18] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => ''),
    				false    					  
    			);
    			
    	// invalid address type 
		$case[19] = array(
    				1,
    				array('country' => 'IND', 'state' => 'RJ', 'city' => 'BHL', 'zipcode' => '311001'),
    				array('address_type' => 'xyz'),
    				false    					  
    			);    			
    	return $case;
    }
    
	 /**
     * @dataProvider providerTestIsApplicable
     */	
	public function testIsApplicable($buyer_id, $address, $params, $result)
	{	 
		// set groups on mock object of user
		$mockUser = $this->getMock('PaycartBuyer', array('getShippingAddress', 'getBillingAddress', 'getId'));
		// 	Set expectations and return values
		$mockUser->expects($this->any())
			 ->method('getId')
			 ->will($this->returnValue($buyer_id));
				 
		$address = (object)$address;
		
		if($buyer_id && $params['address_type'] == 'billing'){
			
			// Set expectations and return values
			$mockUser->expects($this->once())
				 ->method('getBillingAddress')
				 ->will($this->returnValue($address));			
		}
		elseif($buyer_id && $params['address_type'] == 'shipping'){
			// Set expectations and return values
			$mockUser->expects($this->once())
				 ->method('getShippingAddress')
				 ->will($this->returnValue($address));
		}
		else{
			// do nothing
		}

		$testObject = new PaycartGroupruleBuyerAddress($params);
		TestReflection::setValue('Rb_Lib', 'instance', array('paycartbuyer' => array($buyer_id => $mockUser)));
				
		$this->assertEquals($result, $testObject->isApplicable($buyer_id));

		//clean up
		TestReflection::setValue('Rb_Lib', 'instance', array());		
	}
}