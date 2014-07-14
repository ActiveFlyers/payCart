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
		$data = Array('message'=> '','html' => $html );
		
		$this->response->addScriptCall('paycart.checkout.success', $data);
		
		$this->response->sendResponse();
	}
	
	protected function _process()
	{
		switch ($this->step_ready) 
		{
			case Paycart::CHECKOUT_STEP_ADDRESS:
				$this->prepare_step_address();
				break;
				
			case Paycart::CHECKOUT_STEP_CONFIRM :
				$this->prepare_step_confirm();
				break;
			case Paycart::CHECKOUT_STEP_PAYMENT :
				$this->prepare_step_payment();
				break;
			
			default:
				;
			break;
		}
		;
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
		
		if ('address' == $back_to) {
			parent::prepare_step_address();
		}

		$this->setTpl($this->step_ready);
		
		return true;
	}

}