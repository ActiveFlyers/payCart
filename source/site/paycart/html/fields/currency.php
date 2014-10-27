<?php

/**
* @copyright	Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		Paycart
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JFormHelper::loadFieldClass('list');
/** 
 * Currency Field
 * @author rimjhim
 */
class PaycartFormFieldCurrency extends JFormFieldList
{
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		$value_field  = isset($this->element['value_field']) ? $this->element['value_field'] : 'isocode3';

		$currencies   = PaycartFactory::getHelper('invoice')->getCurrency();
		
		foreach ($currencies as $currency){
			$options[] = PaycartHtml::_('select.option', $currency->currency_id, $currency->title.' ('.$currency->currency_id.')');
		}
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
