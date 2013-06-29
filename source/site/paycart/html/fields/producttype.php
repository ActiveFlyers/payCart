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


JFormHelper::loadFieldClass('list');

class PaycartFormFieldProductType extends JFormFieldList
{	
	public function getOptions()
	{
		$product_type = PaycartHelperProduct::getTypes();
		return PaycartHtml::buildOptions($product_type);		
	}
	
}