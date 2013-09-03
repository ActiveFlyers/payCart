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
	protected $title 		 =	null;	
	protected $alias 		 =	'';
	protected $published		 =	1;
	protected $type			 =	'';
	protected $amount		 = 	0.00;
	protected $quantity		 =	0;
	protected $sku	;	
	protected $variation_of	 =	0;  		// This product is variation of another product. 	
	protected $category_id 	 =	0;
	protected $params 		 =	null;
	protected $cover_media	 =	null; 	
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
		$this->title 		 =	'';	
		$this->alias 		 =	'';
		$this->published 	 =	1;
		$this->type			 =	Paycart::PRODUCT_TYPE_PHYSICAL;
		$this->amount		 = 	0;
		$this->quantity		 =	0;
		$this->sku;	
		$this->variation_of	 =	0;  		// This product is variation of another product. 	
		$this->category_id 	 =	0;
		$this->params 		 =	new Rb_Registry();
		$this->cover_media	 =	null; 	
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
		//Extra fields (not realted to columm)
		//IMP:: Dont use '_'(underscore here) becoz we need to inject extra attributes into form
		//if we use _ then it will treat as a extra object property and discarded it.(At data-binding on form) 
		$this->attributes   = Array();
		
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
		
		// IMP :: It will be sliggify and unique into model validation
		if (!$this->alias) {
			$this->alias = $this->getTitle();
		}
		// IMP :: It will be sliggify and unique into model validation		
		if (!$this->sku) {
			$this->sku = $this->getTitle();
		}
		
		// Set Cover Image Path
		if ( $this->upload_files && isset($this->upload_files['cover_media']) && $this->upload_files['cover_media']['name']) {
			$this->cover_media = PaycartHelper::getHash($this->upload_files['cover_media']['name']).PaycartHelperImage::getConfigExtension($this->upload_files['cover_media']['name']);
			$this->cover_media = $this->getName().'/'.$this->cover_media;			
		}
		
		return parent::save();
	}
	
	/**
	 * @return Product name 
	 */
	public function getTitle() 
	{	
		return $this->title;
	}
	
	/**
	 * @return name of Product Cover Media 
	 */
	public function getCoverMedia() 
	{	
		return $this->cover_media;
	}

	/**
	 * 
	 * @return Product's Variantion_of
	 */
	public function getVariationOf() {
		return $this->variation_of;
	}
	
	/**
	 * We required media/image processing after Product save
	 * 
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::_save()
	 */
	protected function _save($previousObject)
	{
		$id = parent::_save($previousObject);
		
		// if save fail
		if (!$id) { 
			return false;
		}
		
		// correct the id, for new records required
		$this->setId($id);
		
		// Cover image
		if ( $this->upload_files && isset($this->upload_files['cover_media'])) {
			// Don't check here isset otherwise it will true (In < PHP 5.4)
			// is_aaray check for it's not a variant && Post data is not empty
			if(is_array($this->upload_files['cover_media']) && !empty($this->upload_files['cover_media']['name'])) { // create new product or re-save product
				$this->_ImageProcess($this->upload_files['cover_media'], $previousObject);
			} elseif(!$previousObject && $this->cover_media && $this->variation_of) { // create new variant
				// Get Image info
				$sourceFileInfo = PaycartHelperImage::imageInfo($this->upload_files['cover_media']);
				// target file-name 
				$targetFileInfo = PaycartHelperImage::imageInfo($this->cover_media);
				// Original Image
				$sourceImage = PaycartHelperImage::getDirectory().$sourceFileInfo['dirname'].'/'.Paycart::IMAGE_ORIGINAL_PREFIX.$sourceFileInfo['filename'].Paycart::IMAGE_ORIGINAL_SUFIX;		
				
				$imageInfo = Array (
	  					'sourceFile' 	=> $sourceImage ,
	  					'targetFolder' 	=> PaycartHelperImage::getDirectory().$sourceFileInfo['dirname'],
	 					'targetFileName'=> $targetFileInfo['filename']
	  					  );
	  			//@PCTODO :: Source Image must be save
	  			$this->_ImageCreate($imageInfo);
			} 
		}
		
		$attributeValue = PaycartFactory::getInstance('attributevalue', 'model');
		//Delete all Custom attribute if exist on Previous object
		if (!empty($previousObject->attributes)) {
			$attributeValue->deleteMany(Array('product_id'=>$id));
		} 
		
		// If any new custom attribute attached with new object then need to save it 
		if(!empty($this->attributes)) {
			$data = Array();
			foreach ($this->attributes as $attributeId => $value) {
				$data[$attributeId]['product_id']	= $id;
				$data[$attributeId]['attribute_id'] = $attributeId;
				$data[$attributeId]['value'] 		= $value['value'];
				$data[$attributeId]['order'] 		= $value['order'];
			}
			// save new attrinutes
			$attributeValue->save($data);
		}
		
		return $id;
	}
	
	/**
	 * Override it due to set upload_files variable
	 * 
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::bind()
	 */
	function bind($data, $ignore = Array()) 
	{
		parent::bind($data, $ignore);
		
		if(is_array($data)) {
			$data = (object) $data;
		}

		if(isset( $data->upload_files)) {
			$this->upload_files = $data->upload_files;
		}
		
		// if custom Attributes available in data then bind with lib object 
		$attributes = isset($data->attributes) ? $data->attributes : Array();
		
		// load custom attributes and bind with product lib
		// If attribute value is avilable at data object then no need to set it
		if($this->getId() && empty($attributes)) { 
			$attributeValueModel 	= PaycartFactory::getInstance('attributevalue', 'model');
			$this->attributes 		= $attributeValueModel->loadProductRecords($this->getid());
		}
		
		foreach ($attributes as $attribute_id => $attribute) {
			$this->attributes[$attribute_id]['value'] = $attribute['value'];
			$this->attributes[$attribute_id]['order'] = $attribute['order'];
		}
		
		return $this;
	}
	
	/**
	 * 
	 * Process Cover Image
	 * @param $imageFile, File type requested data.
	 * @param Lib_object $previousObject
	 * 
	 * @return (bool) True if successfully proccessed
	 * PCTODO:: move to parent lib
	 */
	protected function _ImageProcess($imageFile, $previousObject)
	{
		$app = PaycartFactory::getApplication();
		
		// Image validation required	
		if (!PaycartHelperImage::isValid($imageFile)) {
			$error = PaycartHelperImage::getError();
			PaycartFactory::getApplication()->enqueueMessage($error, 'warning');
			return false;
		}
		
		$imagePath	= PaycartHelperImage::getDirectory();
		
		// 	Upload new image while Previous Image exist 
		// need to remove previous image and thumbnail image
		if ($previousObject  && $previousObject->get('cover_media')) {
			// PCTODO:: Break delete logic into function
			$previousImage 		 =  $previousObject->get('cover_media');
			$previousImageDetail =  PaycartHelperImage::imageInfo($previousImage);
			
			$files = Array(
							$imagePath.$previousImageDetail['dirname'].'/'.Paycart::IMAGE_ORIGINAL_PREFIX.$previousImageDetail['filename'].Paycart::IMAGE_ORIGINAL_SUFIX,		// Original Image
							$imagePath.$previousImage,				// Optimize Image
							$imagePath.$previousImageDetail['dirname'].'/'.Paycart::THUMB_IMAGE_PREFIX.$previousImageDetail['filename'].'.'.$previousImageDetail['extension']
						);
			// Delete Original Image,Optimize Image and thumb Image
			JFile::delete($files);
		}
		
		$currentImageDetail = PaycartHelperImage::imageInfo($this->cover_media);
		$imagePath			= $imagePath.'/'.$currentImageDetail['dirname'];
		
		//Create new folder
		// @PCTODO : Remove this checking
		// @ISSUE : 17
		if(!JFolder::exists($imagePath) && !JFolder::create($imagePath)) {
			$app->enqueueMessage(Rb_Text::sprintf('COM_PAYCART_FOLDER_CREATEION_FAILED', $imagePath),'error');
			return false;
		}
		
		$imageInfo = Array('sourceFile' => $imageFile["tmp_name"], 'targetFolder' => $imagePath, 'targetFileName' => $currentImageDetail['filename']);
		return  $this->_ImageCreate($imageInfo);
	}
	
	/**
	 * PCTODO :: Move code to proper location so other entity like category will utilize it.
	 * Create new Image with Thumb. We do not provide any extension flexibility. Image extension have configured by Paycart System 
	 * How to Create :
	 * 		1#. Copy Original Image. Prfix 'original_' and Suffix '.orig' will be added to original image like "original_IMAGE-NAME.orig"
	 * 		2#. create New optimize Image (From New copied original Image)
	 * 		3#. Create new thumb(From optimize Image). Prifix "thumb_" will be added to optimized image. Like "thumb_IMAGE_NAME" 
	 * @param array $imageInfo
	 * 		$mageInfo = Array 
	 * 					( 	
	 * 						'sourceFile' 	=>	'_ABSOLUTE_PATH_OF_SOURCE_IMAGE_',
	 * 						'targetFolder'	=>	'_ABSOLUTE_PATH_OF_TARGET_FOLDER_'
	 * 						'targetFileName'=>	'_NEW_CREATED_IMAGE_NAME_WITHOUT_IMAGE_EXTENSION' 
	 * 					)
	 */
	protected function _ImageCreate(Array $imageInfo)
	{
		// 1#. Copy source image to target folder it will be usefull for future operation like batch opration, reset operation etc 
		// Build Store path for Original image. Original Image available with prefix nd suffix like original_IMAGE-NAME.orig
		$originalImage	= $imageInfo['targetFolder'].'/'.Paycart::IMAGE_ORIGINAL_PREFIX.$imageInfo['targetFileName'].Paycart::IMAGE_ORIGINAL_SUFIX;;
		if (!JFile::copy($imageInfo['sourceFile'], $originalImage )) {
			JFactory::getApplication()->enqueueMessage(Rb_Text::sprintf('COM_PAYCART_FILE_COPY_FAILED', $originalImage),'error');
			return false;
		}
		
		//2#.Create new optimize image
		// make Optimized Image Path. Extension of optimized image confiured by Paycart System
		$optimizeImage	= $imageInfo['targetFolder'].'/'.$imageInfo['targetFileName'].PaycartHelperImage::getConfigExtension($imageInfo['sourceFile']);
		//@PCTODO (Discuss Point ) : height and width calculate with respect to original image.
		if (!PaycartHelperImage::resize($imageInfo['sourceFile'], $optimizeImage, Paycart::IMAGE_OPTIMIZE_WIDTH, Paycart::IMAGE_OPTIMIZE_HEIGHT)) {
			JFactory::getApplication()->enqueueMessage(Rb_Text::sprintf('COM_PAYCART_IMAGE_RESIZE_FAILED', $optimizeImage),'error');
			return false;
		}

		//3# Create thumbnail
		if(!PaycartHelperImage::createThumb($optimizeImage, $imageInfo['targetFolder'],  Paycart::THUMB_IMAGE_WIDTH,  Paycart::THUMB_IMAGE_HEIGHT)){
			JFactory::getApplication()->enqueueMessage(Rb_Text::sprintf('COM_PAYCART_THUMB_IMAGE_CREATION_FAILED', $optimizeImage),'error');
			return false;
		}
		return true;
	}
	
	/**
	 * 
	 * Create new variation of Product. 
	 */
	public function addVariant()
	{
		$newProduct 	= $this->getClone();
		$newProduct->variation_of = $this->getId();
		// New created variant will be always variation of ROOT product. 
		// @see Discuss#39
		if($variantOf = $this->getVariationOf()) {
			$newProduct->variation_of = $variantOf;
		}
		//### Attribute Changes in Variants
		//1. Product id should be 0
		$newProduct->product_id = 0 ;
		//2. New image file name save nd create new after save
		if($this->getCoverMedia()) { 
			// set Image name  
			$newProduct->cover_media = PaycartHelper::getHash($this->getTitle()).PaycartHelperImage::getConfigExtension($this->getCoverMedia());
			$newProduct->cover_media = $this->getName().'/'.$newProduct->cover_media;
			// set source path. It will required on image processing
			$newProduct->upload_files['cover_media'] = $this->getCoverMedia();
		}		
		
		// Save new variant		
		return $newProduct->save();
		
	}
}
