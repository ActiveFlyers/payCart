<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Product Controller
 * @author mManishTrivedi
 */

class PaycartAdminControllerProduct extends PaycartController 
{
	public function __construct($options = array())
	{
		parent::__construct($options);
		
		$this->_boolMap['visible']  = array('column' => 'visible','value'=>1, 'switch'=>false);
		$this->_boolMap['invisible']= array('column' => 'visible','value'=>0, 'switch'=>false);

		//Register visible task 
		$this->registerTask( 'visible','multidobool');
		$this->registerTask( 'invisible','multidobool');
	}
	
	/**
	 * override it due to get all uploaded files 
	 */
	public function _save(array $data, $itemId=null, $type=null)
	{
		//Get All files from paycart form
		$data['_uploaded_files'] = $this->input->files->get($this->getControlNamePrefix(), false);
		
		//manage media files related to attribute
		if(isset($data['_uploaded_files']['attributes'])){			
			foreach ($data['_uploaded_files']['attributes'] as $attributeId => $value ){
				if(!empty($value['path']['tmp_name'])){
					$data['attributes'][$attributeId]['path'] = $value['path'];
				}
			}
		}
		
		//set attributes and position indices in post data,otherwise there would not be any way to 
        //identify at lib whether saving product or just creating instance
		if(!isset($data['attributes'])){
			$data['attributes'] = array();
			$data['config']     = array('positions'=>array()); 
		}
		
		/*
		 * Format measurement data into standard format before saving
		 * 
		 * It should be on controller level so that bind data will always be in standard format
		 * both for new and existing records  
		 */
		$data = $this->_formatMeasurementData($data);
		
		return parent::_save($data, $itemId, $type);
	}
	
	/**
	 * Format measurement data into our standard format
	 */
	protected function _formatMeasurementData($data)
	{
		$formatter           = PaycartFactory::getHelper('format');
		$weightUnitConfig    = PaycartFactory::getConfig()->get('catalogue_weight_unit');
		$dimensionUnitConfig = PaycartFactory::getConfig()->get('catalogue_dimension_unit');
		
				
		if(isset($data['weight'])){
			$data['weight'] = $formatter->convertWeight($data['weight'],$weightUnitConfig);
		}
		
		if(isset($data['length'])){
			$data['length'] = $formatter->convertDimension($data['length'],$dimensionUnitConfig);	
		}
		
		if(isset($data['width'])){
			$data['width']  = $formatter->convertDimension($data['width'],$dimensionUnitConfig);	
		}
		
		if(isset($data['height'])){
			$data['height'] = $formatter->convertDimension($data['height'],$dimensionUnitConfig);	
		}
		
		return $data;
	}
	
	/**
	 * 
	 * Ajax Task.
	 * 
	 * @PCTODO::  Use JSON formate for this kind of method 
	 * (Where you can get only data from server. no validation or task execution required)
	 */
	public function getAlias() 
	{
		//Check Joomla Session user should be login
		// PCTODO:: Should be check into parent class
		if ( JSession::checkToken() ) {
			//@PCTODO :: Rise exception 
		}
		return true;
	}
	
	/**
	 * 
	 * Ajax Call : Display "Add ttribute" window 
	 * (to creating new attribute) 
	 */
	public function addAttribute()
	{
		//Check Joomla Session user should be login
		if ( !JSession::checkToken() ) {
			//@PCTODO :: Rise exception 
		}
		return true;
	}
	
	public function _copy($itemId)
	{ 	
		try{
		 	$product = PaycartProduct::getInstance($itemId);
		}catch(Exception $ex){
 			//Validate product exist or not
			$this->setError(JText::_('COM_PAYCART_ADMIN_PRODUCT_PRODUCT_NOT_EXIST'));
			return false;
		} 
		
		// if everything is ok then create new variant
		$productHelper = PaycartFactory::getHelper('product');
		$copy          = $productHelper->addVariant($product,false);
		if(!$copy) {
			$this->setError(JText::_('COM_PAYCART_ADMIN_PRODUCT_COPY_CREATION_FAIL'));
			return false;
		}
		
		return true;
	}
	
