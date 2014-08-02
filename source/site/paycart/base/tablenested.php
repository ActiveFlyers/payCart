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
	
	public function rb_save($new = false)
	{
		// Specify where to insert the new node.
		$this->setLocation($this->parent_id, 'last-child');		
		return $this->store();
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
		if($this->rb_save()===false)
		{
			$this->setError( $this->_db->stderr() );
			return false;
		}

		//reload new values
		$this->load($this->get('id'));
		return true;
	}
}
