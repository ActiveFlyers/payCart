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
	protected $css_class	 =	'';
	protected $filterable	 = 	0;
	protected $searchable	 =	0;
	protected $status 		 =	'';
	protected $config		 =  null;
	protected $ordering		 =	0;
	
	//language specific data
	protected $_language;
	//attribute specific data
	protected $_options = array();
	
	public function reset() 
	{	
		$this->productattribute_id	 =	0; 
		$this->type 		 =	'';
		$this->css_class	 =	'';
		$this->filterable	 = 	0;
		$this->searchable	 =	0;
		$this->status 		 =	'';
		$this->ordering		 =	0;
		$this->config		 =  new Rb_Registry();
		
		//language specific data
		$this->_language        = new stdClass();
		$this->_language->title = '';
		$this->_language->productattribute_lang_id 	= 0;
		$this->_language->productattribute_id 		= 0;
		$this->_language->lang_code  				= PaycartFactory::getLanguage()->getTag(); //Current Paycart language Tag
		
		//attribute specific options
		$this->_options = array();
		
		return $this;
	}
	
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
		
		//Collect langauge data
		$language = (isset($data['language'])) ? $data['language']: array();
		
		//bind it to lib instance
		$this->setLanguageData($language);
		
		//set attribute options
		$this->setOptions(PaycartAttribute::getInstance($this->type)->buildOptions($this, $data));
		
		return $this;
	}
	
	public function setLanguageData(Array $language = Array())
	{
		//if langauge data is not available and its an existing record
		if(empty($language) && $this->getId()){
			$language = PaycartFactory::getModelLang('productattribute')
					                           ->loadRecords(Array('lang_code' => $this->_language->lang_code,
																   'productattribute_id' => $this->getId()));
			$language = (array)array_shift($language);
		}
		
		if(empty($language)) {
			return false;
		}
		
		// set language data
		foreach ($this->_language as $key => $value) { 
			if(isset($language[$key])) {
				$this->_language->$key = $language[$key];
			}
		}
		
		return $this;
	}

	public function save()
	{
		//title is mandatory
		if(!$this->_language->title){
			throw new UnexpectedValueException(Rb_Text::sprintf('COM_PAYCART_TITLE_REQUIRED', $this->getName()));
		}	
		
		return parent::save();
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
		
		//save language data
		$this->_saveLanguageData($previousObject);
		
		//save attribute config
		PaycartAttribute::getInstance($this->type)->saveOptions($this);
		
		return $id;
	}
	
	protected function _saveLanguageData($previousObject)
	{
		//PCTODO: Handle it 
		if(empty($this->_language)){
			return false;
		}
		
		$data = (array)$this->_language;
		$data['productattribute_id'] = $this->getId();
		
		//save data
		$langModel = PaycartFactory::getModelLang('productattribute')->save($data, $data['productattribute_lang_id']);
		
		return $this;
	}
	
	protected function _delete()
	{
		//Delete attribute
		if(!$this->getModel()->delete($this->getId())) {
			return false;
		}
		
		//Delete language related data
		if (!$this->_deleteLanguageData()) {
			return false;
		}
		
		//delete option data
		if(!PaycartAttribute::getInstance($this->type)->deleteOptions($this->getId())){
			return false;
		}

		//delete all the attached values of this attribute for products 
		if(!$this->_deleteProductAttributeValues()){
			return false;
		}
	}
	
	protected function _deleteLanguageData()
	{
		return PaycartFactory::getModelLang('productattribute')->deleteMany(array('productattribute_id' => $this->getId()));
	}

	protected function _deleteProductAttributeValues()
	{
		return PaycartFactory::getModel('productattributevalue')->deleteMany(array('productattribute_id' => $this->getId()));
	}
	
	public function getTitle()
	{
		return $this->_language->title;
	}
	
	public function getLanguageCode()
	{
		return $this->_language->lang_code;
	}
	
	public function getOptions()
	{
		return $this->_options;
	}
	
	public function getType()
	{
		return $this->type;
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
		return $this->_language;
	}
	
	function getConfigHtml($selectedValue = '', Array $options = array())
	{
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