	/**
	* Add New Product Variant
	*/
	public function addVariant()
	{
		$variantOf = $this->input->get('variant_of', false);
		//@PCTODO :: use setredirector
		$app = PaycartFactory::getApplication();
		// Check variantof 
		if(!$variantOf) {
			$app->enqueueMessage(Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_VARIANT_PARENT_REQUIRED'),'error');
			$this->setRedirect('index.php?option=com_paycart&view=product');
			return false;
		}
		
		$product = PaycartProduct::getInstance($variantOf);
		
		//Validate Variant exist or not
		if (!$product) {
			$app->enqueueMessage(Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_VARIANT_PARENT_NOT_EXIST'),'error');
			$this->setRedirect('index.php?option=com_paycart&view=product');
			return false;
		} 
		
		// if everything is ok then create new variant
		$productHelper = PaycartFactory::getHelper('product');
		$variant       = $productHelper->addVariant($product);
		if(!$variant) {
			$app->enqueueMessage(Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_VARIANT_CREATION_FAIL'),'error');
			return false;
		}
		
		$this->setRedirect(
						'index.php?option=com_paycart&view=product&task=edit&product_id='.$variant->getId(),
						Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_VARIANT_CREATION_SUCCESS')
							);
		// no need to execute view functions
		return false;
	}
	
	/**
	 * Ajax task : Delete any attached attribute
	 */
	public function deleteAttributeValues()
	{
		$productAttributeId =  $this->input->get('productattribute_id', false);
		$product = PaycartProduct::getInstance($this->input->get('product_id', false));
		$product->deleteAttributeValues($productAttributeId);
		
		$ajax = PaycartFactory::getAjaxResponse();
		$ajax->addScriptCall('paycart.jQuery(".paycart-product-attribute-'.$productAttributeId.'").remove');
		return false;
	}
	
	/**
	 * Ajax task : Delete image attached to product
	 */
	public function deleteImage()
	{
		$productId = $this->input->get('product_id',0);
		$instance  = PaycartProduct::getInstance($productId);
		$imageId   = $this->input->get('image_id',0);
		
		$productHelper = PaycartFactory::getHelper('product');
		$ret 	       = $productHelper->deleteImage($instance,$imageId);
		
		$view = $this->getView();
		if($ret){
			$view->assign('success', true);			
		}
		else{
			$view->assign('success', false);
		}
	
		return true;
	}
	
	public function reorderImages()
	{
		$productId 		= $this->input->get('product_id',0);
		$instance  		= PaycartProduct::getInstance($productId);
		$imageIds   	= $this->input->get('image_ids', array(), 'array');		
		$coverImageId 	= reset($imageIds);		
		$ret = $instance->setImages($imageIds)->setCoverMedia($coverImageId)->save();
		
		$view = $this->getView();
		if($ret){
			$view->assign('success', true);
			$view->assign('coverMedia',$instance->getCoverMedia(false));
		}
		else{
			$view->assign('success', false);
		}
	
		return true;
	}
	
	function editDigitalContent()
	{
		return true;
	}
	
    /**
     * validate digital content 
     */
	protected function _validateBeforeUpload($title, $media_id, $main, $teaser)
	{
		$response = new stdClass();
		$ajax     = PaycartFactory::getAjaxResponse();
		
		// 1. Check for the total size of post back data.
		$contentLength = (int) $_SERVER['CONTENT_LENGTH'];
		if( $contentLength > PaycartFactory::getHelper('media')->getUploadLimit())
		{
			$response->errorMsg = JText::_("COM_PAYCART_ADMIN_PRODUCT_DIGITAL_MAX_UPLOAD_LIMIT_EXCEEDED");
			$ajax->addScriptCall('paycart.admin.product.digital.save.error',$response);
			$ajax->sendResponse();
		}
		
		//2. check if title or main file doesn't exist
		if((empty($title) || (!$media_id && !isset($main['name']) && empty($main['name'])))){
			$response->errorMsg = JText::_("COM_PAYCART_ADMIN_PRODUCT_DIGITAL_MAIN_FILE_CANT_BE_LEFT_BLANK");
			$ajax->addScriptCall('paycart.admin.product.digital.save.error',$response);
			$ajax->sendResponse();
		}
		
		$allowed   = PaycartFactory::getConfig()->get('catalogue_allowed_files');
		
		//3. check supported filetype to be uploaded both for main and teaser files
		$mainfile  = strtolower(JFile::makeSafe($main['name']));		
		if(isset($main['name']) && !empty($main['name']) && 
		   (!preg_match('#\.('.str_replace(array(',','.'),array('|','\.'),$allowed).')$#Ui',$mainfile,$extension) || preg_match('#\.(php.?|.?htm.?|pl|py|jsp|asp|sh|cgi)$#Ui',$mainfile))){
			$response->errorMsg = JText::sprintf("COM_PAYCART_ADMIN_PRODUCT_DIGITAL_INVALID_MAIN_FILE_TYPE",$allowed);
			$ajax->addScriptCall('paycart.admin.product.digital.save.error',$response);
			$ajax->sendResponse();
		}
		
		$teaserfile  = strtolower(JFile::makeSafe($teaser['name']));
		if(isset($teaser['name']) && !empty($teaser['name']) && 
		   (!preg_match('#\.('.str_replace(array(',','.'),array('|','\.'),$allowed).')$#Ui',$teaserfile,$extension) || preg_match('#\.(php.?|.?htm.?|pl|py|jsp|asp|sh|cgi)$#Ui',$teaserfile))){
			$response->errorMsg = JText::sprintf("COM_PAYCART_ADMIN_PRODUCT_DIGITAL_INVALID_TEASER_FILE_TYPE",$allowed);
		   	$ajax->addScriptCall('paycart.admin.product.digital.save.error',$response);
			$ajax->sendResponse();
		}	
		
		return true;
	}
	
	/**
     * Ajaxified task to save digital content i.e teaser and main files
     */
	function saveDigitalContent()
	{
		$data 	   = array();
		$ajax      = PaycartFactory::getAjaxResponse();
		$response  = new stdClass();
		$title 	   = $this->input->post->get('title','','STRING');
		$lang_code = $this->input->get('lang_code','','STRING');		
		$media_id  = $this->input->get('media_id',0,'INT');
		$media_lang_id         = $this->input->get('media_lang_id',0,'INT');
		$teaser_media_id       = $this->input->get('teaser_media_id',0,'INT');
		$teaser_media_lang_id  = $this->input->get('teaser_media_lang_id',0,'INT');			
		
		$main   = $this->input->files->get('main',false,'raw');
		$teaser = $this->input->files->get('teaser',false,'raw');

		// 1. Validate uploaded data before proceeding		
		$this->_validateBeforeUpload($title,$media_id, $main,$teaser);
		
		// 2. save main file
		$media 				   = PaycartMedia::getInstance($media_id);
		$data['media_id']      = $media_id;
		$data['media_lang_id'] = $media_lang_id;
		$data['lang_code']     = $lang_code;
		$data['title'] 	       = $title;
		$main_id               = $media->setBasePath(PAYCART_PATH_MEDIA_DIGITAL_MAIN)->bind($data)->save()->getId();
		$prevFilePath          = PAYCART_PATH_MEDIA_DIGITAL_MAIN.$media->getFilename();
		$mainFilename		   = ''; 
		$teaserFilename		   = '';
		
		try{
			if(isset($main['name']) && !empty($main['name'])){
				$media->moveUploadedFile($main['tmp_name'], JFile::stripExt(JFile::makeSafe($main['name'])),JFile::getExt($main['name']));
			}
			
			//delete previous file if exist
			$newFilePath = PAYCART_PATH_MEDIA_DIGITAL_MAIN.$media->getFilename();
			if($media_id && $prevFilePath != $newFilePath && JFile::exists($prevFilePath)){ 
				JFile::delete($prevFilePath);
			}
			
			$mainFilename = $media->getFilename();
					
			// 3. save teaser file 
			if($teaser_media_id || (isset($teaser['name']) && !empty($teaser['name']))){
				$media 				   = PaycartMedia::getInstance($teaser_media_id);			
				$data['media_id']      = $teaser_media_id;
				$data['media_lang_id'] = $teaser_media_lang_id;
				$teaser_id             = $media->setBasePath(PAYCART_PATH_MEDIA_DIGITAL_TEASER)->bind($data)->save()->getId();
				$prevFilePath          = PAYCART_PATH_MEDIA_DIGITAL_TEASER.$media->getFilename();
				
				if(isset($teaser['name']) && !empty($teaser['name'])){
					$media->moveUploadedFile($teaser['tmp_name'], JFile::stripExt(JFile::makeSafe($teaser['name'])),JFile::getExt($teaser['name']));
				}
				
				//delete previous file if exist
				$newFilePath = PAYCART_PATH_MEDIA_DIGITAL_TEASER.$media->getFilename();
				if($teaser_media_id && $prevFilePath != $newFilePath && JFile::exists($prevFilePath)){ 
					JFile::delete($prevFilePath);
				}
				
				$teaserFilename = $media->getFilename();
				
			}
		}
		catch (Exception $ex){
			$response->errorMsg = JText::_("COM_PAYCART_ADMIN_PRODUCT_DIGITAL_CANT_MOVE_FILE");
			$ajax->addScriptCall('paycart.admin.product.digital.save.error',$response);
			$ajax->sendResponse();
		}
		
		// 4. set media details of main and teaser file in response
		$response->files = array('main' => $main_id, 'teaser' => $teaser_id, 'main_filename' => $mainFilename, 'teaser_filename' => $teaserFilename, 'title' => $title);		
		$ajax->addScriptCall('paycart.admin.product.digital.save.success',$response);
		$ajax->sendResponse();
	}
	
    /**
     * Ajaxified task to delete any Digital content row
     */
	function deleteDigitalContent()
	{
		$mediaId 	   = $this->input->get('main_id',0,'INT');
		$teaserMediaId = $this->input->get('teaser_id',0,'INT');
		$productId     = $this->input->get('product_id',0);

		$ajax = PaycartFactory::getAjaxResponse();
		if(!($mediaId && $productId)){
			$result = false;
			$ajax->addScriptCall('paycart.admin.product.deleteDigitalData.error',$response);
		}else{
			$product = PaycartProduct::getInstance($productId);
			$id = PaycartFactory::getHelper('product')->deleteDigitalContent($product,$mediaId,$teaserMediaId);
			$result = ($id)?true:false;
			
			$response = new stdClass();
			$response->main_id = $mediaId;
			$ajax->addScriptCall('paycart.admin.product.deleteDigitalData.success',$response);
		}
		$ajax->sendResponse();
	}
	
	public function import($tpl = null)
	{		
		return true;
	}
	
	// function to initialize import task and validate and save csv file
	public function initImport()
	{
		$file_position	= JRequest::getVar('file_position');
		
		// validate and save CSV file
		$helper		= PaycartFactory::getInstance('ImportFromCSV' , 'helper');
		$filename	= $helper->validateSaveCSV('product');
		
		$_SESSION['filename'] = $filename;
	}
	
	public function mapImportedCsvFields()
	{
		return true;
	}
	
	// function to dump csv data into a temporary table
	public function importCsvToTempTable()
	{
		$filename		= $_SESSION['filename'];
		$file_position	= JRequest::getVar('file_position');
		$mapped_fields  = JRequest::getVar('mapped_fields' , array());
		$config_form	= JRequest::getVar('paycart_config_form');
		$language		= $config_form['localization_default_language'];
		if(!empty($language)){
			PaycartFactory::setPCCurrentLanguageCode($language);
		}
		
		if(empty($mapped_fields))
		{
			$csv_headers	= $_SESSION['csv_headers'];

			foreach ($csv_headers as $csv_header)
			{
				$data		= JRequest::getVar($csv_header);
				if(!empty($data))
				{
					$mapped_fields[$csv_header]	= $data;
				}
			}
		}
				
		$helper		= PaycartFactory::getInstance('ImportFromCSV' , 'helper');
		$helper->importCsvToTempTable($mapped_fields , 'product' , $filename , $file_position);
	}
	
	// function to finally import csv data
	public function importCSV()
	{
		$start			 = JRequest::getVar('start', 0, 'int');
		$total			 = JRequest::getVar('total', 0, 'int');
		$imported_data   = JRequest::getVar('imported_data' , array());
		$unimported_data = JRequest::getVar('unimported_data' , array());
		$helper			 = PaycartFactory::getInstance('ImportFromCSV' , 'helper');
		$helper->importCSV($start , $total , 'product' , $unimported_data , $imported_data);
	}
	
	// function to export data as csv
	public function export($tpl = null)
	{
		$model			= $this->getModel();
		$start			= JRequest::getVar('start', 0, 'int');
		$filename		= JRequest::getVar('filename');
		$export_fields	= $this->getFields();
		
		$helper	= PaycartFactory::getInstance('ExportToCSV' , 'helper');
		$helper->exportToCSV('product' , $start , $model , $export_fields , $filename);
	}
	
	// function to set the export fields
	public function getFields()
	{
		return array('product_id',
					 'title',
					 'sku',
					 'alias',
					 'productcategory_id',
					 'type',
					 'price',
					 'retail_price',
					 'cost_price',
					 'quantity',
					 'weight',
					 'height',
					 'length',
					 'width',
					 'metadata_title',
					 'metadata_keywords',
					 'metadata_description',
					 'lang_code',
					 'product_lang_id');
	}
	
	public function download()
	{
		return true;
	}
}