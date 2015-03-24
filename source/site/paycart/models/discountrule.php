<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Discountrule model
 * @author mManishTrivedi
 *
 */
class PaycartModelDiscountrule extends PaycartModelLang
{
	public $filterMatchOpeartor = array(
										'title' 	=> array('LIKE'),
										'coupon'	=> array('LIKE'),
										'amount'	=> array('>=', '<='),
										'processor_classname'=> array('='),
										'published' => array('='),
									);

	
	/**
	 * 
	 * get data from Discountrule table  
	 * @param  $where
	 * @param  $orderBy
	 * 
	 * @return Array of stdclass
	 */
	public function getData($where, $orderBy)
	{
		//@PCTODO:: $record can be cached 
		
		// get new query object
		$query 	= 	PaycartFactory::getQuery();
		$table	=	$this->getTable();
		
		// build query
		$query->select('*')
			  ->from($table()->getTableName())
			  ->where($where)
			  ->order($orderBy);
			  
		try	{
			$records =	$this->_db->setQuery($query)->loadObjectList($table->getKeyName());
		}
		catch (RuntimeException $e) {
			//@PCTODO::proper-message propagates
			Rb_Error::raiseError(500, $e->getMessage());
		}
		
		return $records;
			  
	}
	
	public function getGroups($pk, $type)
	{
		$query = new Rb_Query();
		$query->select('group_id')
				->from('#__paycart_discountrule_x_group')
				->where('`type` = "'.$type.'"')
				->where('`discountrule_id` = '.$pk);
				
		return   $query->dbLoadQuery()->loadColumn();
	}
	
	public function saveGroups($pk, $type, $values)
	{
		// remove empty values [0, null, false, ''] 
		$values = array_filter($values);
		 
		$query = new Rb_Query();
		$query->delete()
				->from('#__paycart_discountrule_x_group')
				->where('`type` = "'.$type.'"')
				->where('`discountrule_id` = '.$pk);
				
		if(!$query->dbLoadQuery()->execute()){
			throw new Exception('Error in deleting Taxrule Group Mapping');
		}
		
		if(empty($values)){
			return $this;
		}
		
		$sql = 'INSERT INTO `#__paycart_discountrule_x_group` (`discountrule_id`, `group_id`, `type`) VALUES';		
		
		$insert = array();
		foreach($values as $value){
			if(empty($value)){
				continue;
			}
			
			$insert[] = '('.$pk.', '.$value.', "'.$type.'")';
		}
		$sql .= implode(', ', $insert);
		
		$db = PaycartFactory::getDbo();
		$db->setQuery($sql);
		if(!$db->execute()){
			throw new Exception('Error in inserting Discountrule Group Mapping');
		}
		
		return $this;
	}
	
	public function delete($pk=null)
	{
		//try to calculate automatically
		if($pk === null){
			$pk = (int) $this->getId();
		}
		
		if(!$pk){
			$this->setError('Invalid itemid to delete for model : '.$this->getName());
			return false;
		}
		
		$query = new Rb_Query();
		$query->delete()
				->from('#__paycart_discountrule_x_group')
				->where('`discountrule_id` = '.$pk);
				
		if(!$query->dbLoadQuery()->execute()){
			$this->setError('Error in deleting Discountrule Group Mapping');
			return false;
		}
		
		return parent::delete($pk);
	}
}

class PaycartModelformDiscountrule extends PaycartModelform { }


class PaycartTableDiscountrule extends PaycartTable{ }

class PaycartTableDiscountruleLang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_discountrule_lang', $tblPrimaryKey='discountrule_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}