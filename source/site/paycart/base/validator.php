<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		mManishTrivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

class PaycartValidator extends Rb_Validator
{
	public function validateProductAlias($value, $params = array(), $data = array())
	{
		if(empty($value)){
			return true;
		}
		
		if(!$this->validateSlug($value, $params, $data)){
			return false;
		}
		
		$alias 	= JApplicationHelper::stringURLSafe($value);
		$id 	= isset($data['product_id']) ? $data['product_id'] : 0; 
		$result = PaycartTableProductLang::getRecordsOfAlias($alias, $id);
		
		return !in_array($value, $result);
	}
	
	public function validateProductCategoryAlias($value, $params = array(), $data = array())
	{
		if(empty($value)){
			return true;
		}
		
		if(!isset($data['parent_id']) || !$data['parent_id']){
			return false;
		}
		
		if(!$this->validateSlug($value, $params, $data)){
			return false;
		}
		
		$alias 		= JApplicationHelper::stringURLSafe($value);
		$id 		= isset($data['productcategory_id']) ? $data['productcategory_id'] : 0;
			
		$parent_id 	= $data['parent_id'];
		$result 	= PaycartTableProductcategorylang::getRecordsOfAlias($alias, $parent_id, $id);
		
		return !in_array($value, $result);
	}
}
