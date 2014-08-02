<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	paycartHelper
 * @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Format Helper
 * @author Rimjhim Jain
 */

class PaycartHelperFormat extends JObject
{
	/**
	 * Format the price/amount as per paycart configuration
	 * @param $amount : To be formatted
	 * @param $addCurreny : Set it to true if you want to add currency with amount like $ 10.00
	 * @param $currencyId : currency id if amount to be get with currency. To use it set $addCurreny to true. 
	 * 						(If $addCurreny is true and $currencyId is null then default currency will be added)
	 * @return amount or amount with currency as per the configuration
	 */
	public function amount($amount, $addCurreny = true, $currencyId = null)
	{
		$config = PaycartFactory::getConfig();
		
		$fractionDigitCount = $config->get('localization_fraction_digit_count');
		
		$formatedAmount = number_format(round($amount, $fractionDigitCount), $fractionDigitCount, $config->get('localization_decimal_separator'), '');
		
		if($addCurreny){
			if(empty($currencyId)){
				$currencyId = $config->get('localization_currency');
			}
			
			$currency = $this->currency($currencyId);
			
			if($config->get('localization_currency_position') == 'before'){
				return $currency.' '.$formatedAmount;
			}
			else{
				return $formatedAmount.' '.$currency;
			}
		}
		
		return $formatedAmount;
	}
	
	/**
	 * 
	 * Format currency as per the paycart configuration
	 * @param $currencyId : currency Id to be formatted
	 * @return formatted currency
	 */
	public function currency($currencyId)
	{
		$record	 = PaycartFactory::getHelper('invoice')->getCurrency($currencyId);
		$format  = PaycartFactory::getConfig()->get('localization_currency_format');
		
		if(!isset($format) || $format == 'fullname'){
			return $record->title.' ('. $record->currency_id .')';
		}
		
		if($format == 'isocode'){
			return $record->currency_id;
		}
		
		if($format == 'symbol'){
			return $record->symbol;
		}
		
		return '';
	}
	
}