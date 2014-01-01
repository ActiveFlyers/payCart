<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		Joomla.Plugin
* @subpackage	Paycart
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Flat Amount Processor 
 * @author rimjhim
 */
class PaycartTaxruleProcessorFlatAmount extends PaycartTaxruleProcessor
{
	function process(PaycartTaxruleRequest $request, PaycartTaxruleResponse $response)
	{
		if(!$this->isApplicable($request, $response)){
			return $response;
		}
		
		//it is fixed tax amount so we need to consider product quantity 
		return $response->taxAmount = ($request->taxRate * $request->productQuantity);
	}
	
}
