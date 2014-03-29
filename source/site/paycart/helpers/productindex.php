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
	
	protected $badchars = Array(
								'#',			//  # causes problems. 
								'>', '<', 		//	<> get stripped anyway later on
								'\\'			//  slashes cause errors
								);
								
	protected $keyword	= Array( 
								'word'		=> '',		// Keyword search perform on this word				
								'phrase'	=> 'any'	// Match case : Either any or exact			
								);

								
	public function __construct()
	{
		// get indexer model
		$this->model = PaycartFactory::getModel('productindex');
		
	}							
	
	/**
	 * 
	 * Method invoke to insert and update operation perform on PaycartIndexer table
	 * @param $previousObject, Attribute Lib object
	 * @param $currentObject, Attribute Lib object
	 */
	public function indexing($previousObject, $currentObject) 
	{
		// get all attributes which are filterable and searchable
		$attributes = PaycartFactory::getModel('productattribute')
						->loadrecords(Array('searchable' => Array(Array('=', 1, 'OR')), 'filterable' => Array(Array('=', 1, 'OR'))));
		
		// return true if non-searchable and non-filterable attribute
		if (!$attributes) {
			return true;
		}
		
		$attributeValue = $currentObject->get('_attributeValue');
		
		// collect filter stuff
		$fields	 = new stdClass();
		
		// Collect indexer stuff
		$fields->content = '';
		
		foreach ($attributes as $attributeId=>$attribute) {
			// if attribute not available on Product
			if(!isset($attributeValue[$attributeId])) {
				continue;
			}
			
			$data	= $attributeValue[$attributeId]->toDatabase();
			
			// if attribute is filterable
			if ($attribute->filterable) {
				$column 	= Paycart::PRODUCT_FILTER_FIELD_PREFIX.$attributeId;
				$data		= $attributeValue[$attributeId]->toDatabase();
			
				// set field value
				$fields->$column = $data['value'];
			}
			
			//if attribute is searchable
			if ($attribute->searchable) {
				// set content column value / indexed data
				$fields->content .= $data['value']. ' ';
			}
		}

		// trim start and last space
		$fields->content= trim($fields->content);
		
		// set primary key value
		$fields->product_id = $currentObject->getId();
		
		// check reocrd already exist or not
		$record = $this->model->loadRecords(Array('product_id' => $fields->product_id ));
		
		// Check index already exist or insert new
		$indexerId = null;
		$new	= true;
		if (!empty($record)) {
			list($indexerId) = array_keys($record);
			$new = false;
		}
		
		// save indexed values
		return $this->model->save($fields, $indexerId, $new);
	}
	
	/**
	 * 
	 * Method invoke to check column exist or not
	 * @param String $column : Column name
	 * 
	 * @return (bool)type, True If column exist other-wise false
	 */
	public function checkColumn($column)
	{
		$table 	= PaycartFactory::getTable('productindex');
		$fields = $table->getFields();
		return (bool)array_key_exists($column, $fields);
	}	
	
	/**
	 * 
	 * Method invoke to add/remove (Alter) operation perform on PaycartIndeaxer table
	 * @param $previousObject, Attribute Lib object
	 * @param $currentObject, Attribute Lib object
	 */
	public function alterColumn($previousObject, $currentObject) 
	{
		// look like atribute_1, attribute_4 etc
		$column	= Paycart::PRODUCT_FILTER_FIELD_PREFIX.$currentObject->getId();
		
		//  Case 1:: if {$previousObject}attribute filterable AND {$currentObject}attribute non-filterable 
		//	then remove column
		if ( $previousObject && (bool)$previousObject->get('filterable') && false == (bool)$currentObject->get('filterable')) {
			$this->removeColumn($column);
			return true;
		}
		
		//  Case 2:: if ( {$previousObject}attribute not exits OR non-filterable ) AND {$currentObject}attribute filterable 
		//	then create column for it.
		//  Case 3:: if {$previousObject}attribute filterable AND {$currentObject}attribute also filterable 
		// 	then check column exist (then return) else create column
		if ( (bool)$currentObject->get('filterable') && !$this->checkColumn($column)) {
			$definition = $this->getColumnDefinition($currentObject->get('type'));
			$this->createColumn(Array($column =>$definition) );
			return true;
		}
		
		return true;
	}
	
	/**
	 * 
	 * Invoke to remove column from PayCart filter table
	 * @param String $column, Column name
	 * 
	 * @return (bool) true if successfully deleted
	 */
	public function removeColumn($column)
	{
		if($this->checkColumn($column)) {
			$this->model->dropColumn($column);
		}
		
		return true;	
	}
	
	/**
	 * 
	 * Invoke to create column in PayCart filter table
	 * @param String $column, Column name
	 * 
	 * @return (bool) true if successfully Created
	 */
	public function createColumn(Array $column)
	{
		$this->model->addColumn($column);
		return true;
	}
	
	/**
	 * 
	 * Invoke to get column definition according to attribute type
	 * @param string $type, Attrinute type
	 * 
	 * @return string type, Column definition
	 */
	public function getColumnDefinition($type)
	{
		switch ($type)
		{
			case 'date':
				$definition = ' DATETIME ';
				break;
			case 'text':
				$definition = ' VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci ';
				break;
			case 'textarea':
			case 'list':
			default:
				$definition = ' VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci ';
		}
		return $definition;
	}
	
}
