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
 * Productcategory Model
 * @author manish
 *
 */
class PaycartModelProductcategory extends PaycartModel
{
	/**
	 * Required to override save function becuase the corresponding table is related the PaycartTableNested
	 * 
	 * Save given data for the given record
	 * @param array $data : date to be saved
	 * @param int/string $pk : the record ID, if 0 given data will be saved as new record
	 * @param boolean $new : is a new record (then we will not load it from table) 
	 * 
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Model::save()
	 */
	function save($data, $pk=null, $new=false)
	{
		if(isset($data)===false || count($data)<=0)
		{
			$this->setError(Rb_Text::_('PLG_SYSTEM_RBSL_NO_DATA_TO_SAVE'));
			return false;
		}
		
		//try to calculate automatically
		if($pk === null){
			$pk = (int) $this->getId();
		}

		//also validate via model
		if($this->validate($data, $pk)===false){
			return false;
		}
		
		$table = $this->getTable();
		if(!$table){
			$this->setError(Rb_Text::_('PLG_SYSTEM_RBSL_TABLE_DOES_NOT_EXIST'));
			return false;
		}
		
		// If table object was loaded by some code previously
		// then it can overwrite the previous record
		// So we must ensure that either PK is set to given value
		// Else it should be set to 0
		$table->reset(true);

		//it is a NOT a new record then we MUST load the record
		//else this record does not exist
		if($pk && $table->load($pk)===false){
			$this->setError(Rb_Text::_('PLG_SYSTEM_RBSL_NOT_ABLE_TO_LOAD_ITEM'));
			return false;
		}
 
		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if ($table->parent_id != $data['parent_id'] || !$pk){
			// Specify where to insert the new node.
			$table->setLocation($data['parent_id'], 'last-child');
		}
		 
		// Bind and save the data.
		if ($table->bind($data) && $table->check() && $table->store()){
			 // We should return the record's ID rather then true false
			return $table->{$table->getKeyName()};
		}
	
		$this->setError($table->getError());
		return false;
	}
}

/**
 * 
 * Productcategory Lang Model
 * @author manish
 *
 */
class PaycartModellangProductcategory extends PaycartModel
{
	/**
	 * 
	 * Check if the current alias is valid else create new
	 * @param sting $alias 		: alias to be checked for validity
	 * @param integer $parentId : parent id of the current category
	 * @param integer $pk 		: Except checking on this id while validating alias
	 * @param String $style		: The style (default|dash).
	 *  							{ default	: "Title" becomes "Title (2)"
	 * 								  dash		:    "Title" becomes "Title-2" }
	 *
	 * @return string The valid string
	 */
	public function getValidAlias($alias, $parentId, $pk, $style='dash')
	{	
		if (!$alias) {
			throw InvalidArgumentException(Rb_Text::_('COM_PAYCART_ALIAS_REQUIRED'));
		}
		
		$query 	= new Rb_Query();		
		if($pk){
			$query->where("cl.`productcategory_lang_id` <>".$pk);
		}
		
		$helper = new PaycartHelper();
		$alias  = $helper->sluggify($alias);//Sluggify the input string
		
		$result = $query->select('cl.`productcategory_lang_id`')
						->where("cl.`alias` = '".$alias."'")
						->where('cl.`productcategory_id`= c.`productcategory_id`')
			  			->from($this->getTable()->get('_tbl'). ' as cl')
			  			->join('inner', '`#__paycart_productcategory` as c on c.`parent_id` = '.$parentId)
			  			->dbLoadQuery()->loadResult();
			
		if(!$result){
			return $alias;
		}
		
		// if Value already have '-'(dash) with numeric-data then remove numeric-data 
		$string = $alias;
		if (preg_match('#-(\d+)$#', $string, $matches)) {
			$string = preg_replace('#-(\d+)$#', sprintf('-', ''), $string);
		}
		
		$query 	= new Rb_Query();
		$result = $query->select('alias')
						->where("`alias` LIKE '$string%'")
			  			->from($this->getTable()->get('_tbl'))
			  			->dbLoadQuery()->loadcolumn();

		// build new column value
		while (in_array($alias, $result)) {
			$alias = JString::increment($alias, $style);
		}
		
		return $alias;
	}
}

class PaycartModelformProductCategory extends PaycartModelform { }