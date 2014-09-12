<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Category Controller
 * @author Manish Trivedi
 */

class PaycartAdminControllerProductcategory extends PaycartController {
	
	/**
	 * 
	 * Ajax Call : To create new category
	 */
	function create()
	{
		//Check Joomla Session user should be login
		if ( !JSession::checkToken() ) {
			//@PCTODO :: Rise exception 
		}
		
		$post['title'] = $this->input->get('category_name',null,'RAW');
		
		if (!$post['title']) {
			// @codeCoverageIgnoreStart
			throw new UnexpectedValueException(Rb_Text::sprintf('COM_PAYCART_INVALID_POST_DATA', '$title must be required'));
			// @codeCoverageIgnoreEnd
		}
		
		$category = $this->_save($post);
		// Id required in View
		// IMP:: don't put category_id in property name otherwise it will not work 
		$this->getModel()->setState('id', $category->getId());
		
		return  true;
	}
	
	/**
	 * override it due to get all uploaded files 
	 */
	public function _save(array $data, $itemId=null, $type=null)
	{
		//Get All files from paycart form
		$data['_uploaded_files'] = $this->input->files->get('paycart_form', false);	
		
		return parent::_save($data, $itemId, $type);
	}	

	/**
	 * Ajax task : Delete image attached to productCategory
	 */
	public function deleteImage()
	{
		$id = $this->input->get('productCategory_id',0);
		$instance  = PaycartProductcategory::getInstance($id);
				 
		$ret = $instance->deleteImage($instance->getCoverMedia(false));
		
		$view = $this->getView();
		if($ret){
			$instance->save();
			$view->assign('success', true);
		}
		else{
			$view->assign('success', false);
		}
	
		return true;
	}
}