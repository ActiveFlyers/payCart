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
	protected $published			= 1;
	protected $parent_id	 		= Paycart::PRODUCTCATEGORY_ROOT_ID;
	protected $cover_media	 		= 0; 	
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

	public function reset() 
	{		
		// Table Fields
		$this->productcategory_id	= 0; 
		$this->published		 	= 1;
		$this->parent_id	 		= Paycart::PRODUCTCATEGORY_ROOT_ID; //set id of root 
		$this->cover_media	 		= 0; 	
		$this->created_date  		= Rb_Date::getInstance();	
		$this->modified_date 		= Rb_Date::getInstance();		
		$this->productcategory_lang_id = 0;
		$this->lang_code 			= PaycartFactory::getPCDefaultLanguageCode();
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

	public function getAlias() 
	{
		return $this->alias;
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
		// Process If images exist
		if(isset($this->_uploaded_files['cover_media'])  && isset($this->_uploaded_files['cover_media']['tmp_name']) && !empty($this->_uploaded_files['cover_media']['tmp_name'])){
			if($this->cover_media){
				$media = PaycartMedia::getInstance($this->cover_media);
			}
			else{
				$media = PaycartMedia::getInstance();
				$data = array();
				$data['lang_code'] = $this->lang_code;
				$data['language']['title'] = $this->_uploaded_files['cover_media']['name'];
				$media->bind($data);
				$media->save();
			}
			
			$media->moveUploadedFile($this->_uploaded_files['cover_media']['tmp_name'], JFile::getExt($this->_uploaded_files['cover_media']['name']));
			$media->createThumb(PaycartFactory::getConfig()->get('catalogue_image_thumb_width'),PaycartFactory::getConfig()->get('catalogue_image_thumb_height'));
			$media->createOptimized(PaycartFactory::getConfig()->get('catalogue_image_optimized_width'),PaycartFactory::getConfig()->get('catalogue_image_optimized_height'));
			$media->createSquared(PaycartFactory::getConfig()->get('catalogue_image_squared_size'));
			$this->cover_media = $media->getId();
		}
				
		return parent::save();
	}
	
	/**
	 * @return media id/media set as productcategory's Cover Media 
	 */
	public function getCoverMedia($requireMediaArray = true) 
	{
		if(empty($this->cover_media)){
			return false;
		}
		
		if($requireMediaArray && !empty($this->cover_media)){
			return PaycartMedia::getInstance($this->cover_media)->toArray();
		}
		return $this->cover_media;
	}

	function getTitle()
	{
		return $this->title;
	}
	
	protected function _delete()
	{
		//Delete category
		if(!$this->getModel()->delete($this->getId())) {
			return false;
		}
		
		//Delete product images
		if(!$this->deleteImage()){
			return false;
		}
		
		return true;
	}
	
	/**
	 * Delete image if image id is given
	 * @param $imageId : Imgae id that is required to be deleted (optional)
	 */
	public function deleteImage($imageId = null)
	{
		$mediaId = $this->cover_media;
		
		if(!empty($imageId)){
			$mediaId = $imageId;
		}
		
		if(!empty($mediaId)){
			$media = PaycartMedia::getInstance($mediaId);
			// @PCTODO : error handling
			$media->delete();	
			$this->cover_media = 0;
		}

		// always return this (not false)
		// otherwise after delete trigger will not be fired 
		return $this;
	}
	
	public function getMetadataTitle()
	{
		return $this->metadata_title;
	}
	
	public function getMetadataDescription()
	{
		return $this->metadata_description;
	}
	
	public function getMetadataKeywords()
	{
		return $this->metadata_keywords;
	}
}
