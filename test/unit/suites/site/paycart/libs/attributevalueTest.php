<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

/** 
 * Test AttributeValue Lib 
 */
class PaycartAttributeValueTest extends PayCartTestCase
{
	
	/**
	 * Test design structure of PayCartCategory class.  
	 * Test => Class Structure + Structure of Class Behavior
	 * 
	 * @return void 
	 */
	public function testClassDesign() 
	{
		$reflection  = new ReflectionClass('PaycartAttributeValue');
		//test parent class
		$this->assertEquals('PaycartLib', $reflection->getParentClass()->getname(), 'Check Parent Class');
  		
  		$expectedProperty = Array ( 
		  							'attributevalue_id', 'attribute_id', 
		  							'product_id','value', 'order', '_attribute'	 
  								 );							 
  		//test class property
  		$this->assertSame($expectedProperty, PaycartTestReflection::getClassAttribute('PaycartAttributeValue'));
  		//@PCTODO:: TEST method name and thier params
	}
	
	/**
	 * Test class instance created properly
	 * 
	 * @depends testClassDesign
	 * @return void
	 */
	public function testGetInstance()
	{
		$this->assertInstanceOf('PaycartAttributeValue', PaycartAttributeValue::getInstance());
	}
	
	/**
	 * Test default values of class insance
	 * 
	 * @depends testGetInstance
	 * @return void
	 */
	public function testReset() 
	{
		$instance = PaycartAttributeValue::getInstance();
		// test default values
		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'attributevalue_id'));
		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'attribute_id'));
		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'product_id'));
		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'value'));
		$this->assertSame(null, PaycartTestReflection::getValue($instance, '_attribute'));
		// test return type
		$this->assertInstanceOf('PaycartAttributeValue', $instance);
	}
	
	/**
	 * Test bind data on lib
	 *
	 * @todo    Implement testBind().
	 *
	 * @return  void
	 */
	public function testBind()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
	
	/**
	 * Test FormatValue method 
	 *
	 * @todo    Implement testFormatValue().
	 *
	 * @return  void
	 */
	public function testFormatValue()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}	
}
