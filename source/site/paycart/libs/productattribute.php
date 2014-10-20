<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Product Attribute Lib
 */

class PaycartProductAttribute extends PaycartLib
{
	// Table field
	protected $productattribute_id	 =	0; 
	protected $type 		 =	'';
	protected $code	 		 =	'';
	protected $filterable	 = 	0;
	protected $searchable	 =	0;
	protected $published	 =	1;
	protected $config		 =  null;
	protected $ordering		 =	0;
	
	//language specific data
	protected $title 		= '';
	protected $productattribute_lang_id = 0;
	protected $lang_code 	= '';
	
	//attribute specific data
	protected $_options = array();
	
	public function reset() 
	{	
		$this->productattribute_id	 =	0; 
		$this->type 		 =	'';
		$this->css_class	 =	'';
		$this->filterable	 = 	0;
		$this->searchable	 =	0;
		$this->published 	 =	1;
		$this->ordering		 =	0;
		$this->config		 =  new Rb_Registry();
		
		//language specific data
		$this->title 		= '';
		$this->productattribute_lang_id 	= 0;
		$this->lang_code  	= PaycartFactory::getCurrentLanguageCode();
		
		//attribute specific options
		$this->_options = array();
		
		return $this;
	}
	
	/**
	 * @return PaycartProductAttribute
	 */
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('productattribute', $id, $data);
	}	
	
	/**
	 * bind XML field on lib object
	 * 
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::bind()
	 */
	public function bind($data, $ignore=array()) 
	{
		if(is_object($data)){
			$data = (array) ($data);
		}

		// Parent bind	
		parent::bind($data, $ignore);
		
		//set options
		if( isset($data['_options'])) {
			$this->_options = $data['_options'];
		}
		
		//set attribute options
		$this->setOptions(PaycartAttribute::getInstance($this->type)->buildOptions($this, $data));
		
		return $this;
	}
	
	protected function _save($previousObject)
	{
		$id = parent::_save($previousObject);
		
		// if save fail
		if (!$id) { 
			return false;
		}
		
		// Correct the id, for new records required
		$this->setId($id);
		
		//save attribute config
		PaycartAttribute::getInstance($this->type)->saveOptions($this);
		
		return $id;
	}
	
	protected function _delete()
	{
		//delete option data
		if(!PaycartAttribute::getInstance($this->type)->deleteOptions($this->getId())){
			return false;
		}

		//delete all the attached values of this attribute for products 
		if(!$this->_deleteProductAttributeValues()){
			return false;
		}
		
		//Delete attribute
		return $this->getModel()->delete($this->getId()); 
	}	
	
	protected function _deleteProductAttributeValues()
	{
		return PaycartFactory::getModel('productattributevalue')->deleteMany(array('productattribute_id' => $this->getId()));
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getLanguageCode()
	{
		return $this->lang_code;
	}
	
	public function getOptions()
	{
		return $this->_options;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getCode()
	{
		return $this->code;
	}
	
	public function setOptions($options)
	{
		if(!empty($options)){
			$this->_options = $options;
		}
		
		return $this;
	}
	
	function getLanguage()
	{
		return $this->lang_code;
	}
	
	function getConfigHtml($selectedValue = '', Array $options = array())
	{
		if(empty($this->type)){
			return '';
		}
		
		return PaycartAttribute::getInstance($this->type)->getConfigHtml($this,$selectedValue,$options);
	}
	
	function getEditHtml($selectedValue = '', Array $options = array())
	{
		return PaycartAttribute::getInstance($this->type)->getEditHtml($this,$selectedValue,$options);
	}
	
	function getSelectorHtml($selectedValue = '', Array $options = array())
	{
		return PaycartAttribute::getInstance($this->type)->getSelectorHtml($this,$selectedValue,$options);
	}
	
	function getScript()
	{
		return PaycartAttribute::getInstance($this->type)->getScript();
	}
}