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
	 * Create new variation of Product. 
	 */
	public function addVariant(PaycartProduct $product, $isLinkingRequired = true)
	{
		$newProduct = PaycartProduct::getInstance();
		
		// prepare bind data
		$data = $product->toArray();
		foreach(array('product_id', 'variation_of', 'created_date', 'modified_date', 'cover_media' , 'alias', 'product_lang_id','config_digital','quantity_sold','hits') as $key){
			unset($data[$key]);
		}		
		
		if($isLinkingRequired){
			$data['variation_of'] = $product->getVariationOf();
		}		
		$newProduct->bind($data);
		
		
		// set attribute values
		$newProduct->set('_attributeValues', $product->getAttributes());

		// save the records first, it will copy the same data in all the language
		$newProduct->save();
		
		// fetch all the language records and update one by one
		$parentRecords  = PaycartFactory::getModel('product')->loadLanguageRecords(array('product_id' => $product->getId()));
		$records   		= PaycartFactory::getModel('product')->loadLanguageRecords(array('product_id' => $newProduct->getId()), 'lang_code');
		
		foreach ($parentRecords as $record){
			unset($record->product_id);
			// IMP : Unset alias, so that it can be generated automatically
			$record->alias = '';
			$record->product_lang_id = $records[$record->lang_code]->product_lang_id;
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
			$data  = array();
			$filename =  Jfile::stripExt(JString::str_ireplace('-'.$image_id.'.'.JFile::getExt($image['path_original']), '.'.JFile::getExt($image['path_original']),$image['filename']));
			foreach(array('media_id', 'media_lang_id' ) as $key){
				unset($image[$key]);
			}
			$media->bind($image);
			$media->save();			
			
			$media->moveUploadedFile($image['path_original'],$filename,JFile::getExt($image['path_original']),false);
			$media->createThumb($config->get('catalogue_image_thumb_width'), $config->get('catalogue_image_thumb_height'));
			$media->createOptimized($config->get('catalogue_image_optimized_width'),$config->get('catalogue_image_optimized_height'));
			
			if($image_id == $cover_media){
				$newCoverMedia = $media->getId();
			}
						
			$media_ids[] = $media->getId();
		}
		
		//copy all digital files 
		if($product->getType() == Paycart::PRODUCT_TYPE_DIGITAL){
			$digital = $product->getDigitalContent();
			
			if(!empty($digital)){
				$digitalIds = array();
				foreach ($digital as $digital){
					//main file
					$media = PaycartMedia::getInstance();
					$data  = array();
					$filename =  Jfile::stripExt(JString::str_ireplace('-'.$digital['main']['media_id'].'.'.JFile::getExt($digital['main']['filename']), '.'.JFile::getExt($digital['main']['filename']),$digital['main']['filename']));
					foreach(array('media_id', 'media_lang_id' ) as $key){
						unset($digital['main'][$key]);
					}
					$media->bind($digital['main']);
					$media->setBasePath(PAYCART_PATH_MEDIA_DIGITAL_MAIN);
					$media->save();	
					$media->moveUploadedFile(PAYCART_PATH_MEDIA_DIGITAL_MAIN.$digital['main']['filename'],$filename,JFile::getExt($digital['main']['filename']),false);
					$mainId = $media->getId();
					$digitalIds[$mainId]['main'] = $mainId;
					
					//teaser file
					if(!empty($digital['teaser'])){
						$media = PaycartMedia::getInstance();
						$data  = array();
						$filename =  Jfile::stripExt(JString::str_ireplace('-'.$digital['teaser']['media_id'].'.'.JFile::getExt($digital['teaser']['filename']), '.'.JFile::getExt($digital['teaser']['filename']),$digital['teaser']['filename']));
						foreach(array('media_id', 'media_lang_id' ) as $key){
							unset($digital['teaser'][$key]);
						}
						$media->bind($digital['teaser']);
						$media->setBasePath(PAYCART_PATH_MEDIA_DIGITAL_TEASER);
						$media->save();	
						$media->moveUploadedFile(PAYCART_PATH_MEDIA_DIGITAL_TEASER.$digital['teaser']['filename'],$filename,JFile::getExt($digital['teaser']['filename']),false);
						$digitalIds[$mainId]['teaser'] = $media->getId();
					}
					
				} 
			}			
			$newProduct->setDigitalFiles($digitalIds);
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
		$attributes[$baseAttrId]['selectedvalue'] = $product->getAttributes($baseAttrId);
		
		unset($records[$baseAttrId]);
		
		//for other attributes
		foreach ($records as $key => $record){

			// select only those products that have their
			// base attribute value = current product's value for base filter 
		 	$condition = 'product_id IN(select product_id from #__paycart_productattribute_value 
		 				  where productattribute_id = '.$baseAttrId.'
		 				  and productattribute_value = '.$product->getAttributes($baseAttrId).'  
		 				  and product_id IN('.implode(',', $productIds).'))';		
		 	
		 	$productAttributeValue = PaycartFactory::getModel('productattributevalue')->loadProductAttributeValue($key, $productIds, $condition);
		 	
		 	$attributes[$key]['options'] 	   = array_keys($productAttributeValue);
		 	$attributes[$key]['selectedvalue'] = $product->getAttributes($key);
		}
		
		return $attributes;
	}

	/**
	 * Decrease the quanity of given products 
	 * @param array $productParticulars : array of stdclass of product particulars
	 */
	public function consumeProductStock(Array $productParticulars = array())
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
	
	/**
	 * Increase the quanity of given products 
	 * @param array $productParticulars : array of stdclass of products having id and quantity
	 */
	public function refillProductStock($products)
	{
		foreach ($products as $id => $product){
			
			if(is_object($product))
			{
				$product_id = $product->product_id;
				$quantity   = $product->quantity;
			}
			else {
				$product_id = $product['product_id'];
				$quantity   = $product['quantity'];
			}
			if(!PaycartFactory::getModel('product')->updateStock($product_id, $quantity,true)){
				return false;
			}
		}
		
		return true;
	}

	/**
	 * delete the given image from product
	 */
	function deleteImage(PaycartProduct $product, $imageId)
	{
		$result = $product->deleteImages(array($imageId))->save();
		
		if($result){
			$imageIds   = $product->getImages(false);
			$coverMedia = $product->getCoverMedia(false);
			$mediaId    = 0;
			
			//if the deleted image is not cover media then do nothing
			if(!empty($imageIds) && $coverMedia != $imageId){
				return $result;
			}
			
			if(!empty($imageIds) && $coverMedia == $imageId){
				$mediaId = array_shift($imageIds);	
			}
			
			$product->setCoverMedia($mediaId)->save();
		}
		return $result;
	}

	/**
	 * update all variants and set variation_of property with one of the variants' id
	 * it is required when base product is being deleted
	 */
	function updateVariationOf(Array $variants = array())
	{
		if(empty($variants)){
			return true;
		}
		
		$baseProduct = key($variants);
		
		return PaycartFactory::getModel('product')->updateVariationOf(array_keys($variants),$baseProduct);
	}
	
	/**
	 * check if the given productId is in stock or not
	 */
	function isProductInStock($productId)
	{
		$product = PaycartProduct::getInstance($productId);
		
		if($product instanceof PaycartProduct){
			return ($product->getQuantity() > 0)?true:false;
		}
		
		return false;
	}

	public function getPositions()
	{
		return array('product-overview', 'product-addons', 'product-details', 'product-specifications');
	}
	
	/**
	 * invoke to get detail about all teaser digital files of a product
	 */
	public function getTeaserDigitalContent($productId)
	{
		$digitalData = PaycartProduct::getInstance($productId)->getDigitalContent();

		$teaserData = array();
		foreach ($digitalData as $data){
			if(empty($data['teaser']['filename'])){
				continue;
			}
			$teaserData[$data['teaser']['media_id']] = $data['teaser'];
		}
		return $teaserData;
	}
	
	/**
	 * invoke to get detail about all main digital files of a product
	 */
	public function getMainDigitalContent($productId)
	{
		$digitalData = PaycartProduct::getInstance($productId)->getDigitalContent();

		$mainData = array();
		foreach ($digitalData as $data){
			$mainData[$data['main']['media_id']] = $data['main'];
		}
		return $mainData;
	}
	
	/**
	 * delete the given digital content row from product
	 */
	function deleteDigitalContent(PaycartProduct $product, $mainId, $teaserId)
	{
		$result = $product->deleteDigitalContent($mainId,$teaserId)->save();
		return $result;
	}
	
}
