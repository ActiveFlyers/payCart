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
 * @author manish
 *
 */
class PaycartHelperProductIndex
{
	
	/**
	 * 
	 * Method invoke to insert and update operation perform on PaycartIndexer table
	 * @param $previousObject, Attribute Lib object
	 * @param $currentObject, Attribute Lib object
	 */
	public function indexing($previousObject, $currentObject) 
	{
		$attributes = PaycartFactory::getModel('attribute')->loadrecords(Array('searchable' => 1));
		
		// return true if non-searchable attribute
		if (!$attributes) {
			return true;
		}
		
		$attributeValue = $currentObject->get('_attributeValue');
		
		$content = Array();
		foreach ($attributes as $attributeId=>$attribute) {
			// if attribute not available on Product
			if(!isset($attributeValue[$attributeId])) {
				continue;
			}
			
			$data	= $attributeValue[$attributeId]->toDatabase();
			
			// set field value
			$content[] 	= $data['value'];
		}
		
		// Collect indexer stuff
		$fields = new stdClass();
		$fields->content = '';

		// searchable stuff available
		if(!empty($content)) {
			$fields->content = implode(' ', $content);
		}
		
		$fields->product_id = $currentObject->getId();
		
		// get indexer model
		$model = PaycartFactory::getModel('productindex');
		
		$record = $model->loadRecords(Array('product_id' => $fields->product_id ));
		
		// Check index already exist or insert new
		$indexerId = null;
		$new	= true;
		if (!empty($record)) {
			list($indexerId) = array_keys($record);
			$new = false;
		}
		
		// save indexed values
		return $model->save($fields, $indexerId, $new);
	}
	
}
