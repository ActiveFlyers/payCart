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
 * USPS Processor
 * 
 * Below are the list of parameters, which are part of this processor
 *
 * user_id
 * 
 * calculation_mode
 * 		ONEPACKAGE 	: one package
 * 		PERITEM		: per item
 * 
 * service_code
 * 		FIRST CLASS
 * 		FIRST CLASS COMMERCIAL
 * 		PRIORITY
 * 		PRIORITY COMMERCIAL
 * 		EXPRESS
 * 		EXPRESS COMMERCIAL
 * 		PARCEL
 * 		MEDIA
 * 		LIBRARY
 * 
 * packaging_type
 * 		VARIABLE
 * 		FLAT RATE ENVELOPE
 * 		PADDED FLAT RATE ENVELOPE
 * 		LEGAL FLAT RATE ENVELOPE
 * 		SM FLAT RATE ENVELOPE
 * 		WINDOW FLAT RATE ENVELOPE
 * 		GIFT CARD FLAT RATE ENVELOPE
 * 		FLAT RATE BOX
 * 		SM FLAT RATE BOX
 * 		MD FLAT RATE BOX
 * 		LG FLAT RATE BOX
 * 		REGIONALRATEBOXA
 * 		REGIONALRATEBOXB
 * 		REGIONALRATEBOXC
 * 		RECTANGULAR
 * 		NONRECTANGULAR
 * 			NOTE : We should not consider ENVELOPE size, at first stage
 * 
 * packaging_size
 * 		LARGE
 * 		REGULAR
 * 
 * machinable
 * 		true
 * 		false
 * 
 * @author Gaurav Jain
 */
class PaycartShippingruleProcessorUsps extends PaycartShippingruleProcessor
{								
	private function _getCalculationMode()
	{
		return array(
				'ONEPACKAGE' => Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_CACLULATION_MODE_ONEPACKAGE'),
				'PERITEM' 	 => Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_CACLULATION_MODE_PERITEM')
		);
	}

	private function _PackagingType()
	{
		return array(
				'VARIABLE'						=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_VARIABLE'),
				'FLAT RATE ENVELOPE' 			=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_FLAT_RATE_ENVELOPE'),
 				'PADDED FLAT RATE ENVELOPE'		=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_PADDED_FLAT_RATE_ENVELOPE'),
 				'LEGAL FLAT RATE ENVELOPE'		=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_LEGAL_FLAT_RATE_ENVELOPE'),
				'SM FLAT RATE ENVELOPE'			=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_SM_FLAT_RATE_ENVELOPE'),
				'WINDOW FLAT RATE ENVELOPE'		=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_WINDOW_FLAT_RATE_ENVELOPE'),
				'GIFT CARD FLAT RATE ENVELOPE'	=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_GIFT_CARD_FLAT_RATE_ENVELOPE'),
				'FLAT RATE BOX'					=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_FLAT_RATE_BOX'),
				'SM FLAT RATE BOX'				=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_SM_FLAT_RATE_BOX'),
				'MD FLAT RATE BOX'				=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_MD_FLAT_RATE_BOX'),
				'LG FLAT RATE BOX'				=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_LG_FLAT_RATE_BOX'),
				'REGIONALRATEBOXA'				=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_REGIONALRATEBOXA'),
				'REGIONALRATEBOXB'				=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_REGIONALRATEBOXB'),
				'REGIONALRATEBOXC'				=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_REGIONALRATEBOXC'),
				'RECTANGULAR'					=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_RECTANGULAR'),
				'NONRECTANGULAR'				=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_TYPE_NONRECTANGULAR')
		);
	}
	
	private function _getPackagingSize()
	{
		return array(
				'REGULAR' 	=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_SIZE_REGULAR'), 
				'LARGE' 	=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_PACKAGING_SIZE_LARGE')
		);
	}
	
