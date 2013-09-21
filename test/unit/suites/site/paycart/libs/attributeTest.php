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
class PaycartAttributeTest extends PayCartTestCaseDatabase
{
	protected $sqlDataSet = false;
		
	/**
	 * Test design structure of PayCartCategory class.  
	 * Test => Class Structure + Structure of Class Behavior
	 * 
	 * @return void 
	 */
	public function testClassDesign() 
	{
		$reflection  = new ReflectionClass('PaycartAttribute');
		//test parent class
		$this->assertEquals('PaycartLib', $reflection->getParentClass()->getname(), 'Check Parent Class');
  		
  		$expectedProperty = Array ( 
		  							'attribute_id', 'title', 'published', 'visible', 
		  							'searchable',	'type','default', 
		  							'params', 'xml', 'class'
  								 );							 
  		//test class property
  		$this->assertSame($expectedProperty, PayCartTestReflection::getClassAttribute('PaycartAttribute'));
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
		$this->assertInstanceOf('PaycartAttribute', PaycartAttribute::getInstance());

		//PCTODO : test for id for data,  and id for unavailable data
		
	}

	/**
	 * Test default values of class insance
	 * 
	 * @depends testGetInstance
	 * @return void
	 */
	public function testReset() 
	{
		$instance = PaycartAttribute::getInstance();

		// test default values
		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'attribute_id'));
		$this->assertSame(null, PaycartTestReflection::getValue($instance, 'title'));
		$this->assertSame(1, PaycartTestReflection::getValue($instance, 'published'));
		$this->assertSame(1, PaycartTestReflection::getValue($instance, 'visible'));
		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'searchable'));
		$this->assertSame(null, PaycartTestReflection::getValue($instance, 'type'));
		$this->assertSame(null, PaycartTestReflection::getValue($instance, 'default'));
		$this->assertInstanceOf('Rb_Registry', PaycartTestReflection::getValue($instance, 'params'));
		$this->assertSame(null, PaycartTestReflection::getValue($instance, 'xml'));
		$this->assertSame(null, PaycartTestReflection::getValue($instance, 'class'));

		// test return type
		$this->assertInstanceOf('PaycartAttribute', $instance);

		//PCTODO:: Change $instance value and invoke reset method
	}
	
	/**
	 * 
	 * Test bind and  _save method
	 * 
	 * @return void
	 */
	public function test_save() 
	{
		$data = Array
					(
						'title' 	=>	'title_text',
						'type'		=>	'text',
						'default' 	=>	'default_text',
					    'class' 	=>	'testing_class',
					    'searchable'=>	0,
					    'published' =>	1,
					    'visible' 	=>	1,
					    'ordering' 	=>	'',
					    'params'	=> 	Array(
					    					'attribute_config'=>Array(
					    								"type"=>"text","size"=>"50",
					    								"maxlength"=>"25","readonly"=>"1",
					    								"disabled"=>"1")),
					    'xml' 		=>	''
					);
	
		$instance 	 = PaycartAttribute::getInstance(0,$data);
		$attributeId = PayCartTestReflection::invoke($instance, '_save', null);

		// We have already loaded attribute_id one (into our dataset)  so always next created id must be 2
		$this->assertEquals(2, $attributeId);

		// Count of the row
		$this->assertEquals(2, $this->getConnection()->getRowCount('jos_paycart_attribute'));
		
		// get Current dataset 
		$queryTable	= $this->getConnection()->createDataSet(Array('jos_paycart_attribute'));
		
		//expected dataset
		$path = __DIR__.'/stubs/'.__CLASS__.'/au_'.$this->getName().'.xml';
		$expectedTable = $this->createXMLDataSet($path);
                              
         //Exclude column xml
         $expectedDataSet = new PHPUnit_Extensions_Database_DataSet_DataSetFilter($expectedTable);
         $queryDataSet 	 =	new PHPUnit_Extensions_Database_DataSet_DataSetFilter($queryTable);

         $expectedDataSet->setExcludeColumnsForTable('jos_paycart_attribute', array('xml'));
         $queryDataSet->setExcludeColumnsForTable('jos_paycart_attribute', array('xml'));

         //Comapre Table                              
		$this->assertDataSetsEqual($expectedDataSet, $queryDataSet);
		
		// Test Protected XML variable 
        $instance 	 = PaycartAttribute::getInstance(2);
        $actualXML	 = PaycartTestReflection::getValue($instance, 'xml');
        $expectedXML = "<fieldname='value'label='title_text'class='testing_class'default='default_text'type='text'size='50'maxlength='25'readonly='1'disabled='1'></field>";       	
        $this->assertEquals($expectedXML, str_replace(array(" ", "\r", "\n", "\t"), '',$actualXML));
	}
	

	/**
	 * 
	 * Test _buildFieldXML method
	 * 
	 * @dataProvider provider_BuildFieldXML
	 * @return void
	 */
	public function test_BuildFieldXML($data, $expectedXML)
	{
		$instance 	 = PaycartAttribute::getInstance(0, $data);
		$actualXML	 = PayCartTestReflection::invoke($instance, '_buildFieldXML');
		
		$this->assertSame($expectedXML, str_replace(array(" ", "\r", "\n", "\t"), '',$actualXML));		
	}
	
	/**
	 * 
	 * data provider for test_BuildFieldXML
	 */
	public function provider_BuildFieldXML() 
	{
		// Test for TEXT type field
		$data1 = Array
					(
						'title' 	=>	'title_text',
						'type'		=>	'text',
						'default' 	=>	'default_text',
					    'class' 	=>	'testing_class',
					    'params'	=> 	Array(
					    					'attribute_config'=>Array(
					    								"type"=>"text","size"=>"50",
					    								"maxlength"=>"25","readonly"=>"1",
					    								"disabled"=>"1"))
					);
		$expectedXML1 = "<fieldname='value'label='title_text'class='testing_class'default='default_text'type='text'size='50'maxlength='25'readonly='1'disabled='1'></field>";
		
		// Test for List type field
		$data2 = Array
					(
						'title' 	=>	'title_list',
						'type'		=>	'list',
						'default' 	=>	'option-3',
					    'class' 	=>	'testing_class',
					    'params'	=> 	Array(
					    					'attribute_config'=>Array(
														"type"=>"list",
														"options"=>"option-1\r\noption-2\r\noption-3\r\noption-4",
														"multiple"=>"false",
														"size"=>"5","readonly"=>"0",
														"onchange"=>""))
					);
		
		$expectedXML2 = "<fieldname='value'label='title_list'class='testing_class'default='option-3'type='list'multiple='false'size='5'readonly='0'onchange=''><optionvalue='option-1'>option-1</option><optionvalue='option-2'>option-2</option><optionvalue='option-3'>option-3</option><optionvalue='option-4'>option-4</option></field>";
		// Test for Multi-select List type field		
		$data3 = Array
					(
						'title' 	=>	'title_list',
						'type'		=>	'list',
						'default' 	=>	'option-3',
					    'class' 	=>	'testing_class',
					    'params'	=> 	Array(
					    					'attribute_config'=>Array(
														"type"=>"list",
														"options"=>"option-1\r\noption-2\r\noption-3\r\noption-4",
														"multiple"=>"true",
														"size"=>"5","readonly"=>"0",
														"onchange"=>""))
					);
		
		$expectedXML3 = "<fieldname='value'label='title_list'class='testing_class'default='option-3'type='list'multiple='true'size='5'readonly='0'onchange=''><optionvalue='option-1'>option-1</option><optionvalue='option-2'>option-2</option><optionvalue='option-3'>option-3</option><optionvalue='option-4'>option-4</option></field>";
		
		return 
			Array(
				Array($data1,$expectedXML1),
				Array($data2,$expectedXML2),
				Array($data3,$expectedXML3)	
				);
	}
}
