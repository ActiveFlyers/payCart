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
 * Country Model
 * @author manish
 *
 */
class PaycartModelCountry extends PaycartModelLang
{
	public $filterMatchOpeartor = array(
										'title' 	=> array('LIKE'),
										'published' => array('='),
									);
									
	public function delete($pk = null)
	{
		//get Primary key
		if(!$pk ){
			$pk = $this->getId();
		 }
		 
		// we will delete all dependent information
		// so delete all state of this country
		$query = new Rb_Query();
		$db    = PaycartFactory::getDbo();
		
		$stateIds = $query->select('state_id')
						  ->from('#__paycart_state')
						  ->where("country_id = ". $db->quote($pk))
						  ->dbLoadQuery()
						  ->loadColumn();
						  
		$model = PaycartFactory::getModel('state');
		foreach ($stateIds as $stateId){
			if(!$model->delete($stateId)){
				// @PCTODO:: Specified error 
				return false;
			}
		}
		
		// now we will delete country
		if (!parent::delete($pk)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see/plugins/system/rbsl/rb/rb/Rb_Model::save()
	 */
	function save($data, $pk=null, $new=false)
    {
    	$new = $this->getTable()->load($pk)? false : true;
		
    	$id  = parent::save($data, $pk, $new);
    	
		if(!$id){
			return false;
		}
		
		return $id; 
    }
    
    /**
     * (non-PHPdoc)
     * @see /plugins/system/rbsl/rb/rb/Rb_Model::loadRecords()
     */
    public function loadRecords(Array $queryFilters=array(), Array $queryClean = array(), $emptyRecord=false, $indexedby = null)
    {
    	// load all country table records 
    	$records = parent::loadRecords($queryFilters, $queryClean, $emptyRecord, $indexedby);   	
    	return $records;
    	
    }
    
    /**
     * (non-PHPdoc)
     * @see components/com_paycart/paycart/base/PaycartModelLang::_buildQueryFilter()
     * 
     * Overridden it so that if title filter applied then extra condition can be appended
     */
 	protected function _buildQueryFilter(Rb_Query &$query, $key, $value, $tblAlias='`tbl`.')
    {
    	// Only add filter if we are working on bulk reocrds
		if($this->getId()){
			return $this;
		}
		
    	Rb_Error::assert(isset($this->filterMatchOpeartor[$key]), "OPERATOR FOR $key IS NOT AVAILABLE FOR FILTER");
    	Rb_Error::assert(is_array($value), JText::_('PLG_SYSTEM_RBSL_VALUE_FOR_FILTERS_MUST_BE_AN_ARRAY'));

    	$cloneOP    = $this->filterMatchOpeartor[$key];
    	$cloneValue = $value;
    	
    	while(!empty($cloneValue) && !empty($cloneOP)){
    		$op  = array_shift($cloneOP);
    		$val = array_shift($cloneValue);

			// discard empty values
    		if(!isset($val) || '' == trim($val))
    			continue;

    		if($key == 'title'){
    			$query->where("( `$key` $op '%{$val}%' || $tblAlias`country_id` $op '{$val}' || `isocode2` $op '{$val}' )");
    			continue;
    		}
    			
    		if(strtoupper($op) == 'LIKE'){
	    	  	$query->where("$tblAlias`$key` $op '%{$val}%'");
				continue;
	    	}

    		$query->where("$tblAlias`$key` $op '$val'");
	    		
    	}
    }
    
    
}

class PaycartModelformCountry extends PaycartModelform 
{}

/**
 * 
 * Country Table
 * @author manish
 *
 */
class PaycartTableCountry extends PaycartTable
{
	/**
	 * Issue :: We are using string as primary key. Joomla Compare string with zero with ( == )so we have overwrite this method   
	 * @see /libraries/joomla/table/JTable::store()
	 */
	public function store($updateNulls = false)
	{
		$k = $this->_tbl_key;
		if (!empty($this->asset_id))
		{
			$currentAssetId = $this->asset_id;
		}

		// here we are using (===)
		if (0 === $this->$k)
		{
			$this->$k = null;
		}

		// The asset id field is managed privately by this class.
		if ($this->_trackAssets)
		{
			unset($this->asset_id);
		}

		// If a primary key exists update the object, otherwise insert it.
		if ($this->$k)
		{
			$this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
		}
		else
		{
			$this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
		}

		// If the table is not set to track assets return true.
		if (!$this->_trackAssets)
		{
			return true;
		}

		if ($this->_locked)
		{
			$this->_unlock();
		}

		/*
		 * Asset Tracking
		 */

		$parentId = $this->_getAssetParentId();
		$name = $this->_getAssetName();
		$title = $this->_getAssetTitle();

		$asset = self::getInstance('Asset', 'JTable', array('dbo' => $this->getDbo()));
		$asset->loadByName($name);

		// Re-inject the asset id.
		$this->asset_id = $asset->id;

		// Check for an error.
		$error = $asset->getError();
		if ($error)
		{
			$this->setError($error);
			return false;
		}

		// Specify how a new or moved node asset is inserted into the tree.
		if (empty($this->asset_id) || $asset->parent_id != $parentId)
		{
			$asset->setLocation($parentId, 'last-child');
		}

		// Prepare the asset to be stored.
		$asset->parent_id = $parentId;
		$asset->name = $name;
		$asset->title = $title;

		if ($this->_rules instanceof JAccessRules)
		{
			$asset->rules = (string) $this->_rules;
		}

		if (!$asset->check() || !$asset->store($updateNulls))
		{
			$this->setError($asset->getError());
			return false;
		}

		// Create an asset_id or heal one that is corrupted.
		if (empty($this->asset_id) || ($currentAssetId != $this->asset_id && !empty($this->asset_id)))
		{
			// Update the asset_id field in this table.
			$this->asset_id = (int) $asset->id;

			$query = $this->_db->getQuery(true)
				->update($this->_db->quoteName($this->_tbl))
				->set('asset_id = ' . (int) $this->asset_id)
				->where($this->_db->quoteName($k) . ' = ' . (int) $this->$k);
			$this->_db->setQuery($query);

			$this->_db->execute();
		}

		return true;
	}
}

/**
 * 
 * Language specific Table
 * @author manish
 *
 */
class PaycartTableCountryLang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_country_lang', $tblPrimaryKey='country_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}	
}