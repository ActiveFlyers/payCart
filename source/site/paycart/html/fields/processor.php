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

class PaycartFormFieldProcessor extends JFormFieldList
{	
	public function getInput()
	{	

		if(!isset($this->element['processor_type'])){
			throw new Exception('Invalid type of Processor given in Form xml.');
		}
		
		$this->processor_type = (string)$this->element['processor_type'];
		
		return parent::getInput();		
	}
	
	public function getOptions()
	{	
		$options = parent::getOptions();
		$processors = $this->getProcessor($this->processor_type);
			
		if(empty($processors)){
			return $options;
		}
		
		return array_merge($options, PaycartHtml::buildOptions($processors));		
	}	
	
	private function getProcessor($type)
	{
		/*@var $helper PaycartHelperProcessor */			
		$helper = PaycartFactory::getHelper('processor');
		return $helper->getList($type);		
	}
}