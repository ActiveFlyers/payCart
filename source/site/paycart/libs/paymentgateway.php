<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		Puneet Singhal, Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Paymentgateway Lib
 * @author manish
 *
 */
class PaycartPaymentgateway extends PaycartLib
{
	/**
	 * Payment Gateway table fields
	 */
	protected $paymentgateway_id;
	protected $title;
	protected $status;
	protected $processor_type;
	protected $processor_config;
	
	/**
	 * 
	 * PaycartPaymentgateway Instance
	 * @param  $id,			existing Paymentgateway id
	 * @param  $bindData, 	required data to bind on return instance	
	 * @param  $dummy1, 	Just follow code-standards
	 * @param  $dummy2, 	Just follow code-standards
	 * 
	 * @return PaycartPaymentgateway lib instance
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('paymentgateway', $id, $bindData);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/_rbsl/rb/rb/Rb_Lib::reset()
	 */
	public function reset() 
	{		
		$this->paymentgateway_id		=	0;
		$this->title					=	'';
		$this->status					=	Paycart::STATUS_PUBLISHED;
		$this->processor_type				=	'';
		$this->processor_config			=	new Rb_Registry();
		
		return $this;
	}
	
	
	public function getType()
	{
		return $this->processor_type;
	}
	
	public function getConfig()
	{
		return $this->processor_config;
	}

	
}
