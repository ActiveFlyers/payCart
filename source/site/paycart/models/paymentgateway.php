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
class PaycartModelPaymentgateway extends PaycartModelLang
{
	public $filterMatchOpeartor = array(
										'title' 	=> array('LIKE'),
										'processor_type'=> array('='),
										'published' => array('='),
									);
}

/** 
 * Payment Gateway Model
 */
class PaycartModelFormPaymentgateway extends PaycartModelform
{}

class PaycartTablePaymentgateway extends PaycartTable
{}

class PaycartTablePaymentgatewaylang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_paymentgateway_lang', $tblPrimaryKey='paymentgateway_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}
