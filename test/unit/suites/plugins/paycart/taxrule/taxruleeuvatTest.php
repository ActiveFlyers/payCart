<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim
*/



$file = JPATH_PLUGINS . '/paycart/taxruleflat/processors/euvat.php';

if(file_exists($file)) {
	require_once $file;
	//stub for class PaycartTaxruleProcessorEuvat
	class PaycartTaxruleProcessorEuvatStub extends PaycartTaxruleProcessorEuvat{}
	function PaycartTaxruleProcessorEuvatTest(){};
}

// independently load request and response classes
require_once JPATH_ROOT.'/components/com_paycart/paycart/taxrule/processor.php';

/**
 * 
 * Test case euvat processor
 * @author rimjhim
 * @requires function PaycartTaxruleProcessorEuvatTest
 *
 */
class PaycartTaxruleProcessorEuvatTest extends PayCartTestCase
{
	/**
	 * testing process 
	 * @dataProvider providerValidateVat
	 */
	public function testValidateVat($countryCode, $vatNumber, $expectedResponse, $expectedResult, $functionName = array(), $return = false)
	{
		// create mock
        $stub     = new PaycartTaxruleProcessorEuvatStub();
        $response = new PaycartTaxruleResponse();
        
        // handle dependency if required
		  if(!empty($functionName)){
        	$stub = $this->getMock('PaycartTaxruleProcessorEuvatStub', $functionName, Array($countryCode, $vatNumber, $response));
        	
        	foreach ($functionName as $function){
		        $stub->expects($this->once())
		             ->method($function)
		             ->will($this->returnValue($return));
		  	}
        }
        
        //validate vat
        $valid = $stub->isVatValid($countryCode, $vatNumber, $response);
        
        //start comparing response
        $this->assertSame($expectedResult,$valid,"Validation doesn't match");
        
		foreach ($expectedResponse as $key => $value){
        	if($key == 'exception' && !empty($response->$key)){
        		$this->assertSame($response->$key->getMessage(), $value->getMessage(), "Exeception message doesn't match to the actual one");
        		continue;
        	}
        	$this->assertEquals($response->$key, $value, "Mismatch values");
        }
	} 
	
	/**
	 * Provider for testValidateVat
	 */
	public function providerValidateVat()
	{
		/**
		 * case 1 : Valid Vat number
		 * Validate with soap method
		 */
		$countryCode1  = 'ES';
		$vatNumber1    = 'B54185038';
		$response1	   = new PaycartTaxruleResponse();
		$valid1		   = true;
		
		/**
		 * case 2 : Invalid Vat number
		 * Validate with soap method
		 */
		$countryCode2  = 'ES';
		$vatNumber2    = 'B44165057';
		$response2	   = new PaycartTaxruleResponse();
		$response2->message     = Rb_Text::_('PLG_PAYCART_TAX_RULE_EUVAT_VAT_NUMBER_IS_NOT_VALID');
		$response2->messageType = PayCart::MESSAGE_TYPE_WARNING;
		$valid2		   = false;
		
		/**
		 * case 3 : Valid Vat number
		 * Validate with soap method
		 */
		$countryCode3  = 'GB';
		$vatNumber3    = '802311782';
		$response3	   = new PaycartTaxruleResponse();
		$valid3		   = true;
		
		/**
		 * case 4 : Valid Vat number
		 * When curl and soap are not installed
		 */
		$countryCode4  = 'DE';
		$vatNumber4    = '118646776';
		$response4	   = new PaycartTaxruleResponse();
		$response4->message     = Rb_Text::_('PLG_PAYCART_TAX_RULE_EUVAT_SOAP_AND_CURL_IS_NOT_AVAILABLE');
		$response4->messageType = PayCart::MESSAGE_TYPE_ERROR;
		$functionName4 = array('_curlValidation','_soapValidation');
		$return4	   = false;
		$valid4		   = false;
		
		/**
		 * case 5 : Valid Vat number
		 * Validate with curl method
		 */
		$countryCode5  = 'ES';
		$vatNumber5    = 'B54185038';
		$response5	   = new PaycartTaxruleResponse();
		$functionName5 = array('_soapValidation');
		$return5	   = false;
		$valid5		   = true;
		
		/**
		 * case 6 : Invalid Vat number
		 * Validate with curl method
		 */
		$countryCode6  = 'ES';
		$vatNumber6    = 'G44165006';
		$response6	   = new PaycartTaxruleResponse();
		$response6->message     = Rb_Text::_('PLG_PAYCART_TAX_RULE_EUVAT_VAT_NUMBER_IS_NOT_VALID');
		$response6->messageType = PayCart::MESSAGE_TYPE_WARNING;
		$functionName6 = array('_soapValidation');
		$return6	   = false;
		$valid6		   = false;
		
		/**
		 * case 7 : Valid Vat number
		 * Validate with curl method
		 */
		$countryCode7  = 'DE';
		$vatNumber7    = '118646776';
		$response7	   = new PaycartTaxruleResponse();
		$functionName7 = array('_soapValidation');
		$return7	   = false;
		$valid7		   = true;

		return array(
		    			array($countryCode1,$vatNumber1,$response1,$valid1),
		    			array($countryCode2,$vatNumber2,$response2,$valid2),
		    			array($countryCode3,$vatNumber3,$response3,$valid3),
		    			array($countryCode4,$vatNumber4,$response4,$valid4,$functionName4,$return4),
		    			array($countryCode5,$vatNumber5,$response5,$valid5,$functionName5,$return5),
		    			array($countryCode6,$vatNumber6,$response6,$valid6,$functionName6,$return6),
		    			array($countryCode7,$vatNumber7,$response7,$valid7,$functionName7,$return7)
					);
	}
	
	
}
