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
 * Attribute Lib
 */
class PaycartAttribute extends PaycartLib
{
	// Table field
	protected $attribute_id	 =	0; 
	protected $title 		 =	null;
	protected $published	 =	1;
	protected $visible		 =	1;
	protected $filterable	 = 	0;
	protected $searchable	 =	0;
	protected $type 		 =	0;
	protected $default		 =  0;
	protected $params 		 =	null;
	protected $xml	 		 =	null;
	protected $class		 = 	null;
//	protected $created_by	 =	0;
//	protected $created_date  =	'';	
//	protected $modified_date =	''; 	
	
	public function reset() 
	{		
		$this->attribute_id	 =	0; 
		$this->title 		 =	null;
		$this->published	 =	1;
		$this->visible		 =	1;
		$this->filterable	 =	0;
		$this->searchable	 =	0;
		$this->type 		 =	null;
		$this->default 		 =	null;
		$this->params 		 =	new Rb_Registry();
		$this->xml 		 	 =	null;
		$this->class		 = 	null;
//		$this->created_by	 =	0;
//		$this->created_date  =	Rb_Date::getInstance();	
//		$this->modified_date =	Rb_Date::getInstance(); 	
		
		return $this;
	}
	
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('attribute', $id, $data);
	}	
	
	/**
	 * bind XML field on lib object
	 * 
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::bind()
	 */
	public function bind($data, $ignore=array()) 
	{
		if(is_object($data)){
			$data = (array) ($data);
		}

		// Parent bind	
		parent::bind($data, $ignore);

		//Format XML Value
		$this->xml = $this->_buildFieldXML();
		
		return $this;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * 
	 * Create xml element for xml column
	 * 
	 * @return field-xml
	 */
	protected function _buildFieldXML()
	{
		$attributeConfig = $this->params->get('attribute_config');
		
		// required hard binding {Field label nd name }
		$field = " <field 
						name  = 'value'
						label = '{$this->title}' 
		         ";
		
		// any applied css class
		if($this->class) {
			$field .= " class= '{$this->class}'";
		}
		
		// Default value
		if($this->default) {
			$field .= " default= '{$this->default}'";
		}
		
		//Other field attributes
		foreach ($attributeConfig as $name => $value) {
			if('options' == $name) { 
				// Next move it will be handled 
				continue;
			}
			$field .= " $name='$value' ";
		} 
		
		$field .=">";

		// handle here option type attributes like checkbox, radio, list etc
		if(isset($attributeConfig->options)) {
			// IMP :: Newline always behave like separator 
			$values = explode("\n", $attributeConfig->options);
			foreach ($values as $value) {
				$value = trim($value);
				$field .= "<option value='$value'>$value</option>";
			}
		}

		$field .= ' </field>';

		return $field;
	}
	
	public function formatValue($value)
	{
		return PaycartFactory::getHelper('attribute')->formatValue($this->type, $value);
	}
}
