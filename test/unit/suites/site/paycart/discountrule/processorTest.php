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
// load stub class
require_once 'stubs/processor.php';

class PaycartDiscountRuleProcessorTest extends PayCartTestCase
{
	/**
	 * 
	 * Test getConfigHtml method in discountrule 
	 */
	public function testGetConfigHtml()
	{
		$stub	  = new PaycartDiscountRuleProcessorStub();
		$response = $stub->getConfigHtml(new PaycartDiscountRuleRequest(), new PaycartDiscountRuleResponse());
		
		// test instance
		$this->assertInstanceOf('PaycartDiscountRuleResponse',  $response, "Response must be instance of PaycartDiscountRuleResponse");

		// return config html
		$this->assertSame("<div></div>",$response->configHtml);
	}
	
	
	/**
	 * 
	 * Test getProcessorHtml method in discountrule 
	 */
	public function testGetProcessorHtml()
	{
		$stub	  = new PaycartDiscountRuleProcessorStub();
		$response = $stub->getProcessorHtml(new PaycartDiscountRuleRequest(), new PaycartDiscountRuleResponse());
		
		// test instance
		$this->assertInstanceOf('PaycartDiscountRuleResponse',  $response, "Response must be instance of PaycartDiscountRuleResponse");
		// return confih html
		$this->assertSame("",$response->configHtml);
	}
	
	/**
	 * 
	 * Test discount rule applicability
	 * @param PaycartDiscountRuleRequest 	$requestData
	 * @param PaycartDiscountRuleResponse 	$expectedResponseData
	 * @param PaycartDiscountRuleResponse 	$return
	 * 
	 * * @dataProvider provider_testIsApplicable
	 */
	public function testIsApplicable(PaycartDiscountRuleRequest $requestData, PaycartDiscountRuleResponse $expectedResponseData, $return) 
	{
		$responseData 	= new PaycartDiscountRuleResponse();
		$stub	 		= new PaycartDiscountRuleProcessorStub();
		
		$this->assertSame($return, $stub->stub_testIsApplicable($requestData, $responseData), "Mismatch return-value of isApplicable method");
		
		// test instance
		$this->assertInstanceOf('PaycartDiscountRuleResponse',  $responseData, "Response must be instance of PaycartDiscountRuleResponse");
		
		// test instance property
		foreach ($expectedResponseData as $key=>$expectedValue) {
			
			// checking response property
			$this->assertSame($expectedValue, $responseData->$key,"Mismatch $key value");
		}
	}
	
