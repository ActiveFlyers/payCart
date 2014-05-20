<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );
/**
 * 
 * Checkout html View
 * @author Manish
 *
 */
require_once dirname(__FILE__).'/view.php';

class PaycartSiteViewCheckout extends PaycartSiteBaseViewCheckout
{
	/**
	 * Html set on success-callback.
	 * Rest things continue.
	 * @see plugins/system/rbsl/rb/rb/view/Rb_ViewAjax::render()
	 */
	protected function render($html, $options = array('domObject'=>'rbWindowContent','domProperty'=>'innerHTML'))
	{
		$response	= PaycartFactory::getAjaxResponse();
		
		$data = Array('message'=> '','html' => $html );
		
		$response->addScriptCall('paycart.checkout.submit.success', $data);
		
		$response->sendResponse();
	}

}