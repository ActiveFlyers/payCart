<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



class PaycartHtmlCountry extends Rb_Html
{	
	/**
	 * 
	 * Invoke to get Paycart Country HTML
	 * @param $name 	: 	field name
	 * @param $value	:	field value
	 * @param $attr	:	field attribute
	 */
	public static function getList($name, $value, $attr = Array())
	{
		$available_countries = PaycartFactory::getModel('country')->loadRecords(Array('status' => paycart::STATUS_PUBLISHED));
		
		return PaycartHtml::_('select.genericlist', $available_countries, $name, $attr, 'country_id', 'title', $value);
	}
	
}