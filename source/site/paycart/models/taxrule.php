<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Taxrule Model
 * @author rimjhim
 */

class PaycartModelTaxrule extends PaycartModelLang
{
	public $filterMatchOpeartor = array(
										'title' 	=> array('LIKE'),
										'processor_classname'=> array('='),
										'published' => array('='),
									);
	
	/**
	 * Filter applicable rules from applicable rules 
	 * @param array $ruleIds
	 * @param string $applyOn
	 * @param string $orderBy
	 */
	function filterApplicableRules($ruleIds, $applyOn, $orderBy)
	{
	 	$condition = '`taxrule_id IN('.array_values($ruleIds).')` AND '.
	 				 '`published` = 1 AND '.
                     '`apply_on` LIKE '."'$applyOn'" ;
	 	
	 	$query     = new Rb_Query();
	 	
	 	return $query->select('*')
		 		     ->from('#__paycart_taxrule')
		 		     ->where($conditions)
		 		     ->order($orderBy.' DESC ')
		 		     ->dbLoadQuery()
		 		     ->loadObjectList($this->getTable()->getKeyName()); 	
	 	
	}
	
	public function getGroups($pk, $type)
	{
		$query = new Rb_Query();
		$query->select('group_id')
				->from('#__paycart_taxrule_x_group')
				->where('`type` = "'.$type.'"')
				->where('`taxrule_id` = '.$pk);
				
		return   $query->dbLoadQuery()->loadColumn();
	}
	
	public function saveGroups($pk, $type, $values)
	{
		// remove empty values [0, null, false, '']
		$values = array_filter($values);
		 
		$query = new Rb_Query();
		$query->delete()
				->from('#__paycart_taxrule_x_group')
				->where('`type` = "'.$type.'"')
				->where('`taxrule_id` = '.$pk);
				
		if(!$query->dbLoadQuery()->execute()){
			throw new Exception('Error in deleting Taxrule Group Mapping');
		}
		
		if(empty($values)){
			return $this;
		}
		
		$sql = 'INSERT INTO `#__paycart_taxrule_x_group` (`taxrule_id`, `group_id`, `type`) VALUES';		
		
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
			throw new Exception('Error in inserting Taxrule Group Mapping');
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
				->from('#__paycart_taxrule_x_group')				
				->where('`taxrule_id` = '.$pk);
				
		if(!$query->dbLoadQuery()->execute()){
			$this->setError('Error in deleting Taxrule Group Mapping');
			return false;
		}
		
		return parent::delete($pk);
	}
}

/**
 * 
 * Model form for taxrule
 * @author rimjhim
 *
 */
class PaycartModelformTaxrule extends PaycartModelform{ }


/**
 * 
 * Taxrule Table
 * @author rimjhim
 *
 */
class PaycartTableTaxrule extends PaycartTable{ }

/**
 * 
 * Taxrule language Table
 * @author rimjhim
 *
 */
class PaycartTableTaxruleLang extends PaycartTable{
	
	function __construct($tblFullName='#__paycart_taxrule_lang', $tblPrimaryKey='taxrule_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}
