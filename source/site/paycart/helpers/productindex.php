<?php
/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		support+paycart@readybytes.in
 * @author 		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * PaycartHelper ProductIndex
 * @author manish, rimjhim jain
 *
 */
class PaycartHelperProductIndex
{
	public function __construct()
	{
		// get indexer model
		$this->model = PaycartFactory::getModel('productindex');
		
	}							
	
	/**
	 * Do index the required data of product
	 * @param $previousObject
	 * @param $product : object of PaycartProduct
	 */
	public function doIndexing($previousObject, PaycartProduct $product) 
	{
		$content = array();
		$field	 = new stdclass();
		$productId = $product->getId();
		
		//Step-1 : Prepare core data for indexing of all the languages
		$content[] = $product->getSku();
		
		$query 	  = new Rb_Query();
		$result   = $query->select('title, alias, description')
						  ->from('#__paycart_product_lang')
						  ->where('product_id = '.$productId)
						  ->dbLoadQuery()
						  ->loadObjectList();
						
		foreach ($result as $langCode => $data){
			$content[] = $data->title;
			$content[] = $data->alias;
			if(!empty($data->description)){
				$content[] = $data->description; 	
			}
		}

		//Step-2 : Also index category title from which the product belongs
		$catId = $product->getProductCategory();
		
		$query            = new Rb_Query();
		$categoryLangData = $query->select('title')
							  ->from('#__paycart_productcategory_lang')
							  ->where('productcategory_id = '.$catId)
							  ->dbLoadQuery()
							  ->loadColumn();
		foreach ($categoryLangData as $categoryTitle){
			$content[] = $categoryTitle;
		}
		
		//Step-3 : Prepare attribute data for indexing
		// get all attributes which are searchable
		$searchableAttributes = PaycartFactory::getModel('productattribute')
										      ->loadrecords(Array('searchable' => Array(Array('=', 1))),array(),false,'productattribute_id');

		//prepare attribute data only if any of the attribute is searchable											      
		if ($searchableAttributes) {
			$attributes  = $product->getAttributes();
			$optionsLang = array();
			foreach ($attributes as $attribute_id => $data){
				if(in_array($attribute_id, $searchableAttributes)){
					$attribute   = PaycartProductAttribute::getInstance($attribute_id, $searchableAttributes[$attribute_id]);
					$optionsLang = $attribute->getSearchableDataOfOption($data);
				}
			}
			
			foreach ($optionsLang as $option){
				$content[] = $option['title'];
			} 
		}	

		//Step-4 : Separate data through space and sanitize content before saving
		$indexData       = implode(" ", $content);
		$field->content	 = $this->sanitizeContent($indexData);
		
		// Step-5 : Save data
		if(!isset($previousObject)){
			$field->product_id = $productId;
			return $this->model->save($field,null,true);
		}
		return $this->model->save($field,$productId);
	}
	
	/**
	 * allow alphabets, numbers and spaces
	 * @param string $content
	 */
	function sanitizeContent($content)
	{
		return preg_replace('/[^\p{L}\p{N}\|\s]+/u','',strip_tags($content));
	}
}
