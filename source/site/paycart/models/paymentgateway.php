<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Payment Gateway Model
 */
class PaycartModelPaymentgateway extends PaycartModel
{
	public function loadRecords() 
	{
		$stripe = new stdClass();
		$paypal = new stdClass();
		
		$stripe->paymentgateway_id = 1;
		$paypal->paymentgateway_id = 2;
		
		$stripe->title = 'Stripe';
		$paypal->title = 'PayPal';
		
		$stripe->description = '';
		$paypal->description = '';
		
		$stripe->status = Paycart::STATUS_PUBLISHED;
		$paypal->status = Paycart::STATUS_PUBLISHED;
		
		$stripe->processor_type = 'stripe';
		$paypal->processor_type = 'paypal';
		
		$stripe->processor_config  = '{"api_key":"sk_test_X13tGn9VbhcWDhfruzd8SLMN "}';
		$paypal->processor_config  = '{"merchant_email":"testmerchant@readybytes.in","sandbox":"1","notify_url":"http:\/\/5.kappa.readybytes.in\/paycart\/8318\/index.php?option=com_paycart&view=cart&task=notify&processor=paypal","cancel_url":"http:\/\/5.kappa.readybytes.in\/paycart\/8318\/index.php?option=com_paycart&view=cart&task=cancel&processor=paypal","return_url":"http:\/\/5.kappa.readybytes.in\/paycart\/8318\/index.php?option=com_paycart&view=cart&task=complete&processor=paypal"}';
		
		return Array(1 => $stripe, 2=>$paypal );
	}
}

