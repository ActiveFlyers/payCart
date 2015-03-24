<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		rimjhim 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



class PaycartHtmlCategory
{
	/**
	 * 
	 * Invoke to get Paycart Country HTML
	 * @param $name 	: 	field name
	 * @param $value	:	field value
	 * @param $idtag	:   field id
	 * @param $attr		:	field attribute
	 */
	public static function getList($name, $value='', $idtag = false, $attr = Array())
	{
		$available_categories = PaycartFactory::getHelper('productcategory')->getCategory();
		
		return PaycartHtml::_('select.genericlist', $available_categories, $name, $attr, 'productcategory_id', 'title', $value, $idtag);
	}
	
	static function filter($name, $view, Array $filters = array(), $prefix='filter_paycart')
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$options    = array();
		$options[0] = array('title'=>JText::_('COM_PAYCART_ADMIN_FILTERS_SELECT_CATEGORY'), 'value'=>'');
		$categories = PaycartFactory::getHelper('productcategory')->getCategory();
		
		foreach ($categories as $key => $cat){
			$title = str_repeat('&mdash;', ($cat->level - 1)<0?0:($cat->level - 1)).' '.$cat->title;
			$options[$key] = array('title' => $title, 'value' => $key);
		}
		
		return JHtml::_('select.genericlist', $options, $elementName.'[]', 'onchange="document.adminForm.submit();"', 'value', 'title', $elementValue);
	}
}