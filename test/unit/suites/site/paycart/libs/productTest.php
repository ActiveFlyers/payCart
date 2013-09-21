<?php

/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/

/** 
 * Test Product Lib
 * @author mManishTrivedi
 */
class PaycartProductTest extends PayCartTestCaseDatabase
{

	/**
	 * 
	 * Test Reset Method
	 */
	public function testReset() 
	{
		$instance = PaycartProduct::getInstance();

		// test default rest
		$this->_testReset($instance);

		// Change $insatance value
		$instance->set('title', 'testing');
		$instance->set('amount', 25);

		// Test value changed properly
		$this->assertSame('testing',	$instance->get('title'));
		$this->assertSame(25,	$instance->get('amount'));

		// reset $instance
		$instance->reset();

		//Test after-reset
		$this->_testReset($instance);		
	}
	
	/**
	 * 
	 * Helppr method for testReset
	 * @param PaycartProduct $instance
	 */
	protected function _testReset($instance) 
	{
		$this->assertSame(0, $instance->get('product_id')); 
		$this->assertSame('',$instance->get('title'));	
		$this->assertSame('',$instance->get('alias'));
		$this->assertSame(1,$instance->get('published'));
		$this->assertSame(Paycart::PRODUCT_TYPE_PHYSICAL,$instance->get('type'));
		$this->assertSame(0,$instance->get( 'amount'));
		$this->assertSame(0,$instance->get( 'quantity'));
		$this->assertSame('',$instance->get('sku'));	
		$this->assertSame(0,$instance->get('variation_of')); 	
		$this->assertSame(0,$instance->get('category_id'));
		$this->assertInstanceOf('Rb_Registry',$instance->get( 	'params'));
		$this->assertSame(NULL,$instance->get('cover_media')); 	
		$this->assertSame(null,$instance->get('teaser'));
		$this->assertInstanceOf('Rb_Date',$instance->get('publish_up'));
		$this->assertInstanceOf('Rb_Date',$instance->get('publish_down'));	 	
		$this->assertInstanceOf('Rb_Date',$instance->get('created_date'));	
		$this->assertInstanceOf('Rb_Date',$instance->get('modified_date')); 	
		$this->assertSame(0,$instance->get('created_by'));
		$this->assertSame(0,$instance->get('ordering'));
		$this->assertSame(0,$instance->get('featured'));	
		$this->assertSame(null,$instance->get('description')); 	
		$this->assertSame(0,$instance->get('hits'));
		$this->assertInstanceOf('Rb_Registry',$instance->get('meta_data'));
		$this->assertSame(Array(),$instance->get('_attributeValue'));
	}	
	
	/**
	 * Provider for testBind
	 */
	public function providerTestBind() 
	{
		$data1 	 = Array('upload_files' => Array('uploaded file here'),'title'=>'testing');
		$ignore1 = Array();
		$result1 = Array(
						'upload_files' 	=> Array('uploaded file here'),
						'_attributeValue'	=> Array()
						);

		$data2 	 = Array('upload_files' => Array('uploaded file here'), 'title'=>'testing');
		$ignore2 = Array('title');

		
		return Array(
						Array($data1, $ignore1, $result1),
					    Array($data2, $ignore2, $data2)
					);
	}
	
	
	/**
	 * 
	 * Test Bind method
	 * @param unknown_type $data 
	 * @param unknown_type $ignore
	 * @param unknown_type $result
	 * 
	 * @dataProvider providerTestBind
	 * @return void
	 */
	public function testBind($data, $ignore, $results) 
	{
		// Create a stub for the PaycartProduct class.
        $stub = $this->getMockBuilder('PaycartProduct')
         			 ->setMethods(Array('_setAttributeValues', 'getid'))
                     ->getMock();
                     
		// mock dependency
		$stub->expects($this->once())	// call on after bind
			 ->method('getid')
			 ->will($this->returnValue(0));
			 
        $stub->expects($this->once())
			 ->method('_setAttributeValues')
	 		 ->will($this->returnValue(false));
					 
		$stub->bind($data, $ignore);
		
		foreach ($results as $key=>$value) {
			// test ognore stuff
			if(in_array($key, $ignore)) {	
				// Test ignore data should not be availble on stub 
				$this->assertFalse(isset($stub->$key));
				continue;
			}
			// test binded data
			$this->assertSame($value, $stub->$key);
		}
	}
	
	
	/**
	 * 
	 * Test _setAttributes Method
	 */
	public function test_setAttributeValues() 
	{		        
		// Dummy instance and empty Attribute data
		$product = PaycartProduct::getInstance();
		$this->assertFalse(PayCartTestReflection::invoke($product, '_setAttributeValues'));

		// get instance and _setAttributeValues will be called on bind method 
		$product = PaycartProduct::getInstance(1);
		$attributeValue = $product->get('_attributeValue');

		// Number of attribute
		$this->assertSame(4, count($attributeValue));
		$expectedAttributeValue =
				Array(
						1 => Array('value'=>'text-attribute' , 'order' => '1', 'type'=>'text'),
						2 => Array('value'=> Array(0=>'option-21'), 'order' => '2', 'type'=>'list'),
						3 => Array('value'=> Array(0 => 'option-A', 1 => 'option-B'), 'order' => '3', 'type'=>'list' ),
						4 => Array('value'=> 'Attr', 'order' => '4', 'type'=>'text')
					);

		// Test attribute value type nd order
		foreach ($attributeValue as $id => $value)  {
			$this->assertInstanceOf('PaycartAttributeValue', $value);
			$this->assertSame($expectedAttributeValue[$id]['value'], $value->get('value'));
			$this->assertSame($expectedAttributeValue[$id]['order'], $value->get('order'));
			$this->assertInstanceOf('PaycartAttribute', $value->get('_attribute'));
			$this->assertSame($expectedAttributeValue[$id]['type'], $value->get('_attribute')->get('type'));
		}
	}
	
	/**
	 * Provider for _ImageCreate
	 */
	public function provider_ImageCreate() {
		
		$imageInfo1 = Array( 	
	  						'sourceFile' 	=>	RBTEST_BASE . '/_data/images/dummy.jpg',
	  						'targetFolder'	=>	RBTEST_BASE.'/tmp/',
	  						'targetFileName'=>	'tested_paycart' 
	  					);
		
		$imageInfo2 = Array( 	
	  						'sourceFile' 	=>	RBTEST_BASE . '/_data/images/paycart.jpg',
	  						'targetFolder'	=>	RBTEST_BASE.'/tmp/',
	  						'targetFileName'=>	'tested_paycart' 
	  					);
	  	return Array(
	  					 Array($imageInfo1)
	  					,Array($imageInfo2)
	  				);
	}
	
	/**
	 * 
	 * Test _ImageCreate Method
	 * 
	 * @dataProvider provider_ImageCreate
	 */
	public function test_ImageCreate($imageInfo) 
	{	
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
		
		
	  	$object = PaycartProduct::getInstance();
	  	try{
	  		PayCartTestReflection::invoke($object, '_imageCreate', $imageInfo);
	  	}catch (RuntimeException $e) {
	  		// PCTODO ::Clean language string
	  		$this->assertSame(Rb_Text::_('COM_PAYCART_FILE_COPY_FAILED'), $e->getMessage());
	  	}
	}
	
	
	
	
}
