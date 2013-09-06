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
class PaycartAttributeValue extends PaycartLib
{
	// Table field
	protected $attributevalue_id ;
	protected $attribute_id	 =	0; 
	protected $product_id	 =	0;
	protected $value	 	 =	0;
	protected $order		 =	0;
	protected $_attribute	 =	0;
	
	
	public function reset() 
	{		
		$this->attributevalue_id	= 0;
		$this->attribute_id			= 0; 
		$this->product_id 			= 0;
		$this->value	 			= 0;
		$this->order				= 0;
		$this->_attribute			= null;
		
		return $this;
	}
	
	public static function getInstance($id = 0, $data = null, $cached = false, $dummy2 = null)
	{
		return parent::getInstance('attributevalue', $id, $data);
	}	
	
	public function bind($data, $ignore=Array()) 
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		
		parent::bind($data, $ignore);
		
		if($this->attribute_id) {
			$this->_attribute = PaycartAttribute::getInstance($this->attribute_id);
		}
		// IMP :: Attribute value should be formate here
		// PCTODO:: break into function
		if(is_a($this->_attribute, 'PaycartAttribute')) {
			$this->value = $this->_attribute->formatValue($this->value);
		}
		
		return $this;
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function getOrder()
	{
		return $this->order;
	}	
}
