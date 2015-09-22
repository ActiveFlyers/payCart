<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		mManishTrivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Category Html View
* @author Manish Trivedi
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminHtmlViewProductcategory extends PaycartAdminBaseViewProductcategory
{	
	protected function _adminGridToolbar()
	{
		Rb_HelperToolbar::addNew('new','COM_PAYCART_ADMIN_ADD');
		Rb_HelperToolbar::editList();
		Rb_HelperToolbar::divider();
		Rb_HelperToolbar::deleteList(Rb_Text::_('COM_PAYCART_ENTITY_DELETE_CONFIRMATION'));
		Rb_HelperToolbar::publish();
		Rb_HelperToolbar::unpublish();
	}
	
	protected function _adminEditToolbar()
	{
		Rb_HelperToolbar::apply();
		Rb_HelperToolbar::save();
		Rb_HelperToolbar::cancel();
	}
	
	public function edit($tpl = null)
	{
		$model 	  = $this->getModel();
		$catId	  = $model->getId();
		$category = PaycartProductcategory::getInstance($catId);
		

		// Get the prvioisly posted data if any
		// if it is not empty it means there were some errors
		$post_data = $model->getState('post_data', array());
		if(!empty($post_data)){
			$category->bind($post_data);
		}	
		$this->assign('error_fields', $model->getState('error_fields', array()));
		
		$form 	  = $category->getModelform()->getForm($category);
				
		//set images
		$this->assign('cover_media', $category->getCoverMedia());
		
		$this->assign('form', $form );
		$this->assign('productCategory',$category);
		$this->assign('uploadLimit',PaycartFactory::getHelper('media')->getUploadLimit());

		return parent::edit($tpl);
	}
	
	function _displayGrid($records)
	{
		// Enqueue warning message if set up screen is not clean
		PaycartHelperSetupchecklist::setWarningMessage();
		
		//unset root category from records, so that root won't be listed on grid
		unset($records[Paycart::PRODUCTCATEGORY_ROOT_ID]);
		
		if(count($records) > 0){
			return parent::_displayGrid($records);
		}
		
		return $this->_displayBlank();
	}
}
