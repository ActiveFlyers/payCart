<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * category Html View
 * @author rimjhim
 */
require_once dirname(__FILE__).'/view.php';

class PaycartSiteHtmlViewProductcategory extends PaycartSiteBaseViewProductcategory
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
		$searchWord		 = $this->input->get('query',null,'STRING'); 
		$categoryFilters = array();
		$productFilters	 = array();
		
		$categoryFilters = array('published' => 1, 'parent_id'=>$categoryId);
		$productFilters  = array('published' => 1, 'productcategory_id' => $categoryId , 'visible' => 1 );
		
		//meta details
		$category 		 = PaycartProductcategory::getInstance($categoryId);
		$metaTitle       = $category->getMetadataTitle();
		$metaDescription = $category->getMetadataDescription();
		$metaKeywords    = $category->getMetadataKeywords();
		
		Rb_HelperJoomla::addDocumentMetadata($metaTitle,$metaKeywords,$metaDescription);
		$products = PaycartFactory::getModel('product')->loadRecords($productFilters,array(),false,'product_id');
	
		// 1. If products are not there in the current category
		// 2. If root category is selected and searching is not being taking place 
		//    then show productcategory layout  
		if((count($products) == 0 ||($categoryId == Paycart::PRODUCTCATEGORY_ROOT_ID)) && empty($searchWord)){	
			$result    = new stdClass();
			$formatter = PaycartFactory::getHelper('format');
			foreach ($products as $productId => $data){
				$instance = PaycartProduct::getInstance($productId);
				$product  = (object)$instance->toArray();
				$product->price 			   = $formatter->amount($instance->getPrice(), true);
				$result->$productId 		   = $product;
				$result->$productId->inStock   = PaycartFactory::getHelper('product')->isProductInStock($productId);
				$result->$productId->media     = $instance->getCoverMedia();
			}
			
			$this->assign('products',$result);
			$this->assign('categories',$this->getModel()->loadRecords($categoryFilters));
			return true;
		}

		// Display filters if any product exist in the selected category, if no category selected and 
		// if searching is there 
		if( count($products) > 0 || !empty($searchWord)){			
			$filters = $this->input->get('filters',array(),'ARRAY');
			//if no filter applied yet and no search applied,then send current category as a filter to load result
			if(empty($filters)){
				$filters = array('core' => array('category' => $categoryId ));
			}
			$this->assign('filters',$filters);
		}
		
		$this->assign('searchWord',$searchWord);
		$this->setTpl('filter');
		return true;
	}
}
