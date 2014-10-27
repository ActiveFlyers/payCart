<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		Puneet Singhal 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


JFormHelper::loadFieldClass('list');

class PaycartFormFieldCartStatus extends JFormFieldList
{	
	public function getOptions()
	{
		$status = Paycart::getCartStatus();
		return PaycartHtml::buildOptions($status);		
	}
	
}