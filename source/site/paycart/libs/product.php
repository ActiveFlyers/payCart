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
 * Product Lib
 */
class PaycartProduct extends PaycartLib
{
	protected $product_id	 	= 0; 
	protected $productcategory_id		= 0;
	protected $published		= 1;
	protected $type			 	= Paycart::PRODUCT_TYPE_PHYSICAL;
	protected $price		 	= 0.00;
	protected $quantity		 	= 0;
	protected $sku			 	= '';	
	protected $variation_of		= 0;  		// This product is variation of another product. 	
	protected $cover_media		= 0;
	protected $created_date  	= '';	
	protected $modified_date 	= ''; 
	protected $ordering		 	= 0;
	protected $featured			= 0;

	protected $weight		 	= 0.00;
	protected $height		 	= 0.00;
	protected $length		 	= 0.00;
	protected $width		 	= 0.00;
	protected $weight_unit	 	= '';
	protected $dimension_unit	= '';
	protected $stockout_limit	= 0;
	protected $config			= '';
	
	//Extra fields (not related to columm) 
	protected $_uploaded_files   = array();
	protected $_images			 = array();
	protected $_attributeValues;
	
	//language specific data
	protected $product_lang_id	   = 0;
	protected $lang_code 		   = '';	
	protected $title 			   = '';	
	protected $alias  			   = '';
	protected $teaser 			   = '';
	protected $description 		   = '';	
	protected $metadata_title  	   = '';
	protected $metadata_keywords   = '';
	protected $metadata_description = '';	

	//variants of current product
	protected $_variants 			= array();
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::reset()
	 */
	public function reset() 
	{	
		$this->product_id	 	= 0; 
		$this->productcategory_id		= 0;
		$this->published		= 1;
		$this->type			 	= Paycart::PRODUCT_TYPE_PHYSICAL;
		$this->price		 	= 0.00;
		$this->quantity		 	= 0;
		$this->sku			 	= '';	
		$this->variation_of		= 0;  		// This product is variation of another product. 	
		$this->cover_media		= 0;
		$this->ordering		 	= 0;
		$this->featured			= 0;
	
		$this->weight		 	= 0.00;
		$this->height		 	= 0.00;
		$this->length		 	= 0.00;
		$this->width		 	= 0.00;
		$this->weight_unit	 	= '';
		$this->dimension_unit	= '';
		$this->stockout_limit	= 0;
		$this->config			= new Rb_Registry();
		$this->created_date  	= Rb_Date::getInstance();	
		$this->modified_date 	= Rb_Date::getInstance(); 
		
		$this->_attributeValues = array();
		$this->_images			= array();
		
		$this->product_lang_id	   = 0;		
		$this->lang_code 		   = PaycartFactory::getCurrentLanguageCode();	
		$this->title	 		   = '';	
		$this->alias  			   = '';
		$this->teaser 			   = '';
		$this->description 		   = '';	
		$this->metadata_title  	   = '';
		$this->metadata_keywords   = '';
		$this->metadata_description = '';	

		$this->_variants 			= array();
		
		return $this;
	}
	
