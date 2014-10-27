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

class PaycartFormFieldPaymentgateway extends JFormFieldList
{	
	/**
	 * (non-PHPdoc)
	 * @see libraries/joomla/form/fields/JFormFieldList::getOptions()
	 */
	public function getOptions()
	{
		$options = parent::getOptions();
		
		$processors = PaycartFactory::getHelper('invoice')->getProecessorList();	
			
		if(empty($processors)){
			return $options;
		}
		
		$processors_options = Array();

		// build  processor list for display
		foreach ($processors as $type =>$details) {
			$processors_options[strtolower($type)]  = ucfirst($type);
		}
		
		return array_merge($options, PaycartHtml::buildOptions($processors_options));		
	}	
	
	/**
	 * (non-PHPdoc)
	 * @see libraries/joomla/form/fields/JFormFieldList::getInput()
	 */
	protected function getInput()
	{
		// if Payment gateway already set then we cant change it after save
		if ( !empty($this->value) ) {
			$this->readonly = true;
		}
		
		return parent::getInput();
		
	}
	
	
}