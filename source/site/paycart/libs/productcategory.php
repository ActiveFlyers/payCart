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
	protected $productcategory_id	= 0; 
	protected $status				= Paycart::STATUS_PUBLISHED;
	protected $parent_id	 		= Paycart::PRODUCTCATEGORY_ROOT_ID;
	protected $cover_media	 		= null; 	
	protected $created_date  		= '';	
	protected $modified_date 		= '';
	
	// language table
	protected $productcategory_lang_id	= 0;
	protected $lang_code 			= '';
	protected $title				= '';
	protected $alias				= '';
	protected $description			= '';
	protected $metadata_title		= '';
	protected $metadata_keywords	= '';
	protected $metadata_description	= '';

	// Related Table fields (Language specific fields)
	protected $_language;
	
	public function reset() 
	{		
		// Table Fields
		$this->productcategory_id	= 0; 
		$this->status		 		= Paycart::STATUS_PUBLISHED;
		$this->parent_id	 		= Paycart::PRODUCTCATEGORY_ROOT_ID; //set id of root 
		$this->cover_media	 		= null; 	
		$this->created_date  		= Rb_Date::getInstance();	
		$this->modified_date 		= Rb_Date::getInstance();		
		$this->productcategory_lang_id = 0;
		$this->lang_code 			= PaycartFactory::getLanguage()->getTag(); //@PCFIXME
		$this->title				= '';
		$this->alias				= '';
		$this->description			= '';		
		$this->metadata_title		= '';
		$this->metadata_keywords	= '';
		$this->metadata_description	= '';
		
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
	 * Override it due to _uploaded_files variable
	 *
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::bind()
	 */
	function bind($data, $ignore = Array())
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		parent::bind($data, $ignore);
		
		if( isset($data['_uploaded_files'])) {
			$this->_uploaded_files = $data['_uploaded_files'];
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
		if(!$this->title) {
			throw new UnexpectedValueException(Rb_Text::sprintf('COM_PAYCART_TITLE_REQUIRED', $this->getName()));
		}
		
		// if alias empty then set title on alias
		if (!$this->alias) {
			$this->alias = $this->title;
		}

		$this->alias = $this->getModel()->getValidAlias($this->alias, $this->parent_id, $this->productcategory_lang_id);		
	
		
		// Process If images exist
		if(isset($this->_uploaded_files['cover_media'])  && isset($this->_uploaded_files['cover_media']['tmp_name']) && !empty($this->_uploaded_files['cover_media']['tmp_name'])){
			if($this->cover_media){
				$media = PaycartMedia::getInstance($this->cover_media);
			}
			else{
				$media = PaycartMedia::getInstance();
				$data = array();
				$data['language']['title'] = $image['name'];
				$media->bind($data);
				$media->save();
			}
			
			$media->moveUploadedFile($this->_uploaded_files['cover_media']['tmp_name'], JFile::getExt($this->_uploaded_files['cover_media']['name']));
			$media->createThumb(PaycartFactory::getConfig()->get('catalogue_image_thumb_width'),PaycartFactory::getConfig()->get('catalogue_image_thumb_height'));
			$media->createOptimized(PaycartFactory::getConfig()->get('catalogue_image_optimized_width'),PaycartFactory::getConfig()->get('catalogue_image_optimized_height'));
			$this->cover_media = $media->getId();
		}
				
		return parent::save();
	}
	
	public function getCoverMedia()
	{
		if(empty($this->cover_media)){
			return false;
		}
		
		$media = PaycartMedia::getInstance($this->cover_media);
		if($media != false){
			return $media->toArray();
		}
		
		return false;
	}

	function getTitle()
	{
		return $this->title;
	}
}
