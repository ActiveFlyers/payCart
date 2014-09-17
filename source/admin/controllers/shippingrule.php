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
 * Admin Controller for Shipping Rules
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartAdminControllerShippingrule extends PaycartController 
{	
	public function getProcessorConfig()
	{
		return true;
	}
	
	/**
	 * override it due to format packaging weight
	 */
	public function _save(array $data, $itemId=null, $type=null)
	{
		//format packaging weight before saving
		if(isset($data['packaging_weight'])){
			$data['packaging_weight'] = PaycartFactory::getHelper('format')->convertWeight($data['packaging_weight'],PaycartFactory::getConfig()->get('catalogue_weight_unit'));
		} 
		
		return parent::_save($data, $itemId, $type);
	}
}