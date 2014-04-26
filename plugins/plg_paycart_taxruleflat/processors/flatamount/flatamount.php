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

		try{
			if(!$this->rule_config->tax_rate){
				throw new InvalidArgumentException(Rb_Text::_('COM_PAYCART_TAXRULE_RATE_CANT_BE_ZERO'));
			}
			
			//it is fixed tax amount so we need to consider product quantity
			$response->amount = ($this->rule_config->tax_rate * $request->cartparticular->quantity);
		}
		catch (Exception $e){
			$response->exception = $e;
		}
		
		return $response;
	}
	
}
