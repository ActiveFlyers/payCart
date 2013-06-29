<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
* @author 		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Category Helper
 */
class PaycartHelperCategory extends PaycartHelper
{	
	/**
	 * @return all availble category array('category_id'=>'Array of category stuff')
	 */
	public static function getCategory($reset = false)
	{
		static $result ;
		if ($result && !$reset ) {
			return $result;
		}
		
		$model = PaycartFactory::getInstance('category', 'Model');
		// Should be sorted according to 'title' so need to write query with "order by"
		$model->clearQuery();  
		$query = $model->getQuery()->order('title');
		
		$result = $model->loadRecords();
		 
		return $result;
	}
}
