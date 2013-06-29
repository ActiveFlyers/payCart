<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Category Lib
 */
class PaycartCategory extends PaycartLib
{
	protected $category_id	 =	0; 
	protected $title 		 =	null;
	protected $alias 		 =	'';
	protected $description	 =	null;
	protected $published	 =	1;
	protected $parent 		 =	0;
	protected $cover_image	 =	null; 	
	protected $params 		 =	null;
	protected $created_by	 =	0;
	protected $created_date  =	'';	
	protected $modified_date =	''; 	
	
	public function reset() 
	{		
		$this->category_id	 =	0; 
		$this->title 		 =	null;
		$this->alias 		 =	'';
		$this->description	 =	null;
		$this->published	 =	1;
		$this->parent 		 =	0;
		$this->cover_image	 =	null; 	
		$this->params 		 =	new Rb_Registry();
		$this->created_by	 =	0;
		$this->created_date  =	Rb_Date::getInstance();	
		$this->modified_date =	Rb_Date::getInstance(); 	
		
		return $this;
	}
	
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('category', $id, $data);
	}	
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::save()
	 * Formating here before save content
	 */
	public function save()
	{
		if(!$this->created_by) {
			$this->created_by = Rb_Factory::getUser()->get('id');
		}
		
		// generate unique alias if not exist
		$this->alias = $this->getUniqueAlias();
		
		return parent::save();
	}
	
	
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * Get unique object alias string.
	 *
	 * @param string $id The object id
	 * @param string $alias The object alias
	 *
	 * @return string The unique object alias string
	 */
	public function getUniqueAlias() 
	{
		// alias replace with title if alias name is empty
		if (empty($this->alias)) {
			$this->alias = $this->getTitle(); 
		}

		//Sluggify the input string
		$this->alias = PaycartHelper::sluggify($this->alias);
		
		// @IMP :: Here category_id is mandatory params  
		while ($this->isAliasExists($this->alias, $this->category_id)) {
			$this->alias = JString::increment($this->alias, 'dash');
		}
		
		return $this->alias;
	}
	
	/**
	 * Method to check if an alias already exists.
	 *
	 * @param string $alias The object alias
	 * @param string $id The category id, When you re-save existing alias then should not be generated new alais. 
	 *   
	 * @return string The object id if found, or 0
	 */
	public function isAliasExists($alias, $id = 0) 
	{
		$xid = intval($this->getModel()->translateAliasToID($alias));
		if ($xid && $xid != intval($id)) {
			return true;
		}
		return false;
	}
	
	
}
