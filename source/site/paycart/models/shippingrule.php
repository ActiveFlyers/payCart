<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Model for Shipping Rules
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartModelShippingRule extends PaycartModelLang
{	
	public $filterMatchOpeartor = array(
										'title' 	=> array('LIKE'),
										'processor_classname'=> array('='),
										'published' => array('='),
									);
	
	public function getGroups($pk, $type)
	{
		$query = new Rb_Query();
		$query->select('group_id')
				->from('#__paycart_shippingrule_x_group')
				->where('`type` = "'.$type.'"')
				->where('`shippingrule_id` = '.$pk);
				
		return   $query->dbLoadQuery()->loadColumn();
	}
	
	public function saveGroups($pk, $type, $values)
	{
		// remove empty values [0, null, false, '']
		$values = array_filter($values);
		 
		$query = new Rb_Query();
		$query->delete()
				->from('#__paycart_shippingrule_x_group')
				->where('`type` = "'.$type.'"')
				->where('`shippingrule_id` = '.$pk);
				
		if(!$query->dbLoadQuery()->execute()){
			throw new Exception('Error in deleting Shippingrule Group Mapping');
		}
		
		if(empty($values)){
			return $this;
		}
		
		$sql = 'INSERT INTO `#__paycart_shippingrule_x_group` (`shippingrule_id`, `group_id`, `type`) VALUES';		
		
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
			throw new Exception('Error in inserting Shippingrule Group Mapping');
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
			  ->from('#__paycart_shippingrule_x_group')				
			  ->where('`shippingrule_id` = '.$pk);
				
		if(!$query->dbLoadQuery()->execute()){
			$this->setError('Error in deleting Shippingrule Group Mapping');
			return false;
		}
		
		return parent::delete($pk);
	}	
}


/**
 * Modelform for Shipping Rules
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartModelformShippingRule extends PaycartModelform 
{}

/**
 * Table for Shipping Rules
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartTableShippingRule extends PaycartTable 
{	
		
}

/**
 * 
 * Shippingrule language Table
 * @author rimjhim
 *
 */
class PaycartTableShippingRuleLang extends PaycartTable{
	
	function __construct($tblFullName='#__paycart_shippingrule_lang', $tblPrimaryKey='shippingrule_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}