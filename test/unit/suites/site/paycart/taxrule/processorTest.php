<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim
*/

/**
 * 
 * Test case for base processor of taxrule
 * @author rimjhim
 *
 */

class PaycartTaxruleProcessorStub extends PaycartTaxruleProcessor{}



/**
 * 
 * Test case for base processor of taxrule
 * @author rimjhim
 *
 */

class PaycartTaxruleProcessorTest extends PayCartTestCase
{
	/**
	 * testing config html
	 */
	public function testGetConfigHtml()
	{
		$stub     = new PaycartTaxruleProcessorStub();
		
		$response = $stub->getConfigHtml(new PaycartTaxruleRequest(), new PaycartTaxruleResponse());
		
		$this->assertInstanceOf('PaycartTaxruleResponse', $response, 'Response is not an object of PaycartTaxruleResponse');
		$this->assertSame('',$response->configHtml, "Config html not equal");
	}
	
	/**
	 * testing process 
	 * @dataProvider providerProcess
	 */
	public function testProcess($taxRequest, $ruleConfig, $taxResponse, $isApplicable = true)
	{
		// create mock
		$mock      = new PaycartTaxruleProcessorStub();
		$response  = new PaycartTaxRuleResponse();
        
        // handle dependency if required
        if(!$isApplicable){
	        $mock = $this->getMock('PaycartTaxruleProcessorStub', Array('isApplicable'));
	        $mock->expects($this->once())
	             ->method('isApplicable')
	             ->will($this->returnValue($isApplicable));
        }

        $mock->rule_config = $ruleConfig;
        
        // process tax
        $response = $mock->process($taxRequest, $response);
        
        // compare response
        foreach ($taxResponse as $key => $value){
        	if($key == 'exception' && !empty($taxResponse->$key)){
        		$this->assertInstanceOf('InvalidArgumentException',$response->$key, "Exception object doesn't match");
        		$this->assertSame($response->$key->getMessage(), $value->getMessage(), "Exeception message doesn't match to the actual one");
        		continue;
        	}
        	$this->assertEquals($response->$key, $value, "Mismatch values");
        }
        
	}
	
	/**
	 * Provider for testProcess
	 */
	public function providerProcess()
	{
		/**
		 * Case1 : Nothing set in request
		 */
		$request1  		= new PaycartTaxruleRequest();
		$ruleConfig1 	= new PaycartTaxruleRequestRuleconfig(); 
		$response1 = new PaycartTaxruleResponse();
		$response1->exception = new InvalidArgumentException(Rb_Text::_('COM_PAYCART_TAXRULE_CANT_BE_PROCESSED_ON_ZERO'));
		
		/**
		 * case2 : Taxrate is zero
		 */
		$request2  = new PaycartTaxruleRequest();
		$request2->taxable_amount = 120;
		
		$ruleConfig2 	= new PaycartTaxruleRequestRuleconfig();
		
		$response2 = new PaycartTaxruleResponse();
		$response2->exception = new InvalidArgumentException(Rb_Text::_('COM_PAYCART_TAXRULE_RATE_CANT_BE_ZERO'));
		
		/**
		 * case3 : Everything ok
		 */
		$request3  = new PaycartTaxruleRequest();
		$request3->taxable_amount  = 120;
		
		$ruleConfig3 	= new PaycartTaxruleRequestRuleconfig();
		$ruleConfig3->tax_rate		= 10;
		
		$response3 = new PaycartTaxruleResponse();
		$response3->amount 	= 12;
		
		/**
		 * Case4 : When taxrule is not applicable
		 */
		$request4  = new PaycartTaxruleRequest();
		$response4 = new PaycartTaxruleResponse();
		$ruleConfig4 = new PaycartTaxruleRequestRuleconfig();
		
		return array(
						array($request1, $ruleConfig1, $response1),
						array($request2, $ruleConfig2, $response2),
						array($request3, $ruleConfig3, $response3),
						array($request4, $ruleConfig4, $response4, false)
					);
	}
	
	
	
}