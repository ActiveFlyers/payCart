<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * category Html View
 * @author rimjhim
 */
require_once dirname(__FILE__).'/view.php';

class PaycartSiteViewProductcategory extends PaycartSiteBaseViewProductcategory
{	
	protected $auto_generate_metadata = false;
	
	/**
	 * Display categoies and product related to the current category
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::display()
	 */
	public function display($tpl = NULL)
	{			
		$categoryId	     = $this->input->get('productcategory_id', Paycart::PRODUCTCATEGORY_ROOT_ID); 
		$categoryFilters = array();
		$productFilters	 = array();
		
		$categoryFilters = array('published' => 1, 'parent_id'=>$categoryId);
		$productFilters  = array('published' => 1, 'productcategory_id' => $categoryId);
		
		//meta details
		$category 		 = PaycartProductcategory::getInstance($categoryId);
		$metaTitle       = $category->getMetadataTitle();
		$metaDescription = $category->getMetadataDescription();
		$metaKeywords    = $category->getMetadataKeywords();
		
		Rb_HelperJoomla::addDocumentMetadata($metaTitle,$metaKeywords,$metaDescription);
		$this->assign('products', PaycartFactory::getModel('product')->loadRecords($productFilters));	
		$this->assign('categories',$this->getModel()->loadRecords($categoryFilters));
		return true;
	}
}
