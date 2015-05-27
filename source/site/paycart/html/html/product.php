<?php
/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	product html
* @author 		Garima 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



class PaycartHtmlProduct
{
	/**
	 * 
	 * Invoke to get Paycart Country HTML
	 * @param $name 	: 	field name
	 * @param $value	:	field value
	 * @param $idtag	:   field id
	 * @param $attr		:	field attribute
	 */
	public static function getList($name,$value='', $idtag = false, $attr = array(), $filters= array( 'published' => 1) )
	{
		$pModel = PaycartFactory::getInstance('product', 'model');
		$products = $pModel->loadRecords($filters);
			
		return PaycartHtml::_('select.genericlist', $products, $name, $attr, 'product_id', 'title', $value, $idtag);
	}

}
