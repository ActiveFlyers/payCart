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
	public $location = __DIR__;
	
	public function process(PaycartTaxruleRequest $request, PaycartTaxruleResponse $response)
	{
		static $verifiedVat = array();
		$result = true;
		$ownerCountry = isset($this->global_config->origin_address->country->isocode2)?$this->global_config->origin_address->country->isocode2:null;
		
		if($this->processor_config->associated_address == 'billing'){
			$buyerCountry = isset($request->billing_address->country->isocode2)?$request->billing_address->country->isocode2:null;
			$vatNumber	  = isset($request->billing_address->vat_number)?$request->billing_address->vat_number:null;
		}else{
			$buyerCountry = isset($request->shipping_address->country->isocode2)?$request->shipping_address->country->isocode2:null;
			$vatNumber	  = isset($request->shipping_address->vat_number)?$request->shipping_address->vat_number:null;
		}
		
		//Case 1 . If buyer and seller country is same then apply vat
		if($buyerCountry == $ownerCountry ){
			$result = true;
		}
		//Case 2 . If vat number is not empty and valid then 0% vat will be applicable
		else{
			$key = strtoupper($buyerCountry.$vatNumber);
			if($buyerCountry && $vatNumber && !isset($verifiedVat[$key])){
				$verifiedVat[$key] =  $this->isVatValid($buyerCountry, $vatNumber, $response);
			}
			
			if(!empty($vatNumber) && $verifiedVat[$key]){
				$result = false;
			}
			
			//Case 3 . If vat number is empty of not valid then vat according to seller country is applicable
		}
		
		//Case 4 . If buyer is a non-eu customer then no vat will be applicable
		// we recommend admin to make a group rule and attach it here
		
		if($result){
			return parent::process($request, $response);
		}

		return $response;
	}
	
	
	public function isVatValid($countryCode, $vatNumber, $response)
	{
		try{
			//validate vatnumber through official way
			$result = $this->_soapValidation($countryCode, $vatNumber);
			
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
}