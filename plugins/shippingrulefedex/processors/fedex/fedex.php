<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartShippngrule.Processor
* @subpackage       FlatRate
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Fedex Processor
 * 
 * @author Gaurav Jain
 */
class PaycartShippingruleProcessorFedex extends PaycartShippingruleProcessor
{	
	public $location			= __DIR__;
	public $trackingUrl 		= 'https://www.fedex.com/apps/fedextrack/?action=track&tracknumbers=';
	
	public function getPackageShippingCost(PaycartShippingruleRequest $request, PaycartShippingruleResponse $response)
	{
		
		$req_params = array(			
			'recipient_postalcode' => $request->delivery_address->zipcode,			
			'shipper_postalcode'   => isset($this->global_config->origin_address->zipcode)?$this->global_config->origin_address->zipcode:0,
		);
		
		if(empty($this->processor_config->calculation_mode) || $this->processor_config->calculation_mode == 'ONEPACKAGE'){
			$req_params = $this->_getOnepackageRequestParams($request, $req_params);
		}else{
			$req_params = $this->_getPeritemRequestParams($request, $req_params);
		}
		
		$result = $this->_getFedexShippingCost($request, $req_params);
	
		// set error resosnse if there is error 
		if(!isset($result['rate']) ||  $result['connect'] === false){
			$response->amount = false;
			$response->messageType = Paycart::MESSAGE_TYPE_NOTICE;
//			$response->message = $result['error'];
			return $response;
		}
		
		$response->amount = $result['rate'] + $this->rule_config->handling_charge;		
		return $response;
	}
	
	protected function _getOnepackageRequestParams(PaycartShippingruleRequest $request, $req_params)
	{
		$cost = 0;
		$width = 0;
		$height = 0;
		$length = 0;
		$weight = 0;

		foreach ($request->cartparticulars as $product){
			/* @var $product PaycartRequestParticular */
			if ($product->width && $product->width > $width){ 
				$width = $product->width;
			}
			
			if ($product->height && $product->height > $height){ 
				$height = $product->height;
			}
			
			if ($product->length && $product->length > $length){ 
				$length = $product->length;
			}
				
			if ($product->weight){
				$weight += ($product->weight * $product->quantity);
			}
		}
		
		$weight += $this->rule_config->packaging_weight;
		
		$req_params['service'] = isset($this->processor_config->service_code) ? $this->processor_config->service_code : '';
		$req_params['quantity'] = 1;
		$req_params['package_list'] = array();
		$req_params['package_list'][] = array(
			'width' => ($width > 0 ? $width : 7),
			'height' => ($height > 0 ? $height : 3),
			'length' => ($length > 0 ? $length : 5),
			'weight' => ($weight > 0 ? $weight : 0.5),
//			'quantity' => 1, 
		);
		
		return $req_params;		
	}
	
	protected function _getPeritemRequestParams(PaycartShippingruleRequest $request, $req_params)
	{
		// Load param product
		$req_params['service'] = isset($this->processor_config->service_code) ? $this->processor_config->service_code : '';
		
		$cost = 0;
		// Getting shipping cost for each product
		foreach ($request->cartparticulars as $product){			
			for ($qty = 0; $qty < $product->quantity; $qty++){
				$req_params['package_list'][] = array(
					'width' => ($product->width 	? $product->width : 1),
					'height' => ($product->height 	? $product->height : 1),
					'length' => ($product->length 	? $product->length : 1),
					'weight' => ($product->weight > 0 ? $product->weight : 0.1),
//					'quantity' => $product->quantity, 
				);
			}
		}

		return $req_params;
	}
	
