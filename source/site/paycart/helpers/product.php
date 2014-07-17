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
	 * @PCTODO: remove it if unused
	 * Translate alias to id.
	 *
	 * @param string $alias The alias string
	 *
	 * @return numeric value The Product id if found, or false/empty
	 */
	public static function XXX_translateAliasToID($alias) 
	{	
		$query 	= new Rb_Query();
		$result = $query->select('product_id')
						->where("`alias` = '$alias'")
			  			->from('#__paycart_product')
			  			->dbLoadQuery()->loadResult();
			  			
		return $result;	
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
	public function addVariant($product)
	{
		$newProduct 	= $product->getClone();
		$newProduct->set('variation_of',$product->getId());
		// New created variant will be always variation of ROOT product. 
		// @see Discuss#39
		if($variantOf = $product->getVariationOf()) {
			$newProduct->set('variation_of',$variantOf);
		}
		//### Attribute Changes in Variants
		//1. Product id and product lang id should be 0
		$newProduct->set('product_id', 0) ;
		
		//2. New image file name save nd create new after save
		if($product->getCoverMedia()) { 
			$extension = PaycartFactory::getConfig()->get('image_extension', Paycart::IMAGE_FILE_DEFAULT_EXTENSION);
			// set Image name  
			$newProduct->set('cover_media',PaycartHelper::getHash($product->getTitle()));
			$newProduct->set('cover_media', $product->getName().'/'.$newProduct->cover_media.$extension);
			// set source path. It will required on image processing
			$newProduct->set("_uploaded_files['cover_media']",$product->getCoverMedia());
		}
		
		//3. set attribute values
		$newProduct->set('_attributeValues',$product->getAttributeValues());
		
		//4. Changable Property 	
		$newProduct->set('created_date', Rb_Date::getInstance());	
		$newProduct->set('modified_date',Rb_Date::getInstance()); 
		
		//5. fetch all the language records and save one by one
		$records   = PaycartFactory::getModelLang('product')->loadRecords(array('product_id' => $product->getId()));
		
		foreach ($records as $record){
			$record->product_id = 0;
			$record->product_lang_id = 0;
			$data->language = (array)$record;
			$newProduct->bind($data)->save();
		}

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
}
