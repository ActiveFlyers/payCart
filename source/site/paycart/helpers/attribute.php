<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		support+paycart@readybytes.in
 * @author 		mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Attribute Helper
 * @author 	mManishTrivedi
 */
class PaycartHelperAttribute extends PaycartHelper
{	
	
	/**
	 * Return ordered Attribute Cofiguration XML according to $attributeIds
	 * @param array $attributeIds : Ordered Array (sequential attribute ids )
	 */
	public static function getAttributeXML(Array $attributeIds ) 
	{
		//Get all attributes
		$condition	= Array('attribute_id' => Array(Array('IN', '('.implode(',',$attributeIds).')')));
		$attributes = PaycartFactory::getInstance('attribute','model')
									->loadRecords($condition); 
		
		// IMP NOTE::
		// <form> tag treat as start tag.(start tag will be ignor by SimpleXMLElement)
		// <fieldset> tag required for getting fields data for rendering
 		$xml =  '<form> <fields name="attributes"> <fieldset name="attributes">';
		// IMP :: order of $attributeIds define Attributes position.  
		foreach ($attributeIds as $attributeId ) {
			$xml .=  "	<fields name='$attributeId'> ".
					 "		{$attributes[$attributeId]->xml} ".
					 "		<field name='order' type='hidden' /> ".
					 "	</fields>";
		}	
		$xml .= '</fieldset></fields></form>';
		
		return $xml;
	}
	// Format Attribute Values
	public function formatValue($type, $value)
	{
		$fun = '_Format'.$type;
		return self::$fun($value);
	}
	
	protected function _FormatText($value)
	{
		return trim($value);
	}
	
	protected function _FormatList($value)
	{
		if(!is_array($value)) {
			$value = explode(',', $value);
		} 
		return $value;	
	}	
}
