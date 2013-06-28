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
	protected $name 		 =	null;
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
		$this->name 		 =	null;
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
}
