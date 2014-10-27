<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/
defined('_JEXEC') or die( 'Restricted access' );

/**
 * PaymentGateway Admin Controller for Group
 * 
 * @since 1.0.0
 *  
 * @author mManishTrivedi
 */
class PaycartAdminControllerPaymentGateway extends PaycartController 
{
	/**
	 * AJAX Task
	 */
	public function getConfigHtml()
	{
		$processor_type	= $this->input->get('processor_type', '');
		$processor_id	= $this->input->get('processor_id', 0, 'INT');
		
		$html = PaycartPaymentgateway::getInstance($processor_id)->getConfigHtml($processor_type);
		
		$response = new stdClass();
		$response->config_selector 	= $this->input->get('config_selector', NULL, 'RAW');
		$response->html				= $html;
		
		
		$ajax = PaycartFactory::getAjaxResponse();
		$ajax->addScriptCall('paycart.admin.paymentgateway.setProcessorConfigHtml', $response);
		
		return false;
	}
	
}