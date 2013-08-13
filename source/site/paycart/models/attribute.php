<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Product Custom Attribute  Model
 */
class PaycartModelAttribute extends PaycartModel
{

}

class PaycartModelformAttribute extends PaycartModelform 
{
	 
	// Load specific atrribute type configuration from th
	function preprocessForm($form, $data)
	{
		if($data['type']) {
			// @PCTODO :: Path should be injected from outside.
			// All Attribute config will be availble with form object { params->attribute_config}
			$form->loadFile(PAYCART_PATH_CUSTOM_ATTRIBUTES.'/'.$data['type'].'.xml', false);
		}
		return parent::preprocessForm($form, $data);
	} 
}