	/**
	 * 
	 * PaycartProduct Instance
	 * @param  $id, existing Product id
	 * @param  $data, required data to bind on return instance	
	 * @param  $dummy1, Just follow code-standards
	 * @param  $dummy2, Just follow code-standards
	 * 
	 * @return PaycartProduct lib instance
	 */
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
		// @PCTODO :: Set default Image 
		if(isset($this->_uploaded_files['images']) && !empty($this->_uploaded_files['images']['name'])){
			$extension = PaycartFactory::getConfig()->get('image_extension', Paycart::IMAGE_FILE_DEFAULT_EXTENSION);
			$this->_images['path'] = PaycartHelper::getHash($this->_uploaded_files['images']['name']);
			$this->_images['path'] = $this->getName().'/'.$this->_images['path'].$extension;	
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
	
	public function getAlias() 
	{	
		return $this->alias;
	}
	
	public function getWidth() 
	{	
		return $this->width;
	}
	
	public function getWeight() 
	{	
		return $this->weight;
	}

	public function getHeight() 
	{	
		return $this->height;
	}

	public function getLength() 
	{	
		return $this->length;
	}
	
	/**
	 * @return media id set as product Cover Media 
	 */
	public function getCoverMedia($requireMediaArray = true) 
	{	
		if($requireMediaArray && !empty($this->cover_media)){
			return PaycartMedia::getInstance($this->cover_media)->toArray();
		}
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
	 * 
	 * @return Product's price
	 */
	public function getPrice() {
		return $this->price;
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
		
		// Correct the id, for new records required
		$this->setId($id);
		
		
		//if variation_of parameter is still 0 then resave plan again 
		// because every base plan should be a variation of itself
		if(!$this->variation_of){
			//to reflect the automatic changes in object, it is required (like ordering)
//			$this->reload();
			
			$this->variation_of = $id;
			parent::_save($previousObject);
			
			//to reflect variation_of property, it is required
//			$this->reload();
		}
		
		// Process If images exist
		if(isset($this->_uploaded_files['images']) && count($this->_uploaded_files['images'])) {
			$media_ids = array();
			foreach($this->_uploaded_files['images'] as $image){
				// empty array is posted, if there is no file to upload
				if(!isset($image['tmp_name']) || empty($image['tmp_name'])){
					continue;
				}
				
				$media = PaycartMedia::getInstance();
				$data = array();
				$data['title'] = $image['name'];
				$media->bind($data);
				$media->save();

				$media->moveUploadedFile($image['tmp_name'], JFile::getExt($image['name']));
				$media->createThumb(PaycartFactory::getConfig()->get('catalogue_image_thumb_width'), PaycartFactory::getConfig()->get('catalogue_image_thumb_height'));
				$media->createOptimized(PaycartFactory::getConfig()->get('catalogue_image_optimized_width'),PaycartFactory::getConfig()->get('catalogue_image_optimized_height'));

				$media_ids[] = $media->getId();
			}
			
			if(count($media_ids)){
				$this->addImages($media_ids);
			}
			
			parent::_save($previousObject);
		}
		
		// Process Attribute Value
		$this->_saveAttributeValue($previousObject);

		// few class property might be changed by model validation
		// so we need to reflect these kind of changes (by model validation) to lib object
		// Also set attributes value on product
		$this->reload();
		
		return $id;
	}
	
	/**
	 * 
	 * Invoke this method after Product Save, to save Product (custom)attribute values 
	 * @param ProductLib $previousObject
	 * 
	 * @return PaycartProduct instance
	 */
	protected function _saveAttributeValue($previousObject)
	{
		$attributeValueModel = PaycartFactory::getInstance('productattributevalue', 'model');
		$productId 			 =  $this->getId();
		
		//Delete all Custom attribute if exist on Previous object
		if ($previousObject && !empty($previousObject->_attributeValues) ) {
			$attributeValueModel->deleteMany(Array('product_id'=>$productId));
		} 
		
		// If any new custom attribute attached with new object then need to save it 
		if(!empty($this->_attributeValues )){
			$data = Array();
			$count = 0;
			foreach ($this->_attributeValues as $attributeId => $attributeValue) {
				//format Value before save
				$attribute 		= PaycartProductAttribute::getInstance($attributeId);
				$value = PaycartAttribute::getInstance($attribute->getType())->formatValue($attributeValue);

				$data[++$count]['product_id'] 		 = $productId;
				$data[$count]['productattribute_id'] = $attributeId;
				$data[$count]['productattribute_value'] = $value;

			}
			PaycartFactory::getInstance('productAttributeValue', 'model')->save($data);
		}
		
		return $this;
	}
	
	/**
	 * Override it due to set language and _uploaded_files variable
	 * 
	 * IMP : In the given data, weight, length, height and width must be formatted in our standard database format
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
		
		// if custom Attributes available in data then bind with lib object 
		$attributes = isset($data['attributes']) ? $data['attributes'] : Array();
		
		// Bind attributevalue-lib's instance on Product lib  
		$this->setAttributeValues($attributes);

		return $this;
	}
	
	/**
	 * 
	 * Invoke method to set attribute on Product
	 * @param array $attributeData Array('_ATTRIBUTE_ID_' => '_ATTRIBUTE_DATA_')
	 * 
	 *  @return void
	 */
	protected function setAttributeValues(Array $attributeData = Array())
	{
		// If attribute-data is empty and product exist then load attribute data from database
		if ($this->getId() && empty($attributeData)) {
			$attributeValueModel = PaycartFactory::getModel('productattributevalue');
			$attributeData 	= $attributeValueModel->loadProductRecords($this->getid());
		}
		
		if(empty($attributeData)) {
			//throw InvalidArgumentException(Rb_Text::_('COM_PAYCART_PRODUCT_ATTRIBUTEVALUE_INVALID'));
			return false;
		}
		
		//IMP : should be reset before binding
		$this->_attributeValues = array();
		
		//Get all attributes
		foreach ($attributeData as $attributeId => $value){
			//array of attribute values
			$this->_attributeValues[$attributeId] = $value;
		}
		return $this;
	}
	
	protected function _delete()
	{
		//Delete product
		if(!$this->getModel()->delete($this->getId())) {
			return false;
		}
		
		//PCTODO: Should we delete variants or not??
		//if yes, then delete language and attributes also
		
		//delete product attributes
		if(!$this->deleteAttributeValues()){
			return false;
		}
		
		//Delete product images
		if(!$this->deleteImages()){
			return false;
		}

		//update base product of all the variants i.e variation_of property
		if(!PaycartFactory::getHelper('product')->updateVariationOf($this->getVariants())){
			return false;
		}
		
		return true;
	}
	
	public function deleteAttributeValues($productattributeId = null)
	{
		$condition['product_id'] = $this->getId();
		
		if(!is_null($productattributeId)){
			$condition['productattribute_id'] = $productattributeId;
		}
		
		unset($this->_attributeValues[$productattributeId]);
		return PaycartFactory::getModel('productattributevalue')->deleteMany($condition);
	}
	
	public function getAttributeValues($productAttributeId = null)
	{
		if(!empty($productAttributeId) && isset($this->_attributeValues[$productAttributeId])){
			$attributeValue = $this->_attributeValues[$productAttributeId];
			return $attributeValue;
		}
		return $this->_attributeValues;
	}
	
	/**
	 * Get all the images of product
	 * @return array containing detail all images
	 */
	public function getImages()
	{
		$imageIds = $this->config->get('images', array());
		$images   = array();
		if(!empty($imageIds)){
			foreach ($imageIds as $imageId){
				$images[$imageId] = PaycartMedia::getInstance($imageId)->toArray(); 
			}
		}
		
		return $images;
	}
	
	/**
	 * Set images and merge them with existing images 
	 * @param array $images : array of image ids
	 */
	public function addImages(Array $images = array())
	{
		$existing = $this->config->get('images', array());
		$current  = array_merge($existing, $images);
		$this->config->set('images', $current);
		
		//if cover media is not set then set first image as cover media
		if(empty($this->cover_media) && !empty($current)){
			$this->set('cover_media',array_pop($current));
		}
		
		return $this;
	}
	
	/**
	 * Set images and merge them with existing images
	 * @param array $images : array of image ids
	 */
	public function setImages(Array $images = array())
	{
		$this->config->set('images', $images);
		return $this;
	}

	/**
	 * Delete a particular image if image id is given otherwise all 
	 * @param $imageId : Imgae id that is required to be deleted (optional)
	 */
	public function deleteImages($imageIds = array())
	{
		$allMediaIds = $this->config->get('images',array());
		
		if(empty($imageIds)){
			$imageIds = $allMediaIds;
		}
		
		foreach($imageIds as $mediaId){
			$media = PaycartMedia::getInstance($mediaId);
			// @PCTODO : error handling
			$media->delete();
		}
		
		$imageIds = array_diff($allMediaIds, $imageIds);
		$imageIds = array_values($imageIds);
		return $this->setImages($imageIds);
	}
	
	/**
	 * Get detailed description of product
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	} 
	
	/**
	 * Load all the variants of the product
	 * @return vaiants array of stdClass  
	 */
	public function getVariants()
	{
		if(empty($this->_variants)){
			$records = PaycartFactory::getModel('product')->loadRecords(array('variation_of' => $this->getVariationOf()));
			foreach ($records as $record){
				$this->_variants[$record->product_id] = PaycartProduct::getInstance($record->product_id,$record);
			}
		}
		
		return $this->_variants; 
	}
	
	/**
	 * Load attributes from which product variants can be filtered
	 */
	public function getSelectorAttributes(Array $productIds = array())
	{
		return PaycartFactory::getModel('productattributevalue')->loadSelectorAttributes($productIds);
	}
	
	/**
	 * return html of specified type
	 * @param String $type : Type of html to be render  (edit and selector etc)
	 * @param $attributeId : attribute id
	 * @param String $selectedValue : selected value if any
	 * @param array $attributeOptions : options that is required to be displayed related to the current attribute
	 */
	public function getAttributeHtml($type, $attributeId, $selectedValue = '', Array $attributeOptions = array()) 
	{
		if(empty($attributeId)){
			return '';
		}
		
		$attribute	  = PaycartProductAttribute::getInstance($attributeId);
		$functionName = 'get'.ucfirst($type).'Html';
		return $attribute->$functionName($selectedValue, $attributeOptions);
	}
	
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	/**
	 * return productcategory id or instance
	 */
	public function getProductCategory($requireInstance = false)
	{
		if($requireInstance){
			return PaycartCategory::getInstance($this->productcategory_id);
		}
		
		return $this->productcategory_id;
	}

	/**
	 * set the given mediaId as cover image of product
	 *
	 * @param int $mediaId
	 */
	public function setCoverMedia($mediaId)
	{
		$this->cover_media = $mediaId;
		return $this;
	}
}