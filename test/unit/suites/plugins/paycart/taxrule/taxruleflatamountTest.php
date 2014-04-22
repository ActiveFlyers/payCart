<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim
*/

$file = JPATH_PLUGINS . '/paycart/taxruleflat/processors/flatamount/flatamount.php';

if(file_exists($file)) {
	require_once $file;
	class PaycartTaxruleProcessorFlatAmountStub extends PaycartTaxruleProcessorFlatAmount{}

	function PaycartTaxruleProcessorFlatAmountStub(){};
	
}

// independently load request and response classes
require_once JPATH_ROOT.'/components/com_paycart/paycart/taxrule/processor.php';



/**
 * 
 * stub for class PaycartTaxruleProcessorEuvat
 * @author rimjhim
 * @requires function PaycartTaxruleProcessorFlatAmountStub
 *
 */

class PaycartTaxruleProcessorFlatAmountTest extends PayCartTestCase
{
	/**
	 * testing process 
	 * @dataProvider providerProcess
	 */
	public function testProcess($taxRequest, $ruleConfig, $taxResponse, $isApplicable = true)
	{
		// create mock
		$mock      = new PaycartTaxruleProcessorFlatAmountStub();
		$response  = new PaycartTaxRuleResponse();
        
        // handle dependency if required
        if(!$isApplicable){
	        $mock = $this->getMock('PaycartTaxruleProcessorFlatAmountStub', Array('isApplicable'));
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
		 * Case1 : Nothing set in request and Taxrate is zero
		 */
		$request1  = new PaycartTaxruleRequest();
		$ruleConfig1 = new PaycartTaxruleRequestRuleconfig();
		$response1 = new PaycartTaxruleResponse();		
		$response1->exception = new InvalidArgumentException(Rb_Text::_('COM_PAYCART_TAXRULE_RATE_CANT_BE_ZERO'));
		
		/**
		 * case2 : Everything ok
		 */
		$request2  	= new PaycartTaxruleRequest();
		$request2->cartparticular = new PaycartRequestCartparticular();
		
		$ruleConfig2= new PaycartTaxruleRequestRuleconfig();
		$ruleConfig2->tax_rate	= 10;
		
		$response2 = new PaycartTaxruleResponse();
		$response2->amount 	= 10;
		
		/**
		 * case3 : Everything ok and quatity is set
		 */
		$request3  = new PaycartTaxruleRequest();
		$request3->cartparticular = new PaycartRequestCartparticular();		
		$request3->cartparticular->quantity = 5;
		
		$ruleConfig3= new PaycartTaxruleRequestRuleconfig();
		$ruleConfig3->tax_rate	= 5;
		
		$response3 = new PaycartTaxruleResponse();
		$response3->amount 	   = 25;
		
		/**
		 * Case4 : When taxrule is not applicable
		 */
		$request4  = new PaycartTaxruleRequest();
		$ruleConfig4= new PaycartTaxruleRequestRuleconfig();
		$response4 = new PaycartTaxruleResponse();		
		
		return array(
						array($request1, $ruleConfig1, $response1),
						array($request2, $ruleConfig2, $response2),
						array($request3, $ruleConfig3, $response3),
						array($request4, $ruleConfig4, $response4, false)
					);
	}
}
