<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Base Group Rule
 * @author Gaurav Jain
 */
abstract class PaycartGrouprule
{
	/**
	 * @var Rb_Registry
	 */
	protected $params = null;
	
	public function __construct($params = array())
	{
		$this->params = new Rb_Registry();
		$this->params->loadArray($params);
	}
	
	abstract public function isApplicable($entity_id);
}