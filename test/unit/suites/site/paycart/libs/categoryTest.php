<?php

/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/

/**
 * 
 * Test PaycartCategory Lib 
 * @author mManishTrivedi
 */

class PaycartCategoryTest extends PayCartTestCaseDatabase
{
	/**
	 * Test design structure of PayCartCategory class.  
	 * Test => Class Structure + Structure of Class Behavior
	 * 
	 * @return void 
	 */
	public function testClassDesign() 
	{
		$reflection  = new ReflectionClass('PaycartCategory');
		//test parent class
		$this->assertEquals('PaycartLib', $reflection->getParentClass()->getname(), 'Check Parent Class');
  		
  		$expectedProperty = Array ( 
		  							'category_id', 'title', 'alias', 'description', 'published',	
									'parent', 'cover_media', 'params', 'created_by', 'created_date',		
									'modified_date'	 
  								 );
  								 
  		//test class property
  		$this->assertEquals($expectedProperty, PayCartTestReflection::getClassAttribute('PaycartCategory'));
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
		$this->assertInstanceOf('PaycartCategory', PaycartCategory::getInstance());
	}
	
	/**
	 * Test default values of class insance
	 * 
	 * @depends testGetInstance
	 * @return void
	 */
	public function testReset() 
	{
		$instance = PaycartCategory::getInstance();
		// test default values
		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'category_id'));
		$this->assertEmpty(PaycartTestReflection::getValue($instance, 'title'));
		$this->assertEmpty(PaycartTestReflection::getValue($instance, 'alias'));
		$this->assertEmpty(PaycartTestReflection::getValue($instance, 'description'));
		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'parent'));
		$this->assertEmpty(PaycartTestReflection::getValue($instance, 'cover_media'));
		$this->assertInstanceOf('Rb_Registry', PaycartTestReflection::getValue($instance, 'params'));
 		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'created_by'));
 		$this->assertInstanceOf('Rb_Date', PaycartTestReflection::getValue($instance, 'created_date'));
 		$this->assertInstanceOf('Rb_Date', PaycartTestReflection::getValue($instance, 'modified_date'));
 		// test return type
		$this->assertInstanceOf('PaycartCategory', $instance);
	}
	
	/**
	 * 
	 * Test PaycartCategory lib method
	 * 
	 */
	public function testSave()
	{
		// Mock Dependancy
		$session = PaycartFactory::$session;
		$options = Array(
						'get.user.id' 		=>  44,
						'get.user.name'		=> '_MANISH_TRIVEDI_',
						'get.user.username' => 'mManishTrivedi',
						'get.user.guest'	=>	0
						);
		// MockSession and set 44 user id in session
		PaycartFactory::$session = $this->getMockSession($options);

		// Test Alias auto creation as per title
		$data    = Array('title' => 'testing_title_1',);
		$instance = PaycartCategory::getInstance(0, $data);
		$instance->save();

		// Test: Alias should be unique
		$data = Array('title' => 'testing_title_2', 'alias'=>'cat-3');
		$instance = PaycartCategory::getInstance(0, $data);
		$instance->save();

		// Test: Alias should be unique and auto increment
		$data = Array('title' => 'testing_title_3', 'alias'=>'cat-3');
		$instance = PaycartCategory::getInstance(0, $data);
		$instance->save();
		
		$this->compareTable('jos_paycart_category', Array( 'cover_image', 'created_date', 'modified_date'));
		
		// revert cached stuff
		PaycartFactory::$session = $session ;		  
	}

}
