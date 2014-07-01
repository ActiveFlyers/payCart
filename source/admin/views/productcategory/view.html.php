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
class PaycartAdminViewProductcategory extends PaycartAdminBaseViewProductcategory
{	
	protected function _adminGridToolbar()
	{
		Rb_HelperToolbar::addNew('new','COM_PAYCART_ADMIN_ADD');
		Rb_HelperToolbar::editList();
		Rb_HelperToolbar::divider();
		Rb_HelperToolbar::deleteList(Rb_Text::_('COM_PAYCART_ADMIN_DELETE_PROMPT'));
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
		$catId	  =  $this->getModel()->getId();
		$category =  PaycartProductcategory::getInstance($catId);
		$form 	  = $category->getModelform()->getForm($category);
		
		//set images
		$this->assign('cover_media', $category->getCoverMedia());
		
		$this->assign('form', $form );
		
		return parent::edit($tpl);
	}
}
