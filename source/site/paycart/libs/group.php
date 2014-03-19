<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Lib for Group
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartGroup extends PaycartLib 
{	
	protected $group_id		= 0;
	protected $type			= '';
	protected $title		= '';
	protected $description	= '';
	protected $published	= true;
	protected $ordering		= 0;
	
	/**
	 * @var Rb_Registry
	 */
	protected $params 		= null;	
	
	/**
	 * @var PaycartHelperGroup
	 */
	protected $_helper = null;
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_helper = PaycartFactory::getHelper('group');
	}
	
	/**
	 * @return PaycartGroup
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('group', $id, $bindData);
	}
	
	public function bind($data, $ignore=array())
	{
		if(is_array($data)){
			$data = (object) $data;	
		}

		// reset params, previous params should be replaced with new params
		if(isset($data->params)){
			$this->params = new Rb_Registry() ;
		}

		return parent::bind($data, $ignore);
	}
	
	public function reset() 
	{	
		$this->group_id 	= 0;
		$this->type 		= '';
		$this->title 		= '';
		$this->description 	= '';
		$this->published 	= 1;
		$this->ordering 	= 0;
		$this->params 		= new Rb_Registry();
		return $this;
	}
	
	public function getType()
	{
		return $this->type;
	}
}