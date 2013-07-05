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
 * Product Lib
 */
class PaycartProduct extends PaycartLib
{
	protected $product_id	 =	0; 
	protected $name 		 =	null;	//@PCTODO:: rename "name" to "title"
	protected $alias 		 =	'';
	protected $published		 =	1;
	protected $type			 =	'';
	protected $amount		 = 	0.00;
	protected $quantity		 =	0;
	protected $sku	;	
	protected $variation_of	 =	0;  		// This product is variation of another product. 	
	protected $category_id 	 =	0;
	protected $params 		 =	null;
	protected $cover_image	 =	null; 	
	protected $teaser		 =	null;
	protected $publish_up	 =	'';
	protected $publish_down  =	'';	 	
	protected $created_date  =	'';	
	protected $modified_date =	''; 	
	protected $ordering		 =	0;
	protected $featured		 =	0;	
	protected $description	 =	null; 	
	protected $hits			 =	0;
	protected $meta_data	 = 	null;
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::reset()
	 */
	public function reset() 
	{		
		$this->product_id	 =	0; 
		$this->name 		 =	'';	//@PCTODO:: rename "name" to "title"
		$this->alias 		 =	'';
		$this->published 	 =	1;
		$this->type			 =	Paycart::PRODUCT_TYPE_PHYSICAL;
		$this->amount		 = 	0;
		$this->quantity		 =	0;
		$this->sku;	
		$this->variation_of	 =	0;  		// This product is variation of another product. 	
		$this->category_id 	 =	0;
		$this->params 		 =	new Rb_Registry();
		$this->cover_image	 =	null; 	
		$this->teaser		 =	null;
		$this->publish_up	 =	Rb_Date::getInstance();
		$this->publish_down  =	Rb_Date::getInstance('0000-00-00 00:00:00');	 	
		$this->created_date  =	Rb_Date::getInstance();	
		$this->modified_date =	Rb_Date::getInstance(); 	
		$this->created_by	 =	0;
		$this->ordering		 =	0;
		$this->featured		 =	0;	
		$this->description	 =	null; 	
		$this->hits			 =	0;
		$this->meta_data	 = new Rb_Registry();
		
		return $this;
	}
	
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('Product', $id, $data);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::save()
	 * Formating here before save content
	 */
	public function save()
	{
		// Set Product owner
		if (!$this->created_by) {
			$this->created_by = Rb_Factory::getUser()->get('id');
		}
		//PCTODO :: Save extra query execution if you will check current alias to previous alias  
		// generate unique alias if not exist
		$this->alias = $this->getUniqueAlias();
		
		// Set Cover Image Path
		if ( $this->upload_files && isset($this->upload_files['cover_image']) && $this->upload_files['cover_image']['name']) {
			$this->cover_image = PaycartHelperImage::getOptimizeName($this->upload_files['cover_image']['name']);			
		}
		 
		return parent::save();
	}
	
	/**
	 * @return Product name 
	 */
	public function getTitle() 
	{	//@PCTODO:: rename "name" to "title"
		return $this->name;
		;
	}
	
	/**
	 * @return name of Product Cover Image 
	 */
	public function getCoverImage() 
	{	//@PCTODO :: option for path
		return $this->cover_image;
	}
	
	protected function _save($previousObject)
	{
		$id = parent::_save($previousObject);
		// correct the id, for new records required
		$this->setId($id);
		
		// Cover image
		if ( $this->upload_files && isset($this->upload_files['cover_image']) && $this->upload_files['cover_image']['name'] ) {
			$this->_ImageProcess($this->upload_files['cover_image'], $previousObject); 
		}
		
		return $id;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::bind()
	 * Override it due to set upload_files variable
	 */
	function bind($data, $ignore = Array()) 
	{
		$productLib = parent::bind($data, $ignore);
		
		if(is_array($data) && isset( $data['upload_files'])) {
			$productLib->upload_files = $data['upload_files'];
		}
		if(is_object($data) && isset( $data->upload_files)) {
			$productLib->upload_files = $data->upload_files;
		}
		
		return $productLib;
	}
	
	/**
	 * 
	 * Process Cover Image
	 * @param Lib_object $previousObject
	 * 
	 * @return (bool) True if successfully proccessed
	 * PCTODO:: move to parent lib
	 */
	protected function _ImageProcess($imageFile, $previousObject)
	{
		// Image validation required	
		if (!PaycartHelperImage::isValid($imageFile)) {
			$error = PaycartHelperImage::getError();
			PaycartFactory::getApplication()->enqueueMessage($error, 'warning');
			return false;
		}
		
		$entity 	= $this->getname();
		// Dyamically get constant name 
		$constant	= JString::strtoupper($entity.'_IMAGES_PATH');
		$folderPath = JPATH_ROOT.constant("Paycart::$constant");
		$folderName	= $this->getId();	
		$imagePath	= "$folderPath/$folderName";
		
		// 	Upload new image while Previous Image exist 
		// need to remove previous image and thumbnail image
		if ($previousObject  && $previousObject->get('cover_image')) {
			// need to remove previous image and thumbnail image
			$previousImage =  $previousObject->get('cover_image');
			// Delete Original Image
			PaycartHelperImage::delete($imagePath.'/'.PaycartHelperImage::getOriginalName($previousImage));
			// Delete Optimize Image
			PaycartHelperImage::delete($imagePath.'/'.$previousImage);
			// Delete thumb Image
			PaycartHelperImage::delete($imagePath.'/'.PaycartHelperImage::getThumbName($previousImage));
		}
		
		//Create new folder
		if(!JFolder::exists($imagePath) && !JFolder::create($imagePath)) {
			$this->app->enqueueMessage(Rb_Text::sprintf('COM_PAYCART_FOLDER_CREATEION_FAILED', $imagePath),'error');
			return false;
		}
		
		//Store original image
		$source 		= $imageFile["tmp_name"];
		$currentImage	= $this->getCoverImage();
		//PCTODO:: Image name should be clean
		$originalImage	= $imagePath.'/'.PaycartHelperImage::getOriginalName($currentImage);
		if (!JFile::copy($source, $originalImage)) {
			$this->app->enqueueMessage(Rb_Text::sprintf('COM_PAYCART_FILE_COPY_FAILED', $originalImage),'error');
			return false;
		}
		
		// Create new optimize image
		$optimizeImage 	= $imagePath.'/'.$currentImage;
		
		//@PCTODO :: height and width calculate respect with original image
		if (!PaycartHelperImage::resize($originalImage, $imagePath, Paycart::OPTIMIZE_IMAGE_WIDTH, Paycart::OPTIMIZE_IMAGE_HEIGHT, $currentImage)) {
			$this->app->enqueueMessage(Rb_Text::sprintf('COM_PAYCART_IMAGE_RESIZE_FAILED', $optimizeImage),'error');
			return false;
		}
		
		//Create thumbnail 
		if(!PaycartHelperImage::createThumb($optimizeImage, $imagePath,  Paycart::THUMB_IMAGE_WIDTH,  Paycart::THUMB_IMAGE_HEIGHT)){
			$this->app->enqueueMessage(Rb_Text::sprintf('COM_PAYCART_THUMB_IMAGE_CREATION_FAILED', $optimizeImage),'error');
			return false;
		}
		
		return true;
	}
	// PCTODO:: Remove this function
	function translateAliasToID($alias)
	{
		return PaycartHelperProduct::translateAliasToID($alias);
	}
	
}