	public function provider_testIsApplicable() 
	{
	
		// Case-1 : Previous rule has applied and current rule is non-clubbable 
		$requestData1	= new PaycartDiscountRuleRequest();
		
		$requestData1->entity_previousAppliedRules	= Array('Discountrule lib object ');
		$requestData1->rule_isClubbable			 	= false;
		
		$responseData1 = new PaycartDiscountRuleResponse();
		$responseData1->message 		= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_NON_CLUBBABLE');
		$responseData1->messageType		= Paycart::MESSAGE_TYPE_MESSAGE;
		
		// Case-2 :  rule consumtion is equal to usage limit
		$requestData2	= new PaycartDiscountRuleRequest();
		
		$requestData2->rule_usageLimit  = 5;
		$requestData2->rule_consumption = 5;
		
		$responseData2 	= new PaycartDiscountRuleResponse();
		$responseData2->message 		= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_USAGE_LIMIT_EXCEEDED');
		$responseData2->messageType		= Paycart::MESSAGE_TYPE_WARNING;
		
		// Case-3 : buyer consumption limit exceeded
		$requestData3	= new PaycartDiscountRuleRequest();
		$requestData3->rule_usageLimit  	= 100;
		$requestData3->rule_consumption 	= 50;
		
		$requestData3->rule_buyerUsageLimit	=	10;
		$requestData3->buyer_consumption	=	10;
		
		$responseData3 = new PaycartDiscountRuleResponse();
		$responseData3->message 		= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_BUYER_USAGE_LIMIT_EXCEEDED');
		$responseData3->messageType		= Paycart::MESSAGE_TYPE_WARNING;
		
		// Case-4 : everything is ok   
		$requestData4	= new PaycartDiscountRuleRequest();
		$requestData4->entity_previousAppliedRules	= Array('Discountrule lib object ');
		$requestData4->rule_isClubbable 	= true;
		$requestData4->rule_usageLimit  	= 10;
		$requestData4->rule_consumption 	= 5;
		$requestData4->rule_buyerUsageLimit	= 10;
		$requestData4->buyer_consumption	= 5;
		
		$responseData4 = new PaycartDiscountRuleResponse();
		
		
		
		return Array(
						 Array($requestData1, $responseData1, false)
						,Array($requestData2, $responseData2, false)
						,Array($requestData3, $responseData3, false)
						,Array($requestData4, $responseData4, true)
					);
	}
	
	
	/**
	 * 
	 * Test discount rule processor
	 * @param PaycartDiscountRuleRequest $requestData
	 * @param PaycartDiscountRuleResponse $expectedResponseData
	 * 
	 * @cover calculate
	 * @dataProvider provider_testProcess
	 */
	public function testProcess(PaycartDiscountRuleRequest $requestData, PaycartDiscountRuleResponse $expectedResponseData, $return = true) 
	{
		$responseData 	= new PaycartDiscountRuleResponse();
		
		// create stub mock
		$stubMock 		= $this->getMock('PaycartDiscountRuleProcessorStub', Array('isApplicable'), Array($requestData, $responseData));
		
		// mock dependencies
		$stubMock->expects($this->once())				// call on process
			 	 ->method('isApplicable')				// method name
			     ->will($this->returnValue($return));	// return value
		
		
		$responseData = $stubMock->process($requestData, $responseData);
		
		// test instance
		$this->assertInstanceOf('PaycartDiscountRuleResponse',  $responseData, "Response must be instance of PaycartDiscountRuleResponse");
		
		// test instance property
		foreach ($expectedResponseData as $key=>$expectedValue) {
			
			// if you get exception 
			if ('exception' == $key && $responseData->$key ) {
				$this->assertSame($expectedValue->getMessage(), $responseData->$key->getMessage(),"Mismatch Exception message");
				continue;
			}
			
			// checking response property
			$this->assertSame($expectedValue, $responseData->$key,"Mismatch $key value");
		}
	}
	