	private function _getServiceCode()
	{
		return array(
			'FIRST CLASS'				=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_SERVICE_CODE_FIRST_CLASS'), 
 			'FIRST CLASS COMMERCIAL'	=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_SERVICE_CODE_FIRST_CLASS_COMMERCIAL'),
			'PRIORITY'					=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_SERVICE_CODE_PRIORITY'),
			'PRIORITY COMMERCIAL'		=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_SERVICE_CODE_PRIORITY_COMMERCIAL'),
			'EXPRESS'					=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_SERVICE_CODE_EXPRESS'),
			'EXPRESS COMMERCIAL'		=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_SERVICE_CODE_EXPRESS_COMMERCIAL'),
			'PARCEL'					=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_SERVICE_CODE_PARCEL'),
			'MEDIA'						=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_SERVICE_CODE_MEDIA'),
			'LIBRARY'					=> Rb_Text::_('COM_PAYCART_PROCESSOR_USPS_SERVICE_CODE_LIBRARY')
		);
	}
	
	public function getPackageShippingCost(PaycartShippingruleRequest $request, PaycartShippingruleResponse $response)
	{
		$req_params = array(			
			'recipient_postalcode' => $request->delivery_address->zipcode,			
			'shipper_postalcode' => $request->origin_address->zipcode
		);
		
		if(empty($this->processor_config->calculation_mode) || $this->processor_config->calculation_mode == 'ONEPACKAGE'){
			$req_params = $this->_getOnepackageRequestParams($request, $req_params);
		}else{
			$req_params = $this->_getPeritemRequestParams($request, $req_params);
		}
		
		$result = $this->_getUspsShippingCost($req_params);
	
		// set error resosnse if there is error 
		if(!isset($result['rate']) ||  $result['rate'] === false){
			$response->amount = false;
			$response->messageType = Paycart::MESSAGE_TYPE_NOTICE;
			$response->message = $result['error'];
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

		foreach ($request->products as $product){
			/* @var $product PaycartRequestParticular */
			if ($product->width && $product->width > $width){ 
				$width = $product->width;
			}
			
			if ($product->height && $product->height > $height){ 
				$height = $product->height;
			}
			
			if ($product->length && $product->length > $length){ 
				$height = $product->length;
			}
				
			if ($product->weight){
				$weight += ($product->weight * $product->quantity);
			}
		}
		
		$weight += $this->rule_config->packaging_weight;
		
		$req_params['service'] = isset($this->processor_config->service_code) ? $this->processor_config->service_code : '';
		$req_params['package_list'] = array();
		$req_params['package_list'][] = array(
			'width' => ($width > 0 ? $width : 1),
			'height' => ($height > 0 ? $height : 1),
			'length' => ($length > 0 ? $length : 1),
			'weight' => ($weight > 0 ? $weight : 0.5), // ????
			'packaging_type' => !empty($this->processor_config->packaging_type) ? $this->processor_config->packaging_type : 'VARIABLE',
			'packaging_size' =>  !empty($this->processor_config->packaging_size)? $this->processor_config->packaging_size : 'REGULAR',
			'machinable' => isset($this->processor_config->machinable) ? $this->processor_config->machinable :  false,
		);
		
		return $req_params;		
	}
	
	protected function _getPeritemRequestParams(PaycartShippingruleRequest $request, $req_params)
	{
		// Load param product
		$req_params['service'] = isset($this->processor_config->service_code) ? $this->processor_config->service_code : '';
		
		$cost = 0;
		// Getting shipping cost for each product
		foreach ($request->products as $product){			
			for ($qty = 0; $qty < $product->quantity; $qty++){
				$req_params['package_list'][] = array(
					'width' => ($product->width 	? $product->width : 1),
					'height' => ($product->height 	? $product->height : 1),
					'depth' => ($product->length 	? $product->length : 1),
					'weight' => ($product->weight > 0 ? $product->weight : 0.5),
					'packaging_type' => !empty($this->processor_config->packaging_type) ? $this->processor_config->packaging_type : 'VARIABLE',
					'packaging_size' =>  !empty($this->processor_config->packaging_size)? $this->processor_config->packaging_size : 'REGULAR',
					'machinable' => isset($this->processor_config->machinable) ? $this->processor_config->machinable :  false,
				);
			}
		}

		return $req_params;
	}
	
	protected function _getUspsShippingCost($req_params)
	{
		// POST Request
		$errno = $errstr = $result = '';
		$xmlTab = $this->_getXml($req_params);
		$resultTab = array();
		$resultTab['rate'] = false;

		// Loop on Xml
		foreach ($xmlTab as $xml){
			$url 	= 'http://production.shippingapis.com/ShippingAPI.dll';
			$data 	= 'API=RateV4&XML='.$xml;
			
			jimport('joomla.http.transport');
			$link 		= new JURI($url);		
			$curl 		= new JHttpTransportCurl(new Rb_Registry());
			$response 	= $curl->request('POST', $link, $data);		
		
			if (!$response || !$response->body){
				// @PCTODO : ERROR
				return array(false, Rb_Text::sprintf('COM_PAYCART_SHIPPINGRULE_CONNECTION_ERROR', 'USPS'));
			}				

			$result = $response->body;

			// Get xml from HTTP Result
			$data = trim($result);
			if (strpos($result, '<?'))
				$data = strstr($result, '<?');

			// Parsing XML
			$resultTabTmp = simplexml_load_string($data);
			$resultTabTmpDebug[] = $resultTabTmp;

			if (!isset($resultTabTmp->Package) && isset($resultTabTmp->Description) && isset($resultTabTmp->Number))
			{
				if (!isset($resultTab['error']))
					$resultTab['error'] = '';
				$resultTab['error'] .= '<b>'.(string)$resultTabTmp->Number.'</b> : '.(string)$resultTabTmp->Description."\n";
			}

			if (isset($resultTabTmp->Package)){
				foreach ($resultTabTmp->Package as $package)
				{
					if (isset($package->Error))
					{
						if (!isset($resultTab['error']))
							$resultTab['error'] = '';
						$tmp = (array)$package;
						$resultTab['error'] .= (isset($package->Error->Description) ? 'Error <b>'.(string)$package->Error->HelpContext.'</b> on package <b>'.(string)$tmp['@attributes']['ID'].'</b> : '.(string)$package->Error->Description : 'Error')."\n";
					}
					if (isset($package->Postage->Rate))
					{
						if (!isset($resultTab['rate']))
							$resultTab['rate'] = 0;
						$resultTab['rate'] += (string)$package->Postage->Rate;
					}
				}
			}
		}

		//@PCTODO : LOG ERROR if occurs
		
		return $resultTab;	
	}
	
	protected function _getXml($req_params)
	{
		// Template Xml Package List
		$count = 0;
		$xmlTab = array();
		$xmlPackageList = '';
		
		foreach ($req_params['package_list'] as $k => $p)
		{
			// @PCTODO : conversion should be global or in processor base class 
			// KG, LB, OU conversions			
			$p['weight_pounds'] = $p['weight'] * 2.20462262;			
			$p['weight_ounces'] = $p['weight_pounds'] * 16;
			$p['weight_pounds'] = 0;

			// First class management
			if (substr($req_params['service'], 0, 11) == 'FIRST CLASS')
				$req_params['firstclassmailtype'] = '<FirstClassMailType>PARCEL</FirstClassMailType>';
			else
				$req_params['firstclassmailtype'] = '';

				
			$xmlPackageList .= '<Package ID="'.($k + 1).'ST">
									<Service>'.$req_params['service'].'</Service>'.
									$req_params['firstclassmailtype'].'
									<ZipOrigination>'.$req_params['shipper_postalcode'].'</ZipOrigination>
									<ZipDestination>'.$req_params['recipient_postalcode'].'</ZipDestination>
									<Pounds>'.$p['weight_pounds'].'</Pounds>
									<Ounces>'.$p['weight_ounces'].'</Ounces>
							        <Container>'.$p['packaging_type'].'</Container>
									<Size>'.$p['packaging_size'].'</Size>
							        <Machinable>'.$p['machinable'].'</Machinable>
							    </Package>';
			
			$count++;
			if ($count == 25){
				// Template Xml
				$xmlTemplate 	= '<RateV4Request USERID="'.(isset($this->processor_config->user_id) ? $this->processor_config->user_id : '').'">
										<Revision>2</Revision>
										'.$xmlPackageList.'
									</RateV4Request>';			
				$xmlTab[] 	 	= $xmlTemplate;
				$xmlPackageList = '';
				$count 			= 0;
			}
		}

		// Template Xml
		if ($count > 0){
			// Template Xml
			$xmlTemplate 	= '<RateV4Request USERID="'.(isset($this->processor_config->user_id) ? $this->processor_config->user_id : '').'">
									<Revision>2</Revision>
									'.$xmlPackageList.'
								</RateV4Request>';			
			$xmlTab[] 	 	= $xmlTemplate;
		}

		// Return
		return $xmlTab;
	}
}