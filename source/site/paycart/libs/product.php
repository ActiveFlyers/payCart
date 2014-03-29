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
	protected $status		 	= '';
	protected $type			 	= Paycart::PRODUCT_TYPE_PHYSICAL;
	protected $price		 	= 0.00;
	protected $quantity		 	= 0;
	protected $sku			 	= '';	
	protected $variation_of		= 0;  		// This product is variation of another product. 	
	protected $cover_media		= NULL;
	protected $created_date  	= '';	
	protected $modified_date 	= ''; 
	protected $ordering		 	= 0;
	protected $featured			= 0;

	protected $weight		 	= 0.00;
	protected $height		 	= 0.00;
	protected $length		 	= 0.00;
	protected $depth		 	= 0.00;
	protected $weight_unit	 	= '';
	protected $dimension_unit	= '';
	protected $stockout_limit	= 0;
	protected $config			= '';
	
	//Extra fields (not related to columm) 
	protected $_uploaded_files   = array();
	protected $_attributeValues;
	
	//language specific data
	protected $_language;
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::reset()
	 */
	public function reset() 
	{	
		$this->product_id	 	= 0; 
		$this->productcategory_id		= 0;
		$this->status		 	= '';
		$this->type			 	= Paycart::PRODUCT_TYPE_PHYSICAL;
		$this->price		 	= 0.00;
		$this->quantity		 	= 0;
		$this->sku			 	= '';	
		$this->variation_of		= 0;  		// This product is variation of another product. 	
		$this->cover_media		= NULL;
		$this->ordering		 	= 0;
		$this->featured			= 0;
	
		$this->weight		 	= 0.00;
		$this->height		 	= 0.00;
		$this->length		 	= 0.00;
		$this->depth		 	= 0.00;
		$this->weight_unit	 	= '';
		$this->dimension_unit	= '';
		$this->stockout_limit	= 0;
		$this->config			= '';
		$this->created_date  	= Rb_Date::getInstance();	
		$this->modified_date 	= Rb_Date::getInstance(); 
		
		$this->_attributeValues = new stdClass();
		
		$this->_language = new stdClass();
		$this->_language->product_lang_id	   = 0;
		$this->_language->product_id 		   = 0;	
		$this->_language->lang_code 		   = PaycartFactory::getLanguageTag(); //Current Paycart language Tag	
		$this->_language->title	 			   = '';	
		$this->_language->alias  			   = '';
		$this->_language->teaser 			   = '';
		$this->_language->description 		   = '';	
		$this->_language->metadata_title  	   = '';
		$this->_language->metadata_keywords	   = '';
		$this->_language->metadata_description = '';	
		
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
		//title is mandatory
		if(!$this->_language->title){
			throw new UnexpectedValueException(Rb_Text::sprintf('COM_PAYCART_TITLE_REQUIRED', $this->getName()));
		}
		
		//set title if alias doesn't exist
		if (!$this->_language->alias) {
			$this->_language->alias = $this->_language->title;
		}
		
		// alias must be unique
		$this->_language->alias = PaycartFactory::getTableLang('Product')->getUniqueAlias($this->_language->alias, $this->_language->product_lang_id);

		if (!$this->sku) {
			$this->sku = $this->_language->alias;
		}
		
		// @PCTODO :: Set default Image 
		// Set Cover Image Path
		if ( isset($this->_uploaded_files['cover_media']) && !empty($this->_uploaded_files['cover_media']['name'])) {
			$extension = PaycartFactory::getConfig()->get('image_extension', Paycart::IMAGE_FILE_DEFAULT_EXTENSION);
			$this->cover_media = PaycartHelper::getHash($this->_uploaded_files['cover_media']['name']);
			$this->cover_media = $this->getName().'/'.$this->cover_media.$extension;			
		}
		
		return parent::save();
	}
	
	/**
	 * @return Product name 
	 */
	public function getTitle() 
	{	
		return $this->_language->title;
	}
	
	public function getLanguage()
	{
		return $this->_language;
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
		//PCTODO: First convert weight, height. depth, length into common storage : can be done in toDatabase function for each value
		
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
			$this->reload();
			
			$this->variation_of = $id;
			parent::_save($previousObject);
			
			//to reflect variation_of property, it is required
			$this->reload();
		}
		
		// Process If Cover-media exist
		if(!empty($this->cover_media)) {
			$this->_saveCoverMedia($previousObject);
		}
		
		//save langauge data
		$this->_saveLanguageData($previousObject);
		
		// Process Attribute Value
		$this->_saveAttributeValue($previousObject);

		// few class property might be changed by model validation
		// so we need to reflect these kind of changes (by model validation) to lib object
		// Also set attributes value on product
		$this->reload();
		
		return $id;
	}
	
	/**
	 * Save langauge specific data
	 */
	protected function _saveLanguageData($previousObject)
	{
		//PCTODO: Handle it 
		if(empty($this->_language)){
			return false;
		}
		
		$data = (array)$this->_language;
		$data['product_id'] = $this->getId();
		
		//save data
		$model = PaycartFactory::getModelLang('product');
		$productLangId = $model->save($data, $data['product_lang_id']);
		
		if(!$productLangId){
			throw new RuntimeException(Rb_Text::_("COM_PAYCART_UNABLE_TO_SAVE"), $model->getError());
		}
		
		return $this;
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
				foreach ($attributeValue as $value){
					$data[++$count]['product_id'] 		 = $productId;
					$data[$count]['productattribute_id'] = $attributeId;
					$data[$count]['productattribute_value'] = $value;
				}
			}
			PaycartFactory::getInstance('productAttributeValue', 'model')->save($data);
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
			if (isset($this->_uploaded_files['cover_media']) && is_array($this->_uploaded_files['cover_media']) && !empty($this->_uploaded_files['cover_media']['name']) ) {
				$this->_ImageProcess($this->_uploaded_files['cover_media'], $previousObject);
			}
			
			// Create new variant then you dont have any uploaded image. Use parent Image
			if (!$previousObject && $this->variation_of != $this->getId()) {
				// Image store path
				$path = PaycartFactory::getConfig()->get('image_upload_directory', JPATH_ROOT.Paycart::IMAGES_ROOT_PATH);
				// Source Image
				$sourceImage = $path.'/'.$this->_uploaded_files['cover_media'];
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
	 * Override it due to set language and _upload_files variable
	 * 
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::bind()
	 */
	function bind($data, $ignore = Array()) 
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		
		//PCTODO: Change weight, height, depth, length etc in a format as per set weight/dimension unit
		
		parent::bind($data, $ignore);
		
		if( isset($data['_uploaded_files'])) {
			$this->_uploaded_files = $data['_uploaded_files'];
		}
		
		//media attribute related data 
		if( isset($data['_media'])) {
			$this->_media = PaycartHelperMedia::rearrangeMediaFiles($data['_media']['attributes']);
		}
		
		//Collect langauge data
		$language = (isset($data['language'])) ? $data['language']: array();
		
		//bind it to lib instance
		$this->setLanguageData($language);
		
		// if custom Attributes available in data then bind with lib object 
		$attributes = isset($data['attributes']) ? $data['attributes'] : Array();
		
		// Bind attributevalue-lib's instance on Product lib  
		$this->setAttributeValues($attributes);

		return $this;
	}

	public function setLanguageData(Array $langauge = Array())
	{
		//if langauge data is not available and its an existing record
		if(empty($langauge) && $this->getId()){
			$langauge = PaycartFactory::getModelLang('Product')
					                           ->loadRecords(Array('lang_code' => $this->_language->lang_code,
																   'product_id' => $this->getId()));
			$langauge = (array)array_shift($langauge);
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
			$attributeValueModel = PaycartFactory::getInstance('productattributevalue', 'model');
			$attributeData 	= $attributeValueModel->loadProductRecords($this->getid());
		}
		
		if(empty($attributeData)) {
			//throw InvalidArgumentException(Rb_Text::_('COM_PAYCART_PRODUCT_ATTRIBUTEVALUE_INVALID'));
			return false;
		}
		
		//Get all attributes
		$condition	= Array('productattribute_id' => Array(Array('IN', '('.implode(',',array_keys($attributeData)).')')));
		$attributes = PaycartFactory::getInstance('productattribute','model')
									->loadRecords($condition); 
		
		foreach ($attributes as $attributeId => $attribute) {
			//$this->_attributes[$attributeId] = PaycartProductAttribute::getInstance($attributeId, $attribute);
			
			//PCTODO: Save media attribute at controller level, so that mediaId will be directly set from here 
			
			//array of attribute values
			$this->_attributeValues->$attributeId = $attributeData[$attributeId];
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
			$newProduct->_uploaded_files['cover_media'] = $this->getCoverMedia();
		}
		
		//3. set attribute values
		$newProduct->_attributeValues = $this->_attributeValues;
		
		//4. Changable Property 	
		$newProduct->created_date  =	Rb_Date::getInstance();	
		$newProduct->modified_date =	Rb_Date::getInstance(); 
		
		//5. Set langauge data
		//PCTODO: Save all the langauge data of main product
		$newProduct->_language     = $this->_language;
		$newProduct->_language->product_lang_id	   = 0;
		
		// Save new variant		
		return $newProduct->save();	
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
		if(!$this->_deleteProductAttributes()){
			return false;
		}
		
		//Delete language related data
		if (!$this->_deleteLanguageData()) {
			return false;
		}
		
		return true;
	}
	
	protected function _deleteLanguageData()
	{
		return PaycartFactory::getModelLang('Product')->deleteMany(array('product_id' => $this->getId()));
	}
	
	protected function _deleteProductAttributes()
	{
		return PaycartFactory::getInstance('productattributevalue', 'model')->deleteMany(array('product_id' => $this->getId()));
	}
}