	public function _getFedexShippingCost($requestObject, $wsParams)
	{
		static $tmpMD5 = null;
		static $cachedResult = null;
		
		if(md5(json_encode($wsParams)) === $tmpMD5){
			return $cachedResult;
		}else{
			$tmpMD5 = md5(json_encode($wsParams));
		}
		
		// Check config
		if (!$this->processor_config->key)
			return array('rate' => false, Rb_Text::_('COM_PAYCART_SHIPPINGRULE_FEDEX_CONNECTION_ERROR_EMPTY_KEY'));

		// Check if class Soap is available
		if (!extension_loaded('soap'))
			return array('rate' => false, Rb_Text::_('COM_PAYCART_SHIPPINGRULE_FEDEX_CONNECTION_ERROR_SOAP_NOT_LOADED'));

		// Getting module directory
		$file = dirname(__FILE__).'/RateService_v10.wsdl';
		if($this->processor_config->testmode){
			$file = dirname(__FILE__).'/TestRateService_v10.wsdl';	
		}		

		// Enable Php Soap
		ini_set("soap.wsdl_cache_enabled", "0");
		$client = new SoapClient($file, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

		// Generating soap request
		$request['WebAuthenticationDetail'] = array(
			'UserCredential' => array(
				'Key' => $this->processor_config->key, 
				'Password' => $this->processor_config->password
			)
		); 
		
		$request['ClientDetail'] = array(
			'AccountNumber' => $this->processor_config->account_number, 
			'MeterNumber' => $this->processor_config->meter_number
		);
		
		$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request using PHP ***');
		$request['Version'] = array(
			'ServiceId' => 'crs', 
			'Major' => '10', 
			'Intermediate' => '0', 
			'Minor' => '0'
		);		
																
		$request['ReturnTransitAndCommit'] = true;
		
		$request['RequestedShipment']['Shipper'] 	   = $this->_addShipperAddress();
		$request['RequestedShipment']['Recipient'] 	   = $this->_addRecipientAddress($requestObject->delivery_address);
		$request['RequestedShipment']['ShippingChargesPayment'] = $this->_addShippingChargesPaymentData();
		$request['RequestedShipment']['DropoffType']   = $this->processor_config->pickup_type; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['ShipTimestamp'] = date('c');
		$request['RequestedShipment']['ServiceType']   = $wsParams['service']; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
		$request['RequestedShipment']['PackagingType'] = $this->processor_config->packaging_type; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...

		// Service Type and Packaging Type are not passed in the request
		$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
		$request['RequestedShipment']['PackageCount'] = '1';
		$count = 1;
		foreach ($wsParams['package_list'] as $p)
		{			
			$p['weight'] = $this->convertWeight($p['weight'],$this->global_config->weight_unit,Paycart::WEIGHT_UNIT_PONUD);
			$p['length'] = $this->convertDimension($p['length'],$this->global_config->dimension_unit,Paycart::DIMENSION_UNIT_INCH);
			$p['height'] = $this->convertDimension($p['height'],$this->global_config->dimension_unit,Paycart::DIMENSION_UNIT_INCH);
			$p['width']  = $this->convertDimension($p['width'], $this->global_config->dimension_unit,Paycart::DIMENSION_UNIT_INCH);
			
			$request['RequestedShipment']['RequestedPackageLineItems'][] = array(
				'SequenceNumber' => $count,
				'GroupPackageCount' => 1, //We can use the quantity of a single product here 
				'Weight' => array('Value' => $p['weight'], 'Units' => JString::strtoupper(Paycart::WEIGHT_UNIT_PONUD)),
				'Dimensions' => array('Length' => $p['length'], 'Width' => $p['width'], 'Height' => $p['height'], 'Units' => JString::strtoupper(Paycart::DIMENSION_UNIT_INCH)));
			$count++;
		}
		$request['RequestedShipment']['PackageCount'] = count($request['RequestedShipment']['RequestedPackageLineItems']);
		$request['RequestedShipment']['PreferredCurrency'] = $requestObject->currency;
		
		
		// Get Rates
		try { $resultTab = $client->getRates($request); }
		catch (Exception $e) { 
			//dump the error code and error message in log file of paycart
			PaycartFactory::getHelper('log')->add($e->getMessage());
			
			return array('connect' => false, 'rate' => 0);			
		}

		// Return results
		if (isset($resultTab->HighestSeverity) && $resultTab->HighestSeverity != 'ERROR' && isset($resultTab->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount)){
			$cachedResult = array('connect' => true, 'rate' => $resultTab->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount);
			return $cachedResult;
		}

		 
		if($resultTab->Notifications->Message){
			//dump the error code and error message in log file of paycart
			if(!is_array($resultTab->Notifications)){
				PaycartFactory::getHelper('log')->add('Fedex Error :'.$resultTab->Notifications->Code.'-'.$resultTab->Notifications->Message);
			}else{
				foreach ($resultTab->Notifications as $key => $detail){
					PaycartFactory::getHelper('log')->add('Fedex Error :'.$detail->Code.'-'.$detail->Message);	
				}
			}
		}
		
		return array('connect' => false, 'rate' => 0);
	}

	protected function _addShipperAddress(){
		$global      = $this->global_config->origin_address;
		$address     = !empty($this->processor_config->address)?$this->processor_config->address:$global->address;
		$zipcode     = !empty($this->processor_config->zipcode)?$this->processor_config->zipcode:$global->zipcode;
		$city        = !empty($this->processor_config->city)?$this->processor_config->city:$global->city;		
		$countryCode = !empty($this->processor_config->country_id)?$this->processor_config->country_id:$global->country->isocode2;
		
		$shipper = array(
			'Address' => array(
				'StreetLines' => array($address),
				'City' => $city,
				'StateOrProvinceCode' => '',
				'PostalCode' => $zipcode,
				'CountryCode' => $countryCode
			)
		);
		return $shipper;
	}
	
	protected function _addRecipientAddress($recipient){	
		$recipientAddress = array(
			'Address' => array(
				'StreetLines' => array($recipient->address),
				'City' => $recipient->city,
				'StateOrProvinceCode' => '',
				'PostalCode' => $recipient->zipcode,
				'CountryCode' => isset($recipient->country->isocode2)?$recipient->country->isocode2:'',
				'Residential' => false
			)
		);
		return $recipientAddress;	                                    
	}
	
	protected function _addShippingChargesPaymentData(){
		$shippingChargesPayment = array(
			'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
			'Payor' => array(
					'AccountNumber' => $this->processor_config->account_number,
					'CountryCode'   => !empty($this->processor_config->country_id)?$this->processor_config->country_id:$this->global_config->origin_address->country->isocode2
				)
			);
		return $shippingChargesPayment;
	}
	
	protected function _getPackagingTypes()
	{
		return array(
						  0 => array('title' => JText::_('PLG_PAYCART_SELECT_OPTION'), 'value' => ''),
						  1 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_10KG_BOX'), 'value' => 'FEDEX_10KG_BOX'),
						  2 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_25KG_BOX'), 'value' => 'FEDEX_25KG_BOX'),
						  3 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_BOX'), 'value' => 'FEDEX_BOX'),
						  4 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_ENVELOPE'), 'value' => 'FEDEX_ENVELOPE'),
						  5 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_PAK'), 'value' => 'FEDEX_PAK'),
						  6 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_TUBE'), 'value' => 'FEDEX_TUBE'),
						  7 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_YOUR_PACKAGING'), 'value' => 'YOUR_PACKAGING')
					);
	}
	
	protected function _getServices()
	{
		return array( 						  
						  0 => array('title' => JText::_('PLG_PAYCART_SELECT_OPTION'), 'value' => ''),
						  1 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_EUROPE_FIRST_INTERNATIONAL_PRIORITY'), 	'value' => 'EUROPE_FIRST_INTERNATIONAL_PRIORITY'),
						  2 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_FEDEX_1_DAY_FREIGHT'), 					'value' => 'FEDEX_1_DAY_FREIGHT'),
						  3 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_FEDEX_2_DAY'), 							'value' => 'FEDEX_2_DAY'),
						  4 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_FEDEX_2_DAY_FREIGHT'), 					'value' => 'FEDEX_2_DAY_FREIGHT'),
						  5 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_FEDEX_3_DAY_FREIGHT'), 					'value' => 'FEDEX_3_DAY_FREIGHT'),
						  6 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_FEDEX_EXPRESS_SAVER'), 					'value' => 'FEDEX_EXPRESS_SAVER'),
						  7 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_FEDEX_FREIGHT'), 							'value' => 'FEDEX_FREIGHT'),
						  8 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_FEDEX_GROUND'), 							'value' => 'FEDEX_GROUND'),
						  9 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_FEDEX_NATIONAL_FREIGHT'), 				'value' => 'FEDEX_NATIONAL_FREIGHT'),
						  10 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_FIRST_OVERNIGHT'), 						'value' => 'FIRST_OVERNIGHT'),
						  11 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_GROUND_HOME_DELIVERY'), 					'value' => 'GROUND_HOME_DELIVERY'),
						  12 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_INTERNATIONAL_ECONOMY'), 				'value' => 'INTERNATIONAL_ECONOMY'),
						  13 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_INTERNATIONAL_ECONOMY_FREIGHT'),			'value' => 'INTERNATIONAL_ECONOMY_FREIGHT'),
						  14 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_INTERNATIONAL_FIRST'), 					'value' => 'INTERNATIONAL_FIRST'),
						  15 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_INTERNATIONAL_GROUND'), 					'value' => 'INTERNATIONAL_GROUND'),
						  16 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_INTERNATIONAL_PRIORITY'), 				'value' => 'INTERNATIONAL_PRIORITY'),
						  17 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_INTERNATIONAL_PRIORITY_FREIGHT'), 		'value' => 'INTERNATIONAL_PRIORITY_FREIGHT'),
						  18 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_PRIORITY_OVERNIGHT'), 					'value' => 'PRIORITY_OVERNIGHT'),
						  19 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_SMART_POST'), 							'value' => 'SMART_POST'),
						  20 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE_STANDARD_OVERNIGHT'), 					'value' => 'STANDARD_OVERNIGHT')						  
					);
	}
	
	protected function _getCalculationMode()
	{
		return array( 
		  				  0 => array('title' => JText::_('PLG_PAYCART_SELECT_OPTION'), 'value' => ''),
						  1 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_CACLULATION_MODE_ONEPACKAGE'), 'value' => 'ONEPACKAGE'),
						  2 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_CACLULATION_MODE_PERITEM'),'value' => 'PERITEM')
					);
	}
	
	protected function _getPickupTypes()
	{
		return array(
						  0 => array('title' => JText::_('PLG_PAYCART_SELECT_OPTION'), 'value' => ''),
 						  1 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_PICKUP_TYPE_BUSINESS_SERVICE_CENTER'), 'value' => 'BUSINESS_SERVICE_CENTER'),
						  2 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_PICKUP_TYPE_DROP_BOX'),'value' => 'DROP_BOX'),
						  3 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_PICKUP_TYPE_REGULAR_PICKUP'),'value' => 'REGULAR_PICKUP'),
						  4 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_PICKUP_TYPE_REQUEST_COURIER'),'value' => 'REQUEST_COURIER'),
						  5 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_PICKUP_TYPE_STATION'),'value' => 'STATION')
				);		
	}
	
	public function getConfigHtml(PaycartShippingruleRequest $request, PaycartShippingruleResponse $response, $namePrefix)
	{
		$services     	 = $this->_getServices();
		$calculationMode = $this->_getCalculationMode();
		$pickpTypes		 = $this->_getPickupTypes();		
		$packagingType	 = $this->_getPackagingTypes();
		
		$config 		 = $this->getConfig();
		$location		 = $this->getLocation();
		
		ob_start();
		
		include_once $location.'/tmpl/config.php';
		
		$content = ob_get_contents();
		ob_end_clean();
		
		$response->configHtml = $content;
		return true;
	}
}