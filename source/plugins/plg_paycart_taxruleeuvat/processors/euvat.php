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
	public function process(PaycartTaxruleRequest $request, PaycartTaxruleResponse $response)
	{
		try{
			//PCTODO: Use joomla's soap and curl, use multiple ways to validate VAT
			//validate vatnumber from the offical site
			$client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
			$result = $client->checkVat(array(
											  'countryCode' => $request->buyerCountryCode,
											  'vatNumber'   => $request->buyerVatNumber
											 ));
		}
		catch (Exception $e){
			return $response->set('exception', $e);
		}
		
		// check for property 'valid'
		if($result->valid){
			return parent::process($request, $response);
		}
		
		//set error message to be displayed 
		$response->message     = Rb_Text::_('PLG_PAYCART_TAXRULE_EUVAT_VAT_NUMBER_IS_NOT_VALID');
		$response->messageType = PayCart::MESSAGE_TYPE_ERROR;							 
	}
	
}
