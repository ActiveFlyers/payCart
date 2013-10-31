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
	protected $created_by 	 =	0;
	protected $ordering		 =	0;
	protected $featured		 =	0;	
	protected $description	 =	null; 	
	protected $hits			 =	0;
	protected $meta_data	 = 	null;
	
	//Extra fields (not realted to columm) 
	protected $_upload_files   = Array();
	
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
		$this->sku			 =  '';	
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
		$this->_attributeValue   = Array();
		$this->_upload_files   = Array();
		
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
			$this->created_by = PaycartFactory::getUser()->get('id');
		}
		
		// IMP :: It will be sliggify and unique into model validation
		if (!$this->alias) {
			$this->alias = $this->getTitle();
		}
		// IMP :: It will be sliggify and unique into model validation		
		if (!$this->sku) {
			$this->sku = $this->getTitle();
		}
		
		// @PCTODO :: Set default Image 
		// Set Cover Image Path
		if ( isset($this->_upload_files['cover_media']) && $this->_upload_files['cover_media']['name']) {
			$extension = PaycartFactory::getConfig()->get('image_extension', Paycart::IMAGE_FILE_DEFAULT_EXTENSION);
			$this->cover_media = PaycartHelper::getHash($this->_upload_files['cover_media']['name']);
			$this->cover_media = $this->getName().'/'.$this->cover_media.$extension;			
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
	 * 
	 * @return Product's amount
	 */
	public function getAmount() {
		return $this->amount;
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
		
		// Process If Cover-media exist
		if (!empty($this->cover_media)) {
			$this->_saveCoverMedia($previousObject);
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
	 * Reload current object
	 * 
	 * @return PaycartProduct instance
	 * @PCTODO:: move to upper level
	 */
	public function reload()
	{
		$data = $this->getModel()->loadRecords(array('id'=>$this->getId()));
		return $this->bind($data[$this->getId()]);
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
		$attributeValueModel = PaycartFactory::getInstance('attributevalue', 'model');
		//Delete all Custom attribute if exist on Previous object
		if ( $previousObject && !empty($previousObject->_attributeValue) ) {
			$attributeValueModel->deleteMany(Array('product_id'=>$this->getId()));
		} 
		
		// If any new custom attribute attached with new object then need to save it 
		if(!empty($this->_attributeValue )) {
			$data = Array();
			
			foreach ($this->_attributeValue as $attributeId => $attributeValue) {
				$data[$attributeId]['product_id']	= $this->getId();
				$data[$attributeId]['attribute_id'] = $attributeId;
				
				// get formatted records
				$record = $attributeValue->toDatabase();
				
				$data[$attributeId]['value'] 		= $record['value'];
				$data[$attributeId]['order'] 		= $record['order'];
			}
			
			// save new attrinutes
			$attributeValueModel->save($data);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * Process Cover Media
	 * @param  $previousObject, Product lib object
	 * @throws RuntimeException OR InvalidArgumentException
	 * 
	 * @return Product Lib object 
	 */
	protected function _saveCoverMedia($previousObject)
	{
		try {
			// Create new product or re-save existing product
			// IMP :: Don't check here isset otherwise it will true (In < PHP 5.4) (for $this->_upload_files['cover_media']['name'])
			// is_array check for it's not a variant && Post data is not empty
			if (isset($this->_upload_files['cover_media']) && is_array($this->_upload_files['cover_media']) && !empty($this->_upload_files['cover_media']['name']) ) {
				$this->_ImageProcess($this->_upload_files['cover_media'], $previousObject);
			}
			
			// Create new variant then you dont have any uploaded image. Use parent Image
			if (!$previousObject && $this->variation_of) {
				// Image store path
				$path = PaycartFactory::getConfig()->get('image_upload_directory', JPATH_ROOT.Paycart::IMAGES_ROOT_PATH);
				// Source Image
				$sourceImage = $path.'/'.$this->_upload_files['cover_media'];
				$this->_ImageProcess($sourceImage);
			}
			
		//PCTODO:: exception fire at proper location	
		} catch (RuntimeException $e) {
			//PCTODO :: Notify to User Or set-up error queue
			$message = $e->getMessage();
		} catch (InvalidArgumentException $e) {
			//PCTODO :: Notify to User Or set-up error queue
			$message = $e->getMessage();
		}
			
		return $this;
	}
	
	/**
	 * Override it due to set _upload_files variable
	 * 
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::bind()
	 */
	function bind($data, $ignore = Array()) 
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		
		parent::bind($data, $ignore);
		
		if( isset($data['_upload_files'])) {
			$this->_upload_files = $data['_upload_files'];
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
			$attributeValueModel = PaycartFactory::getInstance('attributevalue', 'model');
			$attributeData 	= $attributeValueModel->loadProductRecords($this->getid());
		}
		
		if(empty($attributeData)) {
			//throw InvalidArgumentException(Rb_Text::_('COM_PAYCART_PRODUCT_ATTRIBUTEVALUE_INVALID'));
			return false;
		}
		
		//Get all attributes
		$condition	= Array('attribute_id' => Array(Array('IN', '('.implode(',',array_keys($attributeData)).')')));
		$attributes = PaycartFactory::getInstance('attribute','model')
									->loadRecords($condition); 
		
		foreach ($attributes as $attributeId => $attribute) {
			// Format data
			$data['product_id'] 	=	$this->getId();
			$data['attribute_id']	=	$attributeId;
			$data['value']			=	$attributeData[$attributeId]['value'];
			$data['order']			=	$attributeData[$attributeId]['order'];
			$data['_attribute']		=	PaycartAttribute::getInstance($attributeId, $attribute);
			// Set PaycartAttributeValue instance
			$this->_attributeValue[$attributeId] = PaycartAttributeValue::getInstance(0, $data);
		}

		return $this;
	}
	
	/**
	 * 
	 * Process Cover Image
	 * @param $imageFile, File type requested data.
	 * @param Lib_object $previousObject
	 * @throws RuntimeException OR InvalidArgumentException
	 * 
	 * @return ProductLib
	 * @PCTODO:: move to parent lib
	 */
	protected function _ImageProcess($sourceImage, $previousObject = null)
	{	
		// Get file path where Image will be saved 
		//PCTODO :: get it from helper
		$path	= PaycartFactory::getConfig()->get('image_upload_directory', JPATH_ROOT.Paycart::IMAGES_ROOT_PATH);

		$image = PaycartFactory::getHelper('image');
		
		// Upload new image while Previous Image exist 
		// need to remove previous image { Original, Optimized and thumbnail image }
		if ($previousObject && $previousObject->getCoverMedia()) {
			$previousImage 	=  $previousObject->getCoverMedia();
			$image->delete($path.'/'.$previousImage);
		}
		
		// target file-info 
		$targetFileInfo = $image->imageInfo($this->cover_media);
		$targetFolder 	= $path.'/'.$targetFileInfo['dirname'];
		
		// Load, validate and then create image 
		$image->loadFile($sourceImage)
			  ->validate()
			  ->create($targetFolder, $targetFileInfo['filename']);
			 
		return $this;
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
			$extension = PaycartFactory::getConfig()->get('image_extension', Paycart::IMAGE_FILE_DEFAULT_EXTENSION);
			// set Image name  
			$newProduct->cover_media = PaycartHelper::getHash($this->getTitle());
			$newProduct->cover_media = $this->getName().'/'.$newProduct->cover_media.$extension;
			// set source path. It will required on image processing
			$newProduct->_upload_files['cover_media'] = $this->getCoverMedia();
		}		
		
		// Changable Property
		$newProduct->publish_up	   =	Rb_Date::getInstance();
		$newProduct->publish_down  =	Rb_Date::getInstance('0000-00-00 00:00:00');	 	
		$newProduct->created_date  =	Rb_Date::getInstance();	
		$newProduct->modified_date =	Rb_Date::getInstance(); 
		
		// Save new variant		
		return $newProduct->save();	
	}
	
	/**
	 * We want to include _attribute variable.  
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::toArray()
	 */
	public  function toArray()
	{

		$arr['_attributeValue'] = $this->_attributeValue ;
		$ret = parent::toArray();
		return array_merge($arr, $ret);
		
	}
}
