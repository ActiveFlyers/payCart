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

class PaycartFormFieldGroup extends JFormFieldList
{	
	public function getInput()
	{	
		$this->group_type = '';
		if(isset($this->element['group_type'])){
			$this->group_type = (string)$this->element['group_type'];
		}	
		
		return parent::getInput();		
	}
	
	public function getOptions()
	{	
		$options = parent::getOptions();
		$groups = $this->getGroups($this->group_type);
			
		if(empty($groups)){
			return $options;
		}
		
		return array_merge($options, PaycartHtml::buildOptions($groups));		
	}	
	
	private function getGroups($type = '')
	{
		$model = PaycartFactory::getModel('group');
		// Should be sorted according to 'title' so need to write query with "order by"
		$model->clearQuery();  
		$query = $model->getQuery()->clear('order')->order('title');
		if(!empty($type)){
			$query->where('`type` = "'.$type.'"')
				  ->where('`published` = 1');
		}
		
		return $model->loadRecords();
	}
}