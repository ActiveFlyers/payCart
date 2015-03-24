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



class PaycartHtmlCountry
{	
	/**
	 * 
	 * Invoke to get Paycart Country HTML
	 * @param $name 	: 	field name
	 * @param $value	:	field value
	 * @param $attr	:	field attribute
	 */
	public static function getList($name, $value='', $idtag = false, $attr = Array())
	{
		$available_countries = PaycartFactory::getModel('country')->loadRecords(Array('published' => 1));
		
		return PaycartHtml::_('select.genericlist', $available_countries, $name, $attr, 'country_id', 'title', $value, $idtag);
	}
	
	static function filter($name, $view, Array $filters = array(), $prefix='filter_paycart')
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$options    = array();
		$options[0] = array('title'=>JText::_('COM_PAYCART_ADMIN_FILTERS_SELECT_COUNTRY'), 'value'=>'');
		$countries  = PaycartFactory::getModel('country')->loadRecords();
		
		foreach ($countries as $key => $country){			
			$options[$key] = array('title' => $country->title, 'value' => $key);
		}
		
		return JHtml::_('select.genericlist', $options, $elementName.'[]', 'onchange="document.adminForm.submit();"', 'value', 'title', $elementValue);
	}
	
}