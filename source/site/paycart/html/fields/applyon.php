<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		support+paycart@readybytes.in 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JFormHelper::loadFieldClass('list');

class PaycartFormFieldApplyon extends JFormFieldList
{	
	public function getOptions()
	{	
		$options = parent::getOptions();
		$applyonOptions = $this->getApplyonOptions();
			
		if(empty($applyonOptions)){
			return $applyonOptions;
		}
		
		return array_merge($options, PaycartHtml::buildOptions($applyonOptions));		
	}	
	
	private function getApplyonOptions()
	{
		return array(
					Paycart::RULE_APPLY_ON_PRODUCT 	=> 'COM_PAYCART_ADMIN_APPLYON_PRODUCT',
					Paycart::RULE_APPLY_ON_SHIPPING => 'COM_PAYCART_ADMIN_APPLYON_SHIPPING',
					Paycart::RULE_APPLY_ON_CART 	=> 'COM_PAYCART_ADMIN_APPLYON_CART',
		);
	}
}