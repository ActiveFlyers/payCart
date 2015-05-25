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
		
		$post['title'] = trim($post['title']);
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
		$data['_uploaded_files'] = $this->input->files->get($this->getControlNamePrefix(), false);	
		
		$entity = parent::_save($data, $itemId, $type);
		
		//Issue #415 required to add the current category at end 
		//otherwise redirection will be on the last category in array 
		//because itemid will be fetched from table object of last saved record
		if($entity instanceof Rb_Lib && $entity->getId()){
			$this->getModel()->getTable()->load($entity->getId());
		}
		
		return $entity;
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
	
	/**
	 * Overriding it because in tablenested children will automatically get deleted 
	 * when try to delete parent category
	 * So here we check if record exist in table then only try to delete the record
	 *  
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Controller::_remove()
	 */
	function _remove($itemId=null, $userId=null)
	{
		//get the model
		$model 		= $this->getModel();
	    if($itemId === null || $itemId === 0){
			$itemId = $model->getId();
		}
		
		//check if record exists
		if($model->getTable()->load($itemId)){
			$item = Rb_Lib::getInstance($this->_component->getPrefixClass(), $this->getName(), $itemId, null)
					->delete();
	
			if(!$item){
				//we need to set error message
				$this->setError($model->getError());
				return false;
			}
		}
		
		return true;
	}
	
/**
	 * 
	 * Ajax Task
	 * @return html which contain all products options
	 */
	public function getProducts() 
	{
		$category_id	=	$this->input->get('category_id', 0, 'array');
	
		$default_selected_product_id	=	$this->input->get('default_product', array(), 'Array');

		// get raw strin without any filter
		$selector	=	$this->input->get('product_selector', NULL, 'RAW');
		
		$ajax_response = PaycartFactory::getAjaxResponse();
		
		if(!$selector) {
			$ajax_response->addScriptCall
					(	'console.log', 
						Array('message' 		=> 	JText::_('product selector is not available here'),
							  'message_type'	=>	Paycart::MESSAGE_TYPE_ERROR )
					);
			return false;
		}
	
		if(!$category_id) {
			PaycartFactory::getAjaxResponse()->addScriptCall('paycart.grouprule.product.updateHtml', Array('product_selector' => $selector, 'product_option_html' => ''));
			return false;
		}	
		
		// limit must be cleaned other wise only specific number of record will fetch
		if(is_array($category_id)){
			$filter['productcategory_id'] = array(array('IN', '('.implode(',', $category_id).')')); 
		}
		else{
			$filter['productcategory_id'] = $category_id;
		}
		
		$products = PaycartFactory::getModel('product')->loadRecords($filter, Array('limit'));
				
		$category_products = array();		
		foreach ($products as $product_id => $product_detail) {
			if(!isset($category_products[$product_detail->productcategory_id])){
				$category_products[$product_detail->productcategory_id] = '';
			}
			
			$selected = '';
			if (in_array($product_id, $default_selected_product_id)) {
				$selected = 'selected="selected"';
			}
			$category_products[$product_detail->productcategory_id] .= "<option value='{$product_detail->product_id}'  $selected > {$product_detail->title}($product_detail->alias) </option>";
		}
		
		$html = '';
		if(count($category_products) >= 1){
			$formatter = PaycartFactory::getHelper('format');
			foreach($category_products as $category_id => $productshtml){
				$html .= $productshtml;
			}
		}
		
		
		PaycartFactory::getAjaxResponse()->addScriptCall('paycart.grouprule.product.updateHtml', Array('product_selector' => $selector, 'product_option_html' => $html));
		
		return false;
	}
}
