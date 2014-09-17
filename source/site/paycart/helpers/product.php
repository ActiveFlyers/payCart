<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 		PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 * @author 		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Product Helper
 */
class PaycartHelperProduct extends PaycartHelper
{	
	/**
	* @return array of availble product types.
	*/
	public static function getTypes() 
	{
		return 
			Array(
					Paycart::PRODUCT_TYPE_PHYSICAL		=>	'COM_PAYCART_PRODUCT_TYPE_PHYSICAL',
					Paycart::PRODUCT_TYPE_DIGITAL		=>	'COM_PAYCART_PRODUCT_TYPE_DIGITAL'	
				  );
	}	
		
	/**
	 * Enter description here ...
	 * Array (	'keyword'	 => Keyword search data,
	 *			'filter'	 =>	Filtered data,
	 *			'limitstart' =>	Start limit,
	 *			'limit'		 => end limit,
	 *			'ordering'	 => Search Ordering (eithre asending or desending)
	 * 		  )
	 * 
	 */
	public function XXgetSearchData($data)
	{
		if(is_array($data)) {
			$data = (Object)$data;
		}
		
		$rows 	= Array();
				
		if (isset($data->keyword) && !empty($data->keyword)) {
			$row[]	=	PaycartFactory::getHelper('productindex')->getData($data);
		}
		
		if (isset($data->filter)) {
			$row[]	=	PaycartFactory::getHelper('productfilter')->getData($data);
		}
		
		$results = Rb_HelperPlugin::trigger('onPayCartProductSearch', $data);
		
		
		foreach ($results as $result) {
			$rows = array_merge((array) $rows, (array) $result);
		}
		
		return $rows;
	}

	/**
	 * Create new variation of Product. 
	 */
	public function addVariant(PaycartProduct $product)
	{
		$newProduct = PaycartProduct::getInstance();
		
		// prepare bind data
		$data = $product->toArray();
		foreach(array('product_id', 'variation_of', 'created_date', 'modified_date', 'cover_media' ) as $key){
			unset($data[$key]);
		}		
		$data['variation_of'] = $product->getId();		
		$newProduct->bind($data);
		
		
		// set attribute values
		$newProduct->set('_attributeValues', $product->getAttributeValues());
		
		// fetch all the language records and save one by one
		$records   = PaycartFactory::getModel('product')->loadLanguageRecords(array('product_id' => $product->getId()));
		
		foreach ($records as $record){
			unset($record->product_id);
			$record->product_lang_id = 0;
			$data = (array)$record;
			$newProduct->bind($data)->save();
		}

		// copy all images
		$images = $product->getImages();
		$cover_media = $product->getCoverMedia(false); 
		$media_ids = array();
		$config = PaycartFactory::getConfig();
		
		$newCoverMedia = 0;
		foreach($images as $image){	
			$image_id = $image['media_id'];
			
			$media = PaycartMedia::getInstance();
			$data = array();
			foreach(array('media_id', 'filename', 'media_lang_id' ) as $key){
				unset($image[$key]);
			}
			$media->bind($image);
			$media->save();			
			
			$media->moveUploadedFile($image['path_original'], JFile::getExt($image['path_original']));
			$media->createThumb($config->get('catalogue_image_thumb_width'), $config->get('catalogue_image_thumb_height'));
			$media->createOptimized($config->get('catalogue_image_optimized_width'),$config->get('catalogue_image_optimized_height'));
			
			if($image_id == $cover_media){
				$newCoverMedia = $media->getId();
			}
						
			$media_ids[] = $media->getId();
		}
		
		$newProduct->set('cover_media', $newCoverMedia);
		$newProduct->setImages($media_ids);
		$newProduct->save();
		return $newProduct;
	}

	/**
	 * 
	 * Process and build required data for selector attributes
	 * In this processing, we treat base attribute and remaining attributes differently
	 * Here we build the attribute options that will be displayed in selector
	 * 
	 * @param Array $records  		  : attributeId => containing totalProducts, totalAttrValue and
	 * 											       comma separated values of attribute that are used in product variants
	 * @param int $baseAttrId         : Id of attribute on which other selectors will be dependent
	 * @param Array $variants         : variants of current product
	 * @param PaycartProduct $product : Current product
	 * 
	 * @return array containing data that will be used to generate selector attributes
	 */
	public function buildSelectorAttributes($records, $baseAttrId, $variants, $product)
	{
		$productIds = array_keys($variants);
		$attributes = array();
		
		//for base attribute
		$productAttributeValue = PaycartFactory::getModel('productattributevalue')->loadProductAttributeValue($baseAttrId, $productIds);
		$attributes[$baseAttrId]['options'] 	  = array_keys($productAttributeValue);
		$attributes[$baseAttrId]['selectedvalue'] = $product->getAttributeValues($baseAttrId);
		
		unset($records[$baseAttrId]);
		
		//for other attributes
		foreach ($records as $key => $record){

			// select only those products that have their
			// base attribute value = current product's value for base filter 
		 	$condition = 'product_id IN(select product_id from #__paycart_productattribute_value 
		 				  where productattribute_id = '.$baseAttrId.'
		 				  and productattribute_value = '.$product->getAttributeValues($baseAttrId).'  
		 				  and product_id IN('.implode(',', $productIds).'))';		
		 	
		 	$productAttributeValue = PaycartFactory::getModel('productattributevalue')->loadProductAttributeValue($key, $productIds, $condition);
		 	
		 	$attributes[$key]['options'] 	   = array_keys($productAttributeValue);
		 	$attributes[$key]['selectedvalue'] = $product->getAttributeValues($key);
		}
		
		return $attributes;
	}

	/**
	 * Decrease the quanity of given products 
	 * @param array $productParticulars : array of stdclass of product particulars
	 */
	public function updateProductStock(Array $productParticulars = array())
	{
		foreach ($productParticulars as $particular){
			$productId = $particular->particular_id;
			$quantity  = $particular->quantity;
			if(!PaycartFactory::getModel('product')->updateStock($productId, $quantity)){
				return false;
			}
		}
		
		return true;
	}
}
