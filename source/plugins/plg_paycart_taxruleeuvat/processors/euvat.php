<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		Joomla.Plugin
* @subpackage	Paycart
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * euvat Processor 
 * @author rimjhim
 */
class PaycartTaxruleProcessorEuvat extends PaycartTaxruleProcessor
{
	const EUVAT_SOAP = "http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl";
	const EUVAT_CURL = "http://vatid.eu/check/";
	
	public function process(PaycartTaxruleRequest $request, PaycartTaxruleResponse $response)
	{
		$result = $this->isVatValid($request->buyerCountryCode, $request->buyerVatNumber, $response);
		
		if($result){
			return parent::process($request, $response);
		}

		return $response;
	}
	
	
	public function isVatValid($countryCode, $vatNumber, $response)
	{
		try{
			//validate vatnumber through official way
			if(!$result = $this->_soapValidation($countryCode, $vatNumber)) {
				//validate vatnumber through some third party solution
				$result = $this->_curlValidation($countryCode, $vatNumber);		
			}
			
			//Set error if both extensions was not loaded 
			if(!$result ){
				$response->message     = Rb_Text::_('PLG_PAYCART_TAX_RULE_EUVAT_SOAP_AND_CURL_IS_NOT_AVAILABLE');
				$response->messageType = PayCart::MESSAGE_TYPE_ERROR;
				return false;
			}
			
			// check property 'valid' which will available in response
			if(!isset($result->valid) || !$result->valid){
				$response->message     = Rb_Text::_('PLG_PAYCART_TAX_RULE_EUVAT_VAT_NUMBER_IS_NOT_VALID');
				$response->messageType = PayCart::MESSAGE_TYPE_WARNING;
				return false;		
			}
		}
		catch(Exception $e){
			$response->exception = $e;	
			return false;
		}
		
		return true;
	}
	
	/**
	 * Soap method to validate vat number
	 * It return an object of stdclass have properties :
	 *  class stdClass (6) {
	 *			  public $countryCode => string(2) "COUNTRY_CODE"
	 *			  public $vatNumber   => string(9) "VAT_NUMBER"
	 *			  public $requestDate => string(16) "DATE REQUESTED"
	 *			  public $valid       => bool(true/false)
	 *			  public $name        => string(3) "---"
	 *			  public $address     => string(3) "---"
	 *			} 
	 * @param string $countryCode
	 * @param string $vatNumber
	 */
	protected function _soapValidation($countryCode,$vatNumber)
	{
		// PCTODO: Remove it and move it on precheck list
		if(extension_loaded('soap')){
			$client   = new SoapClient(self::EUVAT_SOAP);
			return $client->checkVat(array(
											  'countryCode' => $countryCode,
											  'vatNumber'   => $vatNumber
											 ));
		}
		return false;
	}
	
	/**
	 * Curl method to validate vat number 
	 * It returns json like :
	 *      {
	 *		  "response": {
	 *		    "country_code": "COUNTRY_CODE",
	 *		    "vat_number": "VAT_NUMBER",
	 *		    "valid": "false/true",
	 *		    "name": "---",
	 *		    "address": "---"
	 *		  }
	 *	   }
	 * @param string $countryCode
	 * @param string $vatNumber
	 */
	protected function _curlValidation($countryCode,$vatNumber)
	{
		// PCTODO: Remove it and move it on precheck list
		if(extension_loaded('curl')){
			$curl   = new JHttpTransportCurl(new Rb_Registry());
			$result = $curl->request('GET', new JURI(self::EUVAT_CURL.$countryCode.'/'.$vatNumber), null, array('Accept'=>'application/json'),30);
			$output = json_decode($result->body)->response;
			
			//true and false is in string, so convert it to boolean
			$output->valid = ($output->valid === 'true')? true: false;
			
			return $output;
		}
		return false;
	}
}
