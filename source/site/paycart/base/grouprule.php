<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Base Group Rule
 * @author Gaurav Jain
 */
abstract class PaycartGrouprule
{
	/**
	 * @var Rb_Registry
	 */
	protected $config = null;
	
	public function __construct($config = array())
	{
		$this->config = new Rb_Registry();
		$this->config->loadArray($config);
	}
	
	abstract public function isApplicable($entity_id);
}