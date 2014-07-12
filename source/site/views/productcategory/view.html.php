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
	/**
	 * Display categoies and product related to the current category
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::display()
	 */
	public function display($tpl = NULL)
	{			
		$categoryId	     = $this->input->get('productcategory_id',1); //PCTODO: use constant for root category
		$categoryFilters = array();
		$productFilters	 = array();
		
		$categoryFilters = array('status'=>'published', 'parent_id'=>$categoryId);
		$productFilters  = array('status'=>'published', 'productcategory_id' => $categoryId);
		
		$this->assign('products', PaycartFactory::getModel('product')->loadRecords($productFilters));	
		$this->assign('categories',$this->getModel()->loadRecords($categoryFilters));
		return true;
	}
}
