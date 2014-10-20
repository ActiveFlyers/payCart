<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Product Controller
 * @author Manish Trivedi
 */

class PaycartAdminControllerProductAttribute extends PaycartController 
{
	public function save()
	{
		if($this->input->get('format', 'html') == 'json'){
			$data 	= $this->input->post->get($this->_component->getNameSmall().'_form', array(), 'array');		
			$itemId = $this->_getId();		
			$ret 	= $this::_save($data, $itemId);
			
			$view = $this->getView();
			if($ret){
				$view->assign('success', true);
				$view->assign('productattribute_id', $ret->getId());
			}
			else{
				$view->assign('success', false);
				$view->assign('productattribute_id', $itemId);

				// set error fields in model
				$app = JFactory::getApplication();
				$model = $this->getModel();
				$context = $model->getContext();
				$error_fields 	 = $app->getUserStateFromRequest($context.'.error_fields', 'error_fields', array(), 'array');        		
        		$app->setUserState($context . '.error_fields', null);
        		$this->getModel()->setState('error_fields', $error_fields );				
			}
		
			return true;
		}
		
		return parent::save();
	}
	
	/**
	 * override it due to get all uploaded files 
	 */
	public function _save(array $data, $itemId=null, $type=null)
	{
		//Get All files from paycart form
		$data['_options'] = $this->input->get('options', false, 'ARRAY');
		return parent::_save($data, $itemId, $type);
	}
			
	/**
	 * 
	 * Ajax call : Get elements of Attribute Configuration
	 */
	public function getTypeConfig()
	{
		$attributeId	=  $this->input->get('productattribute_id',0);
		$data 			= array();
		$data['type']	=  $this->input->get('attributeType',0);
		
		$attribute		=  PaycartProductAttribute::getInstance($attributeId)->bind($data);

		$html = $attribute->getConfigHtml();
		$js	  = $attribute->getScript(); 

		// replace specific div html and call script
		$ajaxResponse = PaycartFactory::getAjaxResponse();
		
		$ajaxResponse->addScriptCall('paycart.jQuery("#paycart-attribute-config").html', $html);

		if(!empty($js)){		
			$ajaxResponse->addScriptCall($js);
		}
		return false;
	}
	
	/**
	 * Ajax Call: create new attribute from product 
	 */
	public function create() 
	{
		$attribute 		= parent::save();

		//PCTODO: If product is not saved then show proper error message
		$productId 		= $this->input->get('productId',0); 
		
		$this->setRedirect( 'index.php?option=com_paycart&view=product&task=edit&product_id='.$productId);

		return false;
	}
	
	/**
	 * Ajaxified task to remove any existing option
	 */
	public function removeOption()
	{
		$type      = $this->input->get('attributeType');
		$counter   = $this->input->get('counter');
		$optionId  = $this->input->get('optionId',0);
		
		if(PaycartAttribute::getInstance($type)->deleteOptions($attribute, $optionId)){
			$ajaxResponse = PaycartFactory::getAjaxResponse();
			$ajaxResponse->addScriptCall('paycart.jQuery("#option_row_'.$counter.'").remove');
		}	
		return false;
	}
	
	/**
	 * Ajaxified task to add a new option to an attribute
	 */
	public function addOption()
	{
		$type      = $this->input->get('attributeType');
		$totalRows = $this->input->get('totalRows',0);
		
		$instance = PaycartAttribute::getInstance($type);
		$html = $instance->buildCounterHtml($totalRows, $type);
		$js	  = $instance->getScript(); 
		
		$ajaxResponse = PaycartFactory::getAjaxResponse();
		$ajaxResponse->addScriptCall('paycart.jQuery("#paycart-attribute-options").append',$html);
		
		if(!empty($js)){		
			$ajaxResponse->addScriptCall($js);
		}
		return false;
	}
	
	public function getEditHtml()
	{
		return true;
	}
	
	public function deleteAttribute()
	{
		$productattribute_id   = $this->input->get('productattribute_id',0);		 
					
		$view = $this->getView();
		if(PaycartProductAttribute::getInstance($productattribute_id)->delete()){
			$view->assign('success', true);
		}
		else{
			$view->assign('success', false);
		}
		
		return true;
	}
}