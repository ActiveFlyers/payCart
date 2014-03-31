<?php
/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/

/** 
 * Test Paycart Attribute Lib
 * @author mManishTrivedi
 */

class PaycartProductAttributeTest extends PayCartTestCaseDatabase
{
		
	/**
	 * Test design structure of PayCartCategory class.  
	 * Test => Class Structure + Structure of Class Behavior
	 * 
	 * @return void 
	 */
	public function testClassDesign() 
	{
		$reflection  = new ReflectionClass('PaycartProductAttribute');
		//test parent class
		$this->assertEquals('PaycartLib', $reflection->getParentClass()->getname(), 'Check Parent Class');
  		
  		$expectedProperty = Array ( 
		  							'productattribute_id', 'type', 'css_class', 'filterable', 
		  							'searchable','status','config','ordering','_language','_options'
  								 );

  		//test class property
  		$this->assertSame($expectedProperty, PayCartTestReflection::getClassAttribute('PaycartProductAttribute'));
  		//@PCTODO:: TEST method name and thier params
	}

	/**
	 * Test default values of class instance
	 * 
	 * @return void
	 */
	public function testReset() 
	{
		$instance = PaycartProductAttribute::getInstance(0);
		
		// test default reset
		$this->_testReset($instance);
		
		// Change $insatance value
		PaycartTestReflection::setValue($instance, 'type', 'color');
		PaycartTestReflection::setValue($instance, 'status', 'published');
		
		// Test value changed properly
		$this->assertSame('color',PaycartTestReflection::getValue($instance, 'type'));
		$this->assertSame('published',PaycartTestReflection::getValue($instance, 'status'));
		
		$instance->reset();
		
		//test after reseting instance
		$this->_testReset($instance);
	}
		
	protected function _testReset($instance)
	{	
		// test default values
		$this->assertSame(0, $instance->get('productattribute_id'));
		$this->assertSame('',$instance->get('status'));
		$this->assertSame('',$instance->get('type'));
		$this->assertSame(0, $instance->get('searchable'));
		$this->assertSame(0, $instance->get('filterable'));
		$this->assertInstanceOf('Rb_Registry', $instance->get('config'));
		$this->assertSame('',$instance->get('css_class'));
		$this->assertSame(0, $instance->get('ordering'));

		//language specific data
		$language        = new stdClass();
		$language->title = '';
		$language->productattribute_lang_id = 0;
		$language->productattribute_id 		= 0;
		$language->lang_code  				= PaycartFactory::getLanguageTag(); //Current Paycart language Tag
		
		$this->assertEquals($language, $instance->get('_language'));
		
		// test return type
		$this->assertInstanceOf('PaycartProductAttribute', $instance);
	}
	
