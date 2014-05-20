<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Productcategory Lib
 * @author manish
 *
 */
class PaycartProductcategory extends PaycartLib
{
	// Table Fields
	protected $productcategory_id	=	0; 
	protected $status				=	Paycart::STATUS_PUBLISHED;
	protected $parent_id	 		=	Paycart::PRODUCTCATEGORY_ROOT_ID;
	protected $cover_media	 		=	null; 	
	protected $created_date  		=	'';	
	protected $modified_date 		=	'';

	// Related Table fields (Language specific fields)
	protected $_language;
	
	public function reset() 
	{		
		// Table Fields
		$this->productcategory_id	=	0; 
		$this->status		 		=	Paycart::STATUS_PUBLISHED;
		$this->parent_id	 		=	Paycart::PRODUCTCATEGORY_ROOT_ID; //set id of root 
		$this->cover_media	 		=	null; 	
		$this->created_date  		=	Rb_Date::getInstance();	
		$this->modified_date 		=	Rb_Date::getInstance();
		
		// Related Table Fields
		$this->_language	 = new stdClass();
		
		// use only by system 
		$this->_language->productcategory_lang_id	= 0;
		$this->_language->productcategory_id	    = 0;
		$this->_language->lang_code 	= PaycartFactory::getLanguage()->getTag(); //Current Paycart language Tag	
		$this->_language->title			= '';
		$this->_language->alias			= '';
		$this->_language->description	= ''; 	
		
		$this->_language->metadata_title		= '';
		$this->_language->metadata_keywords	= '';
		$this->_language->metadata_description	= '';
		
		return $this;
	}
	
	
	/**
	 * 
	 * PaycartProductcategory Instance
	 * @param  $id, existing Productcategory id
	 * @param  $data, required data to bind on return instance	
	 * @param  $dummy1, Just follow code-standards
	 * @param  $dummy2, Just follow code-standards
	 * 
	 * @return PaycartProductcategory lib instance
	 */
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('productcategory', $id, $data);
	}

	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::bind()
	 */
	public function bind($data,  $ignore=array())
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		
		//1#. Bind Table specific fields
		parent::bind($data, $ignore);
		
		//2#. Bind related table fields (language specific)
		$records = array();
		//@PCTODO :: improve condition as per your requirment
		if(isset($data['language'])){
			// like data is Posted
			$records = $data['language'];
		} else if($this->getId()){
			//@PCTODO :: move to helper and array_shift record
			$records	 = PaycartFactory::getModelLang('Productcategory')
										->loadRecords(Array('lang_code' => $this->_language->lang_code,
															'productcategory_id' => $this->getId()
															));
			$records = (array)array_shift($records);															
		}
		
		$this->setLanguageData($records);
		
		return $this;
	}
	
	/**
	 *
	 * Set language specific data on $this _language property
	 * @param $data, Array of available data which are set on $this language
	 * 
	 * @return $this
	 */
	public function setLanguageData($data)
	{
		if(!is_array($data)){
			$data = (array) ($data);
		}
		
		// set _language stuff
		foreach ($this->_language as $key => $value) {
			// set data value on $this if key is set on data 
			if(isset($data[$key])) {
				$this->_language->$key = $data[$key];
			}
		}
		
		return $this;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::save()
	 */
	public function save()
	{
		// Title is must for any item 
		if(!$this->_language->title) {
			throw new UnexpectedValueException(Rb_Text::sprintf('COM_PAYCART_TITLE_REQUIRED', $this->getName()));
		}
		
		// if alias empty then set title on alias
		if (!$this->_language->alias) {
			$this->_language->alias = $this->_language->title;
		}

		$this->_language->alias = PaycartFactory::getModelLang('Productcategory')->getValidAlias($this->_language->alias, $this->parent_id, $this->_language->productcategory_lang_id);
		
		//@PCTODO :: before parent save. Create cover_media path and set on $this
		
		return parent::save();
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::_save()
	 */
	protected function _save($previousObject) 
	{
		// 1# Save Table fields data
		$saveId = parent::_save($previousObject);
		
		if (!$saveId) {
			return false;
		}
		
		$this->setId($saveId);
		
		//2#. Process Cover media
		//@PCTODO :: process cover media 
		
		//3#. Save Related table fields (Language stuff)
		$languageData = (Array)$this->_language;
		$languageData['productcategory_id'] = $saveId;
				
		// store new data
		if(!PaycartFactory::getModelLang('Productcategory')->save($languageData,$this->_language->productcategory_lang_id)) {
			//@PCTODO :: notify to admin
		}
		
		// few class properties might be changed by model validation or might be chage related fields
		// so we need to reflect these kind of changes to lib object
		$this->reload();
		
		return $saveId;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::_delete()
	 */
	protected function _delete()
	{
		//1#. Delete Table fields
		if(!$this->getModel()->delete($this->getId())) {
			return false;
		}
		
		//2#. Delete Related table fields
		if (!$this->_deleteLanguageData()) {
			// @PCTODO :: notify to admin 
		}
		
		return true;
	}
	
	/**
	 * 
	 * Delete language specific data for product category
	 * @param string $langCode, have language code if need to delete specificlanguage code data
	 * 
	 */
	protected function _deleteLanguageData($langCode = '')
	{
		// Product category id
		$condition['productcategory_id'] = $this->getId();
		
		// specific language code
//		if ($langCode) {
//			$condition['lang_code'] = $langCode;
//		}
		
		return PaycartFactory::getModelLang('Productcategory')->deleteMany($condition);
	}
	
	function getLanguage()
	{
		return $this->_language;
	}
	
	function getTitle()
	{
		return $this->_language->title;
	}
}