	public function provider_testProcess() 
	{
		// Case-1 : without any kind of data   
		$requestData1	= new PaycartDiscountRuleRequest();
		$responseData1 	= new PaycartDiscountRuleResponse();
		
		$responseData1->exception = new InvalidArgumentException(Rb_Text::_('COM_PAYCART_DISCOUNTRULE_NOT_ON_ZERO'));
		
		// Case-2 : without discount-amount   
		$requestData2	= new PaycartDiscountRuleRequest();
		$requestData2->entity_total		= 100;
		$requestData2->entity_price 	= 50;
		
		$responseData2 = new PaycartDiscountRuleResponse();
		$responseData2->exception = new InvalidArgumentException(Rb_Text::_('COM_PAYCART_DISCOUNTRULE_IS_NOT_ZERO'));
		
		// Case-3 : Successive and total is zero
		$requestData3	= new PaycartDiscountRuleRequest();
		$requestData3->rule_amount			=	10;
		$requestData3->rule_isSuccessive	=	1;
		$requestData3->entity_price			= 	50;
		$requestData3->entity_total 		= 	0;
		
		$responseData3 = new PaycartDiscountRuleResponse();
		$responseData3->exception = new InvalidArgumentException(Rb_Text::_('COM_PAYCART_DISCOUNTRULE_NOT_ON_ZERO'));
		
		// Case-4 : Non-successive and unit cost is zero   
		$requestData4	= new PaycartDiscountRuleRequest();
		$requestData4->rule_amount		=	10;
		$requestData4->rule_isSuccessive	=	0;
		$requestData4->entity_price		= 	0;
		$requestData4->entity_total 		= 	100;
		
		$responseData4 = new PaycartDiscountRuleResponse();
		$responseData4->exception = new InvalidArgumentException(Rb_Text::_('COM_PAYCART_DISCOUNTRULE_NOT_ON_ZERO'));
		
		// Case-5 : flat,amount, succesive    
		$requestData5	= new PaycartDiscountRuleRequest();
		$requestData5->rule_amount			=	12.29;
		$requestData5->rule_isSuccessive	=	1;
		$requestData5->rule_isPercentage	=	0;
		$requestData5->entity_price			= 	50;
		$requestData5->entity_total 		= 	100;
		
		$responseData5 = new PaycartDiscountRuleResponse();
		$responseData5->amount = 12.29;
		
		// Case-6 : flat, amount, non-succesive    
		$requestData6	= new PaycartDiscountRuleRequest();
		$requestData6->rule_amount			=	13.29;
		$requestData6->rule_isSuccessive	=	0;
		$requestData6->rule_isPercentage	=	0;
		$requestData6->entity_price			= 	50;
		$requestData6->entity_total 		= 	100;
		
		$responseData6 = new PaycartDiscountRuleResponse();
		$responseData6->amount = 13.29;
		
		// Case-7 : percentage, amount, succesive,     
		$requestData7	= new PaycartDiscountRuleRequest();
		$requestData7->rule_amount			=	14.53;
		$requestData7->rule_isSuccessive	=	1;
		$requestData7->rule_isPercentage	=	1;
		$requestData7->entity_price			= 	50;
		$requestData7->entity_total 		= 	129;
		
		$responseData7 = new PaycartDiscountRuleResponse();
		$responseData7->amount = 18.7437;
		
		// Case-8 : percentage, amount, non-succesive    
		$requestData8	= new PaycartDiscountRuleRequest();
		$requestData8->rule_amount			=	25.36;
		$requestData8->rule_isSuccessive	=	0;
		$requestData8->rule_isPercentage	=	1;
		$requestData8->entity_price			= 	482;
		$requestData8->entity_total 		= 	874;
		
		$responseData8 = new PaycartDiscountRuleResponse();
		$responseData8->amount = 122.2352;
		
		
		// Case-9 : percentage, amount, non-succesive, and non-clubbable    
		$requestData9	= new PaycartDiscountRuleRequest();
		$requestData9->rule_amount			=	25.36;
		$requestData9->rule_isSuccessive	=	0;
		$requestData9->rule_isPercentage	=	1;
		$requestData9->rule_isClubbable		=	0;
		$requestData9->entity_price			= 	482;
		$requestData9->entity_total 		= 	874;
		
		$responseData9 = new PaycartDiscountRuleResponse();
		$responseData9->amount 				= 122.2352;
		$responseData9->stopFurtherRules 	= true;
		
		
		// Case-10 : previous discount rule applied but its non-clubbable
		// we will not check applicability here just return default values    
		$requestData10 = new PaycartDiscountRuleRequest();
		$requestData10->rule_isClubbable = false;
		$requestData10->entity_previousAppliedRules= Array($requestData9);
		
		$responseData10 = new PaycartDiscountRuleResponse();
		
		
		return Array(
						 Array($requestData1, $responseData1)
						,Array($requestData2, $responseData2)
						,Array($requestData3, $responseData3)
						,Array($requestData4, $responseData4)
						,Array($requestData5, $responseData5)
						,Array($requestData6, $responseData6)
						,Array($requestData7, $responseData7)
						,Array($requestData8, $responseData8)
						,Array($requestData9, $responseData9)
						,Array($requestData10, $responseData10, false)
					);
	}	
}
