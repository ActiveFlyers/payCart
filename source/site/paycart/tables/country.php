<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		mManish Trivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

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
class PaycartTableLangCountry extends PaycartTable
{
	function __construct($tblFullName='#__paycart_country_lang', $tblPrimaryKey='country_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}	
}