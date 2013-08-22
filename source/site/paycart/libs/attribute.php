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
 * Attribute Lib
 */
class PaycartAttribute extends PaycartLib
{
	protected $attribute_id	 =	0; 
	protected $title 		 =	null;
	protected $published	 =	1;
	protected $visible		 =	1;
	protected $searchable	 =	0;
	protected $type 		 =	0;
	protected $default		 =  0;
	protected $params 		 =	null;
	protected $created_by	 =	0;
	protected $created_date  =	'';	
	protected $modified_date =	''; 	
	
	public function reset() 
	{		
		$this->attribute_id	 =	0; 
		$this->title 		 =	null;
		$this->published	 =	1;
		$this->visible		 =	1;
		$this->searchable	 =	0;
		$this->type 		 =	0;
		$this->default 		 =	0;
		$this->params 		 =	new Rb_Registry();
		$this->created_by	 =	0;
		$this->created_date  =	Rb_Date::getInstance();	
		$this->modified_date =	Rb_Date::getInstance(); 	
		
		return $this;
	}
	
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('attribute', $id, $data);
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
		
		return parent::save();
	}
	
	
	public function getTitle()
	{
		return $this->title;
	}
}
