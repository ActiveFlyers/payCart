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
	 * Test discount rule processor
	 * @param PaycartDiscountRuleRequest $requestData
	 * @param PaycartDiscountRuleResponse $expectedResponseData
	 * 
	 * @cover calculate
	 * @dataProvider provider_testProcess
	 */
	public function testProcess(PaycartDiscountRuleRequest $requestData, PaycartDiscountruleRequestRuleconfig $ruleConfig, PaycartDiscountRuleResponse $expectedResponseData) 
	{
		$responseData 	= new PaycartDiscountRuleResponse();
		
		// create stub mock
		$stubMock 				= new PaycartDiscountRuleProcessorStub();
		$stubMock->rule_config 	= $ruleConfig;
		$responseData = $stubMock->process($requestData, $responseData);
		
		// test instance
		$this->assertInstanceOf('PaycartDiscountRuleResponse',  $responseData, "Response must be instance of PaycartDiscountRuleResponse");
		
		// test instance property
		foreach ($expectedResponseData as $key=>$expectedValue) {
			// checking response property
			$this->assertSame($expectedValue, $responseData->$key,"Mismatch $key value");
		}
	}
	
	public function provider_testProcess() 
	{
		// Case-1 : without any kind of data   
		$requestData1	= new PaycartDiscountRuleRequest();
		$ruleConfig1 	= new PaycartDiscountruleRequestRuleconfig();
		$responseData1 	= new PaycartDiscountRuleResponse();
		
		$responseData1->message 	= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_NOT_ON_ZERO');
		$responseData1->messageType	= Paycart::MESSAGE_TYPE_ERROR;
		
		
		// Case-2 : without discount-amount   
		$requestData2	= new PaycartDiscountRuleRequest();
		$requestData2->discountable_amount = 100;		
		
		$ruleConfig2 	= new PaycartDiscountruleRequestRuleconfig();
		
		$responseData2 = new PaycartDiscountRuleResponse();		
		$responseData2->message 	= Rb_Text::_('COM_PAYCART_DISCOUNTRULE_IS_NOT_ZERO');
		$responseData2->messageType	= Paycart::MESSAGE_TYPE_ERROR;		
				
		// Case-3 : flat,amount    
		$requestData3	= new PaycartDiscountRuleRequest();
		$requestData3->discountable_amount 		= 	100;
		
		$ruleConfig3 	= new PaycartDiscountruleRequestRuleconfig();
		$ruleConfig3->amount 		= 12.29;
		$ruleConfig3->is_percentage = false;
		
		$responseData3 = new PaycartDiscountRuleResponse();
		$responseData3->amount = 12.29;		
		
		// Case-4 : percentage,amount    
		$requestData4	= new PaycartDiscountRuleRequest();
		$requestData4->discountable_amount 		= 	129;
		
		$ruleConfig4 	= new PaycartDiscountruleRequestRuleconfig();
		$ruleConfig4->amount 		= 14.53;
		$ruleConfig4->is_percentage = true;
		
		$responseData4 = new PaycartDiscountRuleResponse();
		$responseData4->amount = 18.7437;		
		
		
		return Array(
						 Array($requestData1, $ruleConfig1, $responseData1)
						,Array($requestData2, $ruleConfig2, $responseData2)
						,Array($requestData3, $ruleConfig3, $responseData3)
						,Array($requestData4, $ruleConfig4, $responseData4)
					);
	}	
}
