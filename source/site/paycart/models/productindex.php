<?php

/**
* @copyright	Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim jain 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Productindex Model
 * @author rimjhim jain
 *
 */

class PaycartModelProductIndex extends PaycartModel
{
	function buildWhereCondition($keyword)
	{
		static $whereCondition = null;
		
		//if already condition is build then return
		if(!empty($whereCondition)){
			return $whereCondition;
		}
		
		// Check mysql configuration for value 'ft_min_word_len'
		//if length of string is less than 4 (i.e. mysql default minimum length of full text search word )
		//then prepare a query using LIKE		
		$row = PaycartFactory::getDbo()->setQuery("SHOW variables LIKE 'ft_min_word_len'")->loadRow();
		$minWordLength = $row[1];
		
		$check     = true;
		$condition = array(); 
		$words     = explode(' ', $keyword);
		foreach ($words as $word){
			$condition[] = "content LIKE '% ".$word." %'";
			if($check && strlen($word) < $minWordLength){
				$check = false;
			}
		}
		
		if(!$check){
			$whereCondition = ' ( '.implode(' OR ', $condition).' ) ';
		}else{
			$whereCondition = "match(content) against('".$keyword."' IN BOOLEAN MODE)";	
		}
		
		return $whereCondition;
	}
	
	/**
	 * Delete the record with the given id
     *
     * Overridden this function bcoz the parent function is tried to load the record
     * and if record doesn't exist then error will occur 
     *
	 * @see plugins/system/rbsl/rb/rb/Rb_Model::delete()
	 */
	function delete($productId = null)
	{
		if(!$productId){
			return true;
		}
		
		$query 	  = new Rb_Query();
		return  $query->delete()
					  ->from('#__paycart_product_index')
					  ->where('product_id = '.$productId)
					  ->dbLoadQuery()
					  ->execute();
	}
}

/**
 * 
 * Productindex Table
 * @author rimjhim jain
 *
 */

class PaycartTableProductIndex extends PaycartTable
{
	public function __construct($tblFullName='#__paycart_product_index', $tblPrimaryKey='product_id', $db=null)
	{
		return parent::__construct('#__paycart_product_index', $tblPrimaryKey, $db);
	}
}
