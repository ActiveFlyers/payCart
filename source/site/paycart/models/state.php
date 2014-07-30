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
 * State Model
 * @author manish
 *
 */
class PaycartModelState extends PaycartModelLang
{
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_Model::delete()
	 */
	public function delete($pk=null)
	{
		//get Primary key
		if(!$pk ){
			$pk = $this->getId();
		 }

		// now we will delete state
		if (!parent::delete($pk)) {
			return false;
		}
		
		return true;		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see/plugins/system/rbsl/rb/rb/Rb_Model::save()
	 */
	public function save($data, $pk=null, $new=false)
    {		
    	// get state id
    	$state_id = parent::save($data, $pk, $new);
    	
		if ( !$state_id ) {
			return false;
		}
		
		return $state_id;
    }
    
    /**
     * (non-PHPdoc)
     * @see /plugins/system/rbsl/rb/rb/Rb_Model::loadRecords()
     */
    public function loadRecords(Array $queryFilters=array(), Array $queryClean = array(), $emptyRecord=false, $indexedby = null)
    {
    	// load all state table records 
    	$records = parent::loadRecords($queryFilters, $queryClean, $emptyRecord, $indexedby);
    	return $records;
    }   
}

class PaycartModelformState extends PaycartModelform 
{
	//PCTODO:: Add option in form to add name
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$name = $this->_component->getNameCom().'.'.$this->getName();
		$form = $this->loadForm($name, $this->getName(), array('control' => $this->_component->getNameSmall().'_state_form', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		if ( is_array($data) && !empty($data)) {
			$form->bind($data);
		}

		return $form;
	}
}

/**
 * 
 * State Table
 * @author manish
 *
 */
class PaycartTableState extends PaycartTable{}

/**
 * 
 * Language specific Table
 * @author manish
 *
 */
class PaycartTableStateLang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_state_lang', $tblPrimaryKey='state_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}	
}