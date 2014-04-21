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
 * Enter description here ...
 * @author mManishTrivedi
 *
 */
class PaycartLibDiscountruleTest extends PayCartTestCase
{	
	/**
	 * 
	 * Test discount rule applicability
	 * @param PaycartDiscountRuleRequest 	$requestData
	 * @param PaycartDiscountRuleResponse 	$expectedResponseData
	 * @param PaycartDiscountRuleResponse 	$return
	 * 
	 * @dataProvider provider_testIsApplicable
	 */
	public function testIsApplicable($mockData, $setData, $expectedResponseData) 
	{
		$mock = $this->getMock('PaycartDiscountRule', array('getAlreadyAppliedRules', 'getTotalConsumption', 'getTotalConsumptionByBuyer'));
		
		foreach($mockData as $func_name => $returnValue){
			// Set expectations and return values
			$mock->expects($this->once())
				 ->method($func_name)
				 ->will($this->returnValue($returnValue));
		}
		
		foreach ($setData as $key => $value){
			$mock->set($key, $value);
		}

		$cartmock = $this->getMock('PaycartCart');
		$particularmock = $this->getMock('PaycartCartparticular'); 
		$responseData = $mock->isApplicable($cartmock, $particularmock);
		
		foreach ($expectedResponseData as $key=>$expectedValue) {			
			// checking response property
			$this->assertSame($expectedValue, $responseData->$key,"Mismatch $key value");
		}
	}
	
	public function provider_testIsApplicable() 
	{
		// Case-1 : Previous rule has applied and current rule is non-clubbable 
		$mockData1	= new stdClass();		
		$mockData1->getAlreadyAppliedRules	= Array('Discountrule lib object ');
		
		$setData1 = new stdClass();
		$setData1->is_clubbable			 	= false;
		
		$responseData1 = new stdClass();
		$responseData1->error			= true;
		$responseData1->message 		= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_NON_CLUBBABLE');
		$responseData1->messageType		= Paycart::MESSAGE_TYPE_MESSAGE;
		
		// Case-2 :  rule consumtion is equal to usage limit
		$mockData2	= new stdClass();		
		$mockData2->getTotalConsumption  = 5;
		
		$setData2 = new stdClass();
		$setData2->usage_limit = 5;
		
		$responseData2 	= new stdClass();
		$responseData2->error			= true;
		$responseData2->message 		= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_USAGE_LIMIT_EXCEEDED');
		$responseData2->messageType		= Paycart::MESSAGE_TYPE_WARNING;
		
		// Case-3 : buyer consumption limit exceeded
		$mockData3	= new stdClass();
		$mockData3->getTotalConsumption = 50;
		$mockData3->getTotalConsumptionByBuyer	= 10;
		
		$setData3 = new stdClass();
		$setData3->usage_limit  		= 100;
		$setData3->buyer_usage_limit	= 10;		
		
		$responseData3 = new stdClass();
		$responseData3->error			= true;
		$responseData3->message 		= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_BUYER_USAGE_LIMIT_EXCEEDED');
		$responseData3->messageType		= Paycart::MESSAGE_TYPE_WARNING;
		
		// Case-4 : everything is ok   
		$mockData4	= new stdClass();
		$mockData4->getAlreadyAppliedRules		= Array('Discountrule lib object ');
		$mockData4->getTotalConsumption 		= 5;
		$mockData4->getTotalConsumptionByBuyer	= 5;
		
		$setData4 = new stdClass();		
		$setData4->is_clubbable 		= true;
		$setData4->usage_limit  		= 10;		
		$setData4->buyer_usage_limit	= 10;		
		
		$responseData4 = new stdClass();
		$responseData4->error = false;
				
		
		return Array(
						 Array($mockData1, $setData1, $responseData1)
						,Array($mockData2, $setData2, $responseData2)
						,Array($mockData3, $setData3, $responseData3)
						,Array($mockData4, $setData4, $responseData4)
					);
	}
}
