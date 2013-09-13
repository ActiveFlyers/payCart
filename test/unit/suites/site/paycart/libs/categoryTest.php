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

class PaycartCategoryTest extends PayCartTestCase
{
	/**
	 * (non-PHPdoc)
	 * @see test/unit/PayCartTestCase::setUp()
	 */
	protected function setUp() 
	{
		// load stub class
		require_once __DIR__.'/stubs/lib.php';
		parent::setUp();
		;
	}
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
  		$this->assertEquals($expectedProperty, PaycartTestReflection::getClassAttribute('PaycartCategory'));
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
		// Mock Juser class
		$mockUser 	= $this->getMockBuilder('Juser',  Array('get'))
         			 ->disableOriginalConstructor()
         			 ->setMethods(Array('get'))
                     ->getMock();
        // Get fun call only one time with id param
		$mockUser->expects($this->once())
				  ->method('get')
				  ->with($this->equalTo('id'))
				  ->will($this->returnValue(52));
        
		// Mock PaycartTableCategory class
		$mockTable 	= $this->getMockBuilder('PaycartTableCategory')
                     ->disableOriginalConstructor()
                     ->getMock();
        // Call one time with 'testing_title' and 5 param
        $mockTable->expects($this->once())
				  ->method('getUniqueAlias')
				  ->with($this->equalTo('testing_title'), $this->equalTo(5))
				  ->will($this->returnValue('alias'));
		
		// Create a stub for the SomeClass class.
        $stub = $this->getMockBuilder('PaycartCategory')
         			 ->setMethods(Array('getId', 'getTitle'))
                     ->getMock();
        $stub->expects($this->once())
				  ->method('getId')
				  ->will($this->returnValue(5));
		$stub->expects($this->once())
				  ->method('getTitle')
				  ->will($this->returnValue('testing_title'));

		// check return object should be same
		$this->assertSame($stub, $stub->save($mockUser, $mockTable));      
	}

}
