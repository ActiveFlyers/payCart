<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
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
	protected $css_class	 =	1;
	protected $visible		 =	1;
	protected $filterable	 = 	0;
	protected $searchable	 =	0;
	protected $status 		 =	'';
	protected $config		 =  null;
	protected $ordering		 =	0;
	
	//language specific data
	protected $_language;
	//attribute specific data
	protected $_params;
	
	public function reset() 
	{	
		$this->config		 =  new Rb_Registry();
		
		//language specific data
		$this->_language        = new stdClass();
		$this->_language->title = '';
		$this->_language->productattribute_lang_id 	= 0;
		$this->_langauge->productattribute_id 		= 0;
		$this->_language->lang_code  				= PaycartFactory::getLanguageTag(); //Current Paycart language Tag
		
		//option specific data
		$this->_params = array();
		
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
		
		//Collect langauge data
		$language = (isset($data['_language']) && isset($data['_language']['lang_code'])) ? $data['_language']: array();
		
		//bind it to lib instance
		$this->setLanguageData($language);
		
		//set attribute specific data
		//$this->setParams(PaycartAttribute::getInstance($this->type)->bindConfig($this, $data));
		
		return $this;
	}
	
	public function setLanguageData($language)
	{
		//if langauge data is not available and its an existing record
		if(empty($langauge) && $this->getId()){
			$langauge = (array) PaycartFactory::getInstance('productattributelang')
					                           ->loadRecords(Array('lang_code' => $this->_language->lang_code,
																   'productattribute_id' => $this->getId()));
		}
		
		if(empty($langauge)) {
			return false;
		}
		
		// set language data
		foreach ($this->_language as $key => $value) { 
			if(isset($langauge[$key])) {
				$this->_language->$key = $langauge[$key];
			}
		}
		
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
		
		//save language data
		$this->_saveLanguageData($previousObject);
		
		//save attribute config
		PaycartAttribute::getInstance($this->type)->saveConfig($this);
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
		$langModel = PaycartFactory::getInstance('productattributelang','model')->save($data, $data['productattribute_lang_id']);;
		
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
		if(!PaycartAttribute::getInstance($this->type)->deleteConfig($this)){
			return false;
		}
	}
	
	protected function _deleteLanguageData()
	{
		return PaycartFactory::getModel('productattributelang')->deleteMany(array('productattribute_id' => $this->getId()));
	}
	
	public function getTitle()
	{
		return $this->_langauge->title;
	}
	
	public function getLanguageCode()
	{
		return $this->_language->lang_code;
	}
	
	public function getParams()
	{
		return $this->_params;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function setParams($params)
	{
		if(!empty($params)){
			$this->_params = $params;
		}
		
		return $this;
	}
}