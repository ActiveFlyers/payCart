<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Category Base View
* @author mManishTrivedi
 */
class PaycartAdminBaseViewCategory extends PaycartView
{	
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::edit()
	 */
	public function edit($tpl=null) {
		
		$categoryId	=  $this->getModel()->getState('id');
		$category	=  PaycartCategory::getInstance($categoryId);
		
		$this->assign('form',  $category->getModelform()->getForm($category));
		return parent::edit($tpl);
	}
	
}