	/**
	 * Test bind function
	 * 
	 * @return void
	 */
	public function testBind()
	{
		// Create a stub for the PaycartProduct class.
        $mockattr = $this->getMockBuilder('PaycartProductAttribute')
         			 ->setMethods(Array('getId','setLanguageData', 'setOptions'))
                     ->getMock();
                     
		// mock dependency
		$mockattr->expects($this->once())	// call on after bind
			 	 ->method('getId')
			     ->will($this->returnValue(0));
                     
        $mockattr->expects($this->once())
			     ->method('setLanguageData')
	 		     ->will($this->returnValue(false));
	 		 
	 	$mockattr->expects($this->once())
			     ->method('setOptions')
	 		     ->will($this->returnValue(false));
        
        //create mock object 
	    $mock = $this->getMock('PaycartAttributeColor',array('buildOptions'));
        $mock->expects($this->once())
        	 ->method('buildOptions')
	         ->will($this->returnValue(null));
	    
	    PayCartTestReflection::setValue('PaycartAttribute', 'instance', array('color' => $mock));
	 	
	 	$data = array('type' 	  => 'color',
	 				  'status' 	  => 'published',
	 				  'css_class' => 'testclass',
	 				  'searchable'=> 1,
					  'config'    => new Rb_Registry(),
	 				  'filterable'=> 1	 	
	 					);
	 					
	 	$ignore = array('filterable','config','ordering');
	 	
	 	$mockattr->bind($data,$ignore);
	 	
	 	foreach ($data as $key => $value){
	 		if(in_array($key,$ignore)){
	 			$this->assertFalse(isset($mockattr->$key));
				continue;
	 		}
	 		$this->assertEquals($value, $mockattr->get($key), $key. "was not properly binded");
	 	} 
	 	
	 	PayCartTestReflection::setValue('PaycartAttribute', 'instance', array());
	}
	
	
	public $testSetLanguageData =  
						Array(
								'_data/dataset/productattribute/productattribute-1.php'
							);
	/**
	 * 
	 * Enter description here ...
	 */
	public function testSetLanguageData()
	{		        
	    //create mock object 
	    $mock = $this->getMock('PaycartAttributeSelect',array('buildOptions'));
        $mock->expects($this->once())
        	 ->method('buildOptions')
	         ->will($this->returnValue(null));
	    
	    PayCartTestReflection::setValue('PaycartAttribute', 'instance', array('select' => $mock));
		
	    // Dummy instance and empty data
		$instance = PaycartProductAttribute::getInstance();
		$this->assertFalse(PayCartTestReflection::invoke($instance, 'setLanguageData'));
	    
		//set a property
		$instance = PaycartProductAttribute::getInstance(1);
		//var_dump($instance);
		$data = array('title' => 'size');
		
		$instance->setLanguageData($data);
		
		$this->assertEquals('size',$instance->getTitle(), "title didn't match");
		
		PayCartTestReflection::setValue('PaycartAttribute', 'instance', array());
	}
	
	
	/**
	 * 
	 * Test _save method
	 * @PCTODO:: test with various data-type
	 * @return void
	 */
	public function test_Save() 
	{
		$data = Array(
						'type'		=>	'select',
					    'css_class' =>	'class-attribute-1',
					    'ordering' 	=>	0,
						'status'	=> 'published',
						'_language' => array(
											 'title' => 'Attribute-1'
											)
					);
	
		$instance 	 = PaycartProductAttribute::getInstance(0,$data);
		$attributeId = PayCartTestReflection::invoke($instance, '_save', null);

		// We have already loaded attribute_id one (into our dataset)  so always next created id must be 2
		$this->assertEquals(1, $attributeId);

		// Count of the row
		$this->assertEquals(1, $this->getConnection()->getRowCount('jos_paycart_productattribute'));
		
		// get Current dataset 
		$queryTable	= $this->getConnection()->createDataSet(Array('jos_paycart_productattribute'));
		
		// Expected data
		list($row,$rowLang)	 = $this->auDataAttribute();
		$au_data = Array( "jos_paycart_productattribute" => Array($row[1]),
						  "jos_paycart_productattribute_lang" => array($rowLang[1])
						  );
		
		$this->compareTables(array_keys($au_data),$au_data, array('jos_paycart_productattribute' => array()));
	}
	
	public function auDataAttribute() 
	{
		$row 	= Array();
		
		list($tmpl, $tmplLang) = include RBTEST_PATH_DATASET.'/productattribute/tmpl.php';
		
		$row[0] = array_merge(Array('productattribute_id'=>0), $tmpl);
									   	
		$row[1]	= array_replace($row[0], Array(  'productattribute_id' => 1, 'type'=>'select',
												 'css_class'=>'class-attribute-1','ordering'=>1, 'status' => 'published'
											));
													
		$rowLang[0] 	= array_merge(Array('productattribute_lang_id'=>0), $tmplLang);

		$rowLang[1]	= array_replace($rowLang[0], Array(  'productattribute_lang_id' => 1,'title'=>'Attribute-1',
														 'productattribute_id' => 1,'lang_code'=> 'en-GB' 
													  ));
													
		return array($row,$rowLang);
	}
	
	protected function tearDown()
	{
		parent::tearDown();
	}
}
