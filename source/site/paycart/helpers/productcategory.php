<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Category Helper
 */
class PaycartHelperProductCategory extends PaycartHelper
{	
	/**
	 * @return all available category array('category_id'=>'Array of category stuff')
	 */
	public function getCategory($reset = false)
	{
		static $result ;
		if ($result && !$reset ) {
			return $result;
		}
		
		$model = PaycartFactory::getModel('productcategory');
		// Should be sorted according to 'title' so need to write query with "order by"
		$model->clearQuery();  
		$query = $model->getQuery()->where('published = 1')->clear('order')->clear('limit')->order('lft');
		
		$result = $model->loadRecords();
		 
		return $result;
	}
	
	/**
     * generate category tree 
     * i.e. sequence of categories coming while traversing for a category from root
     */
	public function generateCategoryTree()
	{
		$categories = PaycartFactory::getModel('productcategory')->loadRecords();
		
		foreach ($categories as $data){
			$categoryId = $data->productcategory_id;
			$temp       = $categoryId;
			$tree[$categoryId][] = $temp;
			
			while($temp != Paycart::PRODUCTCATEGORY_ROOT_ID){
				$tree[$categoryId][] = $categories[$temp]->parent_id;
				$temp = $categories[$temp]->parent_id;
			}
			
			$instance = PaycartProductcategory::getInstance($categoryId,$data);
			$instance->setTree(array_reverse($tree[$categoryId]))->save();
		}
	}
}
