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
    
        static protected $_country_data  = Array();
        static protected $_state_data   = Array();

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
	
	/**
	 * 
	 * format weight as per the configuration 
	 * @param $value : value (in kg) to be formatted according to the configuration setting 
	 */
	function weight($value, $defaultUnit = paycart::WEIGHT_UNIT_GRAM)
	{
		$weightUnitConfig = PaycartFactory::getConfig()->get('catalogue_weight_unit');
		
		return $this->convertWeight($value, $defaultUnit, $weightUnitConfig);
	}
	
	/**
	 * 
	 * convert value into the resultant unit
	 * @param $value : value to be converted
	 * @param $inputUnit : weight unit in which the value is (kg, gm, lb, oz etc)
	 * @param $resultantUnit : weight unit in which the value to be converted
	 */
	function convertWeight($value, $inputUnit , $resultantUnit = paycart::WEIGHT_UNIT_GRAM)
	{
		$key = $inputUnit.'->'.$resultantUnit;
		
		switch($key)
		{
			case Paycart::WEIGHT_UNIT_KILOGRAM.'->'.Paycart::WEIGHT_UNIT_GRAM  : return (1000*$value);
			
			case Paycart::WEIGHT_UNIT_KILOGRAM.'->'.Paycart::WEIGHT_UNIT_PONUD : return (2.20462*$value);
			
			case Paycart::WEIGHT_UNIT_KILOGRAM.'->'.Paycart::WEIGHT_UNIT_OUNCE : return (35.274*$value);
			
			case Paycart::WEIGHT_UNIT_GRAM.'->'.Paycart::WEIGHT_UNIT_KILOGRAM  : return ($value/1000);
			
			case Paycart::WEIGHT_UNIT_PONUD.'->'.Paycart::WEIGHT_UNIT_KILOGRAM : return (0.453592*$value);
					
			default	 :  return $value;
		}
	}
	
	/**
	 * 
	 * format weight unit
	 * @param $value : value  to be formatted according to the configuration setting 
	 */
	function dimension($value, $defaultUnit = paycart::DIMENSION_UNIT_CENTIMETER)
	{
		$dimensionUnitConfig = PaycartFactory::getConfig()->get('catalogue_dimension_unit');
		
		return $this->convertDimension($value, $defaultUnit, $dimensionUnitConfig);
	}
	
	/**
	 * 
	 * convert value into the resultant unit
	 * @param $value : value to be converted
	 * @param $inputUnit : dimension unit in which the value is (m, cm, in etc)
	 * @param $resultantUnit : dimension unit in which the value to be converted
	 */
	function convertDimension($value, $inputUnit, $resultantUnit = paycart::DIMENSION_UNIT_CENTIMETER)
	{
		$key = $inputUnit.'->'.$resultantUnit;
		
		switch($key)
		{
			case Paycart::DIMENSION_UNIT_METER.'->'.Paycart::DIMENSION_UNIT_CENTIMETER  : return (100*$value);
			
			case Paycart::DIMENSION_UNIT_METER.'->'.Paycart::DIMENSION_UNIT_INCH 		: return (39.3701*$value);
			
			case Paycart::DIMENSION_UNIT_CENTIMETER.'->'.Paycart::DIMENSION_UNIT_METER  : return ($value/100);

			case Paycart::DIMENSION_UNIT_INCH.'->'.Paycart::DIMENSION_UNIT_METER 		: return (0.0254*$value);
						
			default	 :  return $value;
		}
	}
	
	/**
	 * PCTODO : Consider user's timezone
	 * Format date into given format(if any) or format date according to configuration 
	 * @param Rb_Date $date
	 * @param unknown_type $format : date format to change the date
	 */
	public function date(Rb_Date $date ,$format=null)
	{
		$date_format	= PaycartFactory::getConfig()->get('localization_date_format');
		$format 		= ($format === null) ? $date_format : $format;

		if(empty($format)){
			return (string)$date;
		}
		
		return $date->toFormat($format);
	}
        
        /**
         * Invoke to get Country-Name
         * @param type $country_id 
         * @return country name
         */
        public function country($country_id)
        {
            if ( !isset(static::$_country_data[$country_id]) ) {
                static::$_country_data = PaycartFactory::getModel('country')
                                                  ->loadRecords();
            }
            
            
            if ( !isset(static::$_country_data[$country_id]) ) {
                return JText::_('COM_PAYCART_ERROR_UNKNOWN_COUNTRY');
            }     
            
            return static::$_country_data[$country_id]->title;             
        }
        
        
        /**
         * Invoke to get State-Name
         * @param type $country_id 
         * @return state name
         */
        public function state($state_id)
        {
            if ( !isset(static::$_state_data[$state_id]) ) {
                static::$_state_data = PaycartFactory::getModel('state')
                                                  ->loadRecords();
            }
            
            
            if ( !isset(static::$_state_data[$state_id]) ) {
                return JText::_('COM_PAYCART_ERROR_UNKNOWN_STATE');
            }     
            
            return static::$_state_data[$state_id]->title;             
        }
        
    /**
     * convert the given price range into a display format
     * @param $value : comma separated range
     */
	public function priceRange($value, $separator = ' - ')
	{
		$range = explode(',', $value);
		      
		$range[0] = $this->amount($range[0]);
		$range[1] = $this->amount($range[1]);
		
		return implode($separator,$range);
	}

	/**
     * convert the given weight range into a display format
     * @param $value : comma separated range
     */
	public function weightRange($value, $separator = ' - ')
    {
       $weightUnitConfig = PaycartFactory::getConfig()->get('catalogue_weight_unit');
       $range = explode(',', $value);
		      
	   $range[0] = $range[0].' '.$weightUnitConfig;
	   $range[1] = $range[1].' '.$weightUnitConfig;
	
	   return implode($separator,$range);
    }
        
}