<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * PayCart Config Model
 * @author manish
 *
 */
class PaycartModelConfig extends PaycartModel
{
	function save($data =array(), $pk = NULL, $new = false)
	{
		$available = array_keys($data);
		$db   = PaycartFactory::getDbo();
		
		$record   = $this->loadRecords();
		$existing = array_keys($record);
		$diff 	  = array_intersect($existing, $available);
		$tmp  	  = '';
		
		//update existing records
		$update = 'UPDATE `#__paycart_config` SET `value` = CASE `key` ';
		foreach($diff as $key){
				if(is_array($data[$key])){
					$data[$key] = json_encode($data[$key]);
				}
				
				$tmp .= "WHEN '".$key."' THEN ".$db->quote($data[$key]);
				unset($data[$key]);
		}	

		if(!empty($tmp)){
			$tmp = rtrim($tmp,',');
			$update .= $tmp.' END WHERE `key` IN ("'.implode('","', $diff).'")';
			$db->setQuery($update)->query();
		}
		
		if(empty($data)){
			return true;
		}
		
		//insert new records
		$insert 	= 'INSERT INTO `#__paycart_config` (`key`, `value`) VALUES ';
		$queryValue = array();
		foreach ($data as $key => $value){
			if(is_array($value)){
				$value = json_encode($value);
			}
			$queryValue[] = "(".$db->quote($key).",". $db->quote($value).")";
		}
		
		$insert .= implode(",", $queryValue);
		return $db->setQuery($insert)->query();
	
	}
	
	public function loadRecords(Array $queryFilters=array(), Array $queryClean = array(), $emptyRecord=false, $indexedby = null)
	{
		$query =  new Rb_Query();
		
		return $query->select('*')
					 ->from('`#__paycart_config`')
					 ->dbLoadQuery()
			   		 ->loadObjectList('key');
	}
}

class PaycartModelformConfig extends PaycartModelform {}

class PaycartTableConfig extends PaycartTable{}
