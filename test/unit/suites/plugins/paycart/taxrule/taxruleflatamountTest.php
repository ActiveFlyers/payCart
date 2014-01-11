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
 * stub for class PaycartTaxruleProcessorEuvat
 * @author rimjhim
 *
 */
require_once JPATH_PLUGINS . '/paycart/taxruleflat/processors/flatamount/flatamount.php';

class PaycartTaxruleProcessorFlatAmountStub extends PaycartTaxruleProcessorFlatAmount{}


/**
 * 
 * Test case for flat amount processor
 * @author rimjhim
 *
 */

class PaycartTaxruleProcessorFlatAmountTest extends PayCartTestCase
{
	/**
	 * testing process 
	 * @dataProvider providerProcess
	 */
	public function testProcess($taxRequest, $taxResponse, $isApplicable = true)
	{
		// create mock
		$mock      = new PaycartTaxruleProcessorFlatAmountStub();
		$response  = new PaycartTaxRuleResponse();
        
        // handle dependency if required
        if(!$isApplicable){
	        $mock = $this->getMock('PaycartTaxruleProcessorFlatAmountStub', Array('isApplicable'), Array($taxRequest, $response));
	        $mock->expects($this->once())
	             ->method('isApplicable')
	             ->will($this->returnValue($isApplicable));
        }

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
		$response1 = new PaycartTaxruleResponse();
		$response1->exception = new InvalidArgumentException(Rb_Text::_('COM_PAYCART_TAXRULE_RATE_CANT_BE_ZERO'));
		
		/**
		 * case2 : Everything ok
		 */
		$request2  = new PaycartTaxruleRequest();
		$request2->taxRate		= 10;
		
		$response2 = new PaycartTaxruleResponse();
		$response2->taxAmount 	= 10;
		
		/**
		 * case3 : Everything ok and quatity is set
		 */
		$request3  = new PaycartTaxruleRequest();
		$request3->taxRate	       = 5;
		$request3->productQuantity = 5;
		
		$response3 = new PaycartTaxruleResponse();
		$response3->taxAmount 	   = 25;
		
		/**
		 * Case4 : When taxrule is not applicable
		 */
		$request4  = new PaycartTaxruleRequest();
		$response4 = new PaycartTaxruleResponse();
		
		return array(
						array($request1, $response1),
						array($request2, $response2),
						array($request3, $response3),
						array($request4, $response4, false)
					);
	}
}
