<?php 
/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		support+paycart@readybytes.in
 * @author      rimjhim
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Base Nested Table
 * @author rimjhim
 */
class PaycartTableNested extends JTableNested
{
	/**
	 * Object constructor to set table and key fields.  In most cases this will
	 * be overridden by child classes to explicitly set the table and key fields
	 * for a particular database table.
	 *
	 * @param   string           $table  Name of the table to model.
	 * @param   mixed            $key    Name of the primary key field in the table or array of field names that compose the primary key.
	 * @param   JDatabaseDriver  $db     JDatabaseDriver object
	 */
	public function __construct($table, $key, $db = null)
	{
		if($db===null){
			$db	= PaycartFactory::getDBO();
		}
		
		return parent::__construct($table, $key, $db);
	}
	
	public function store($updateNulls = false)
	{
		// Ordering fix
		$k = $this->_tbl_key;
		$columns = array_keys($this->getProperties());

		$now = new Rb_Date();
		
		// It must be required when migration is running from any subscription system to payplans system 
		// and we need to insert manually created and modified date. 
		// if a new record, handle created date
		if(!($this->$k) && in_array('created_date', $columns)){
			$this->created_date = $now->toSql();
		}
	
		//handle modified date
		if(in_array('modified_date', $columns)){
			$this->modified_date = $now->toSql();
		}
		return parent::store($updateNulls);
	}
	
	public function boolean($columnName, $value, $switch)
	{
		//check if column exist
		$columnName		= strtolower($columnName);
		if(($oldValue=$this->get($columnName, null)) === null)
		{
			$this->setError(sprintf("COLUMN %S DOES NOT EXIST IN TABLE %S",$columnName, $this->getName()));
			return false;
		}

		//figure do we need switch
		if($switch === false)
			$this->set($columnName, $value);
		else
			$this->set($columnName, $oldValue ? 0 : 1);

		//now save
		$properties = $this->getProperties();
		if($this->save($properties)===false)
		{
			$this->setError( $this->_db->stderr() );
			return false;
		}

		//reload new values
		$this->load($this->get('id'));
		return true;
	}
}
