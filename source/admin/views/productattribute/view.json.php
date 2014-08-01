<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Attribute Json View
 */

require_once dirname(__FILE__).'/view.php'; 
class PaycartAdminViewProductAttribute extends PaycartAdminBaseViewProductAttribute
{	
	public function getEditHtml()
	{
		$productAttributeId = $this->getModel()->getId();
		
		$this->assign('productAttribute', PaycartProductAttribute::getInstance($productAttributeId));
		$this->assign('productAttributeValue', $this->input->get('value', ''));
		
		// @PCTODO IMP : Echo and exit required for angularjs layout
		// 		 because this task is called from ng-include
		echo $this->loadTemplate('addattribute');		
		exit();		
	}
	
	public function edit($tpl = null)
	{
		$attributeId	= $this->getModel()->getId();
		$attribute		= PaycartProductAttribute::getInstance($attributeId);		
		$form 			= $attribute->getModelform()->getForm();
	    
	    // get html of the specific 	    
	    $html = $attribute->getConfigHtml();
	    
	    $this->assign('attributeHtml',$html);
		$this->assign('form', $form);
		$this->assign('json', $this->loadTemplate('edit'));
		return true;
	}
	
	public function save()
	{
		$id = $this->get('productattribute_id');
		if($this->get('success', false)){			
			$response = array('success' => true);
			$response['message'] = JText::_('COM_PAYCART_ADMIN_SUCCESS_ITEM_SAVE');
			$response['productattribute'] = (object) PaycartProductAttribute::getInstance($id)->toArray();
		}
		else{
			$response = array('success' => false);				
			$response['message'] = JText::_('COM_PAYCART_ADMIN_ERROR_ITEM_SAVE');
		}
		
		$response['productattribute_id'] = $id;
		$this->assign('json', $response);
		return true;
	}
	
	public function deleteAttribute()
	{		
		if($this->get('success', false)){			
			$response = array('success' => true);
			$response['message'] = JText::_('COM_PAYCART_ADMIN_PRODUCTATTRIBUTE_DELETE_SUCCESS');
		}
		else{
			$response = array('success' => false);				
			$response['message'] = JText::_('COM_PAYCART_ADMIN_PRODUCTATTRIBUTE_DELETE_ERROR');
		}
		$this->assign('json', $response);
		
		return true;	
	}
}
