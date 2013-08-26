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
	protected $attribute_id	 =	0; 
	protected $title 		 =	null;
	protected $published	 =	1;
	protected $visible		 =	1;
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
		$this->searchable	 =	0;
		$this->type 		 =	0;
		$this->default 		 =	0;
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
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::save()
	 * Formating here before save content
	 */
	public function save()
	{
//		if(!$this->created_by) {
//			$this->created_by = Rb_Factory::getUser()->get('id');
//		}
		
		return parent::save();
	}
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::_save()
	 * After Attribute creation we build xml by using atrribute config and we need attribute id 
	 * so save opration excute twice times.  
	 */
	protected function _save($previousObject)
	{
		//@PCTODO :: if $previousObject is exist then first build xml then save it, no need to save twice time 
		$id = parent::_save($previousObject);
		
		if(!$id){
			return false;
		}
		// set id to $this 
		$this->setId($id);
		
		// Create XML for Attribute
		$this->xml = $this->_buildFieldXML();
		//@PCTODO :: check previous formate if xml dont have any change then no need to re-save 
		return parent::_save($previousObject);
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * 
	 * Create xml element for xml column
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
				$field .= "<option value='$value'>$value</option>";
			}
		}

		$field .= ' </field>';
		//@PCTODO :: only store Attribute configuration
		$fieldset = 
					"<fields name='attributes'>".
					"	<fields name='{$this->getId()}'>".
							$field.
						    "<field name='order' type='hidden' /> ".
					"	</fields>".
					"<fields>"
				;
		
		return $fieldset;
	}
}
