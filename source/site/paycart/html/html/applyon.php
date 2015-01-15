<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		rimjhim jain
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class PaycartHtmlApplyon
{	
	public static function getList($name, $value='', $ignore=array(), $idtag = false, $attr = Array())
	{	
		$applyonOptions = array(
								Paycart::RULE_APPLY_ON_CART 	=> JText::_('COM_PAYCART_ADMIN_APPLYON_CART'),
								Paycart::RULE_APPLY_ON_PRODUCT 	=> JText::_('COM_PAYCART_ADMIN_APPLYON_PRODUCT'),
								Paycart::RULE_APPLY_ON_SHIPPING => JText::_('COM_PAYCART_ADMIN_APPLYON_SHIPPING')
							   );
		
        //unset the options that are being ignored
		foreach ($ignore as $key){
			if(array_key_exists($key,$applyonOptions)){
				unset($applyonOptions[$key]);
			}
		}
		
		return PaycartHtml::_('select.genericlist', $applyonOptions, $name, $attr, '', '', $value, $idtag);
	}
}