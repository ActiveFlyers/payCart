<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 */


/** 
 * Stub class of Base Lib
* @author Team Readybytes
 */
class PaycartLib extends Rb_Lib
{
	public	$_component	= PAYCART_COMPONENT_NAME;

	static public function getInstance($name, $id=0, $bindData=null, $dummy = null)
	{
		return parent::getInstance(PAYCART_COMPONENT_NAME, $name, $id, $bindData);
	}
	/**
	 * Stub function 
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::save()
	 */
	public function save()
	{
		return $this;
	}
}
