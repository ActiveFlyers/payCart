<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/
defined('_JEXEC') or die( 'Restricted access' );
/**
 * Admin Controller for Group
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartAdminControllerGroup extends PaycartController 
{
	public function addRule()
	{		
		return true;
	}
	
	public function _save(array $data, $itemId=null)
	{
		// if $pramas is not set then initialize
		if(!isset($data['params'])){
			$data['params'] = array();
		}
		
		// reaarange config
		array_values($data['params']);
		
		return parent::_save($data, $itemId);
	}
}