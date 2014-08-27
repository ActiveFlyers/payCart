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
}