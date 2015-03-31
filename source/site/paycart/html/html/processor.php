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

class PaycartHtmlProcessor
{		
	static function filter($name, $view, $type, Array $filters = array(), $prefix='filter_paycart')
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$options    = array();
		$options[0] = array('title'=>JText::_('COM_PAYCART_ADMIN_FILTERS_SELECT_TYPE'), 'value'=>'');
		
		//only those processor will be listed out for which any instance is created
		$processors	= PaycartFactory::getModel($type)->loadRecords(array(),array(), false, 'processor_classname');
		$installed  = PaycartFactory::getHelper('processor')->getList($type);
		foreach ($processors as $key => $data){			
			$options[$key] = array('title' => $installed[$key]->title, 'value' => $key);
		}
		
		return JHtml::_('select.genericlist', $options, $elementName.'[]', 'onchange="document.adminForm.submit();"', 'value', 'title', $elementValue);
	}	
}