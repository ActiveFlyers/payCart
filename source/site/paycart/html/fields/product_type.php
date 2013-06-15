<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		mManishTrivedi 
*/

if(defined('_JEXEC')===false) die();


JFormHelper::loadFieldClass('list');
jimport('joomla.form.formfield');

class PaycartFormFieldProduct_Type extends JFormFieldList
{
	
	//protected $type = 'product_type';
		
	public function getOptions()
	{
		//$product = PaycartProduct::getInstance();
		$product_type = PaycartProduct::getProductType();
		return PaycartHtml::buildOptions($product_type);		
	}
	
}