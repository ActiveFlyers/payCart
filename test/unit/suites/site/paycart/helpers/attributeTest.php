<?php

/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/
/** 
 * Attribute Helper Test
 * @author 	mManishTrivedi
 */
class PaycartHelperAttributeTest extends PayCartTestCaseDatabase
{
	
	public $testgetAttributeXML = Array("/_data/dataset/attribute/attribute-1.php");
	
	/**
	 * 
	 * Test getAttributeXml method
	 * @param array $attributeIds
	 */
	public function testgetAttributeXML() 
	{
		
		$attributeIds = Array(2,1,3);
		
		// get actual xml
		$actualXML = PaycartHelperAttribute::getAttributeXML($attributeIds);
		
		// xml order is imp
		$expectedXML = '<form> <fields name="attributes"> <fieldset name="attributes">
							<fields name="2"> 
								<field name  = "value" label = "Attribute-2" class= "class-list" default= "option-22" type="list"  multiple="false"  size="3"  readonly="0"  onchange="" >
									<option value="option-21">option-21</option><option value="option-22">option-22</option><option value="option-23">option-23</option>
									<option value="option-24">option-24</option><option value="option-25">option-25</option> </field> <field name="order" type="hidden" /> 
							</fields>
							<fields name="1">
								<field name  = "value" label = "Attribute-1" class= "class-attribute-1" default= "deafault-attribut-1" type="text"  size="25"  maxlength="16"  readonly="0"  disabled="0" > 
								<field name="order" type="hidden" /> 
							</fields>
							<fields name="3">
								<field name  = "value"  label = "Attribute-A" default= "option-D" type="list"  multiple="true"  size="4"  readonly="0"  onchange="" >
									<option value="option-A">option-A</option><option value="option-B">option-B</option><option value="option-C">option-C</option>
									<option value="option-D">option-D</option><option value="option-E">option-E</option><option value="option-F">option-F</option> </field> 
								<field name="order" type="hidden" /> 
							</fields>
						</fieldset></fields></form>';
		// skip char
		$specialChar = Array("\n","\t"," ");
		
		$this->assertSame(str_replace($specialChar, "", $actualXML),  str_replace($specialChar, "", $actualXML));
		
	}
	
}
