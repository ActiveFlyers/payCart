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
	public function __construct($config = Array()) 
	{
		$this->response = PaycartFactory::getAjaxResponse();
		
		return parent::__construct($config);
	}
	
	/**
	 * Html set on success-callback.
	 * Rest things continue.
	 * @see plugins/system/rbsl/rb/rb/view/Rb_ViewAjax::render()
	 */
	protected function render($html, $options = array('domObject'=>'rbWindowContent','domProperty'=>'innerHTML'))
	{
		$response	= PaycartFactory::getAjaxResponse();
		
		$data = Array('message'=> '','html' => $html );
		
		$response->addScriptCall('paycart.checkout.success', $data);
		
		$response->sendResponse();
	}
	
	/**
	 * Enter description here ...
	 */
	public function process()
	{
		$this->_process();
		
		$this->setTpl($this->step_ready);
		return true;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function goBack()
	{
		$back_to = $this->input->get('back_to');
		
		$ajax_response = PaycartFactory::getAjaxResponse();
		
		switch ($back_to) {
			case 'billing_address':
				parent::prepare_step_address();
				//move_next
				$this->response->addScriptCall("paycart.checkout.buyeraddress.billing_address_info['move_next'] = false; ");
				$this->response->addScriptCall('paycart.checkout.buyeraddress.visible_address_info = paycart.checkout.buyeraddress.billing_address_info;');
				
			break;
			
			case 'shipping_address':
				parent::prepare_step_address();
				$this->response->addScriptCall("paycart.checkout.buyeraddress.shipping_address_info['move_next'] = false; ");
				$this->response->addScriptCall('paycart.checkout.buyeraddress.visible_address_info = paycart.checkout.buyeraddress.shipping_address_info;');
				
			default:
				;
			break;
		}

		$this->setTpl($this->step_ready);
		return true;
	}

}