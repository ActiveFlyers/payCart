<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi, Rimjhim Jain
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );

require_once dirname(__FILE__).'/view.php';

class PaycartSiteAjaxViewProduct extends PaycartSiteBaseViewProduct
{
	public function addToCart()
	{		$product_id = $this->input->get('product_id');
			$ajax = PaycartFactory::getAjaxResponse();
			$ajax->addScriptCall('$("[data-pc-selector='.$product_id.']").attr("value", "Added");');
			$ajax->addScriptCall('setTimeout(function() { $("[data-pc-selector='.$product_id.']").attr("value","Add to cart") }, 600);');
			$ajax->sendResponse();
	}
}