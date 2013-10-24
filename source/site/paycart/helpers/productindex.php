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
		$attributes = PaycartFactory::getModel('attribute')->loadrecords(Array('searchable' => Array('=', 1, 'OR'),'filterable' => 1));
		
		// return true if non-searchable and non-filterable attribute
		if (!$attributes) {
			return true;
		}
		
		$attributeValue = $currentObject->get('_attributeValue');
		
		// collect filter stuff
		$fields	 = new stdClass();
		$content = Array();
		
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
				$content[] 	= $data['value'];
			}
		}
		
		// Collect indexer stuff
		$fields->content = '';

		// searchable stuff available
		if(!empty($content)) {
			$fields->content = implode(' ', $content);
		}
		
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
	
	
	public function XX_getData($data)
	{
		if (!isset($data->keyword) || !is_array($data->keyword) ) {
			throw new InvalidArgumentException(Rb_Text::_('COM_PAYCART_INVALID_KEYWORD'));
		}

		if(isset($data->keyword['word'])) {
			$this->keyword['word'] = $data->keyword['word'];
		}
		
		if(isset($data->keyword['phrase']) && $data->keyword['phrase'] ) {
			$this->keyword['phrase'] = $data->keyword['phrase'];
		}
		
		//how to treat keyword search
		$this->sentizeKeyWord();
		
		$this->model->getData(Array('index' => $this->keyword['word']));
		
	}
	
	/**
	 * 
	 * Sentize key-word according to predefine rules. 
	 */
	protected function sentizeKeyWord()
	{
		// trim bad char
		$this->keyword['word'] 	= trim(JString::str_ireplace($this->badchars, '', $this->keyword['word']));
		
		// if searchword enclosed in double quotes, strip quotes and do exact match
		if (substr($this->keyword['word'], 0, 1) == '"' && substr($this->keyword['word'], -1) == '"')
		{
			$this->keyword['word'] 		= JString::substr($this->keyword['word'], 1, -1);
			$this->keyword['phrase']	= 'exact';
		}
		
//		if ('any' == $this->keyword['phrase']) {
//		    /**
//		     * @PCTODO:: Break into multiple parts like word have "Samsung Mobile Phone" 
//		     * then keyword string is
//		     * Array(
//		     * "Samsung", "Samsung mobile", "Samsung phone",
//		     * "mobile", "mobile phone",
//		     * "phone", 
//		     * "phone mobile", "phone Samsung",
//		     * "mobile samsung",
//		     * "Samsung Mobile Phone", "Samsung Phone Mobile ",
//		     * "Mobile Phone Samsung", "Mobile Samsung Phone",
//		     * "Phone Mobile Samsung", "Phone Samsung Mobile"
//		     *  )
//		     */
//		}
//		
//		// IMP: keyword-word must be an array at the end of this method
//		if(!is_array($this->keyword['word'])) {
//			$this->keyword['word'] = (array) $this->keyword['word'];
//		}
	
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
