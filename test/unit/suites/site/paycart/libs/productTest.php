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
		//image info
		$provider[] = Array( 	
	  						'sourceFile' 	=>	RBTEST_BASE . '/_data/images/dummy.jpg',
	  						'targetFolder'	=>	RBTEST_BASE.'/tmp/',
	  						'targetFileName'=>	'tested_paycart' 
	  					);
	  	// result
	  	$provider[]	= 'COM_PAYCART_FILE_COPY_FAILED';
	  	$provider[] = null;
		// Case :: through Exception Image 	  	
	  	$providers[] = $provider;
	  			
	  	$extensions = Array('.jpg','.png','.gif'
	  					//	,'.bmp'				// Right now, Joomla does not support it
	  						);
	 
	  	// Cases ::Paycart Config have 
	  	// When paycart config have .jpg extension and image uploaded in different-2 flavour {.jpg, .gif, .png}
		// When paycart config have .png extension and image uploaded in different-2 flavour {.jpg, .gif, .png}
		// When paycart config have .gif extension and image uploaded in different-2 flavour {.jpg, .gif, .png}
	  	foreach ($extensions as $extension) {
	  		foreach ($extensions as $value) {
	  			$provider = Array();
	  			// Image Info
	  			$provider[] = Array( 	
	  						'sourceFile' 	=>	RBTEST_BASE . '/_data/images/paycart'.$value,
	  						'targetFolder'	=>	RBTEST_BASE.'/tmp/',
	  						'targetFileName'=>	'tested_paycart' 
	  					);	
	  			// result images	
	  			$provider[]	=	Array(
	  						'original_image'  => RBTEST_BASE.'/tmp/'.Paycart::IMAGE_ORIGINAL_PREFIX.'tested_paycart'.Paycart::IMAGE_ORIGINAL_SUFIX,
	  						'optimized_image' => RBTEST_BASE.'/tmp/tested_paycart'.$extension,
	  						'thumb_image' 	  => RBTEST_BASE.'/tmp/thumb_tested_paycart'.$extension			
	  					);
	  			// Config Extension
				$provider[]  = $extension;
				
	  			$providers[] = $provider;
	  		}
	  	}
	  	
	  	return $providers;
	}
	
	/**
	 * 
	 * Test _ImageCreate Method
	 * 
	 * @dataProvider provider_ImageCreate
	 */
	public function test_ImageCreate($imageInfo, $result, $configExtension) 
	{	
		// Backup Paycart Config
		$backupConfig = PayCartTestReflection::getValue('PaycartFactory', '_config');

		// before test clean all files
		JFile::delete(glob(RBTEST_BASE.'/tmp/*.*'));
		
		// Dependency :Create Mock for PaycartConfig 
		$mockConfig = $this->getMock('Rb_Registry');
		$mockConfig->expects($this->any())
					->method('get')
					->will($this->returnCallback(function($prop) use ($configExtension) { return  $configExtension; }));

		// Set Mockconfig
	  	PayCartTestReflection::setValue('PaycartFactory', '_config', $mockConfig);
		
	  	//Get Product Instance
	  	$object = PaycartProduct::getInstance();
	  	
	  	try {
	  		// invoke SUT
	  		PayCartTestReflection::invoke($object, '_imageCreate', $imageInfo);
	  	} catch (RuntimeException $e) {
	  		//@PCTODO ::Clean language string
	  		//@PCTODO:: Seperate this test case (Only for First Case) so we proper testing if exception fire
	  		$this->assertSame(Rb_Text::_($result), $e->getMessage());
	  	}
	  	
	  	if (is_array($result)) {
	  		$this->assertFileExists($result['original_image'], 'Missing Original Image');
	  		$this->assertFileExists($result['optimized_image'], 'Missing Optimized Image');
	  		$this->assertFileExists($result['thumb_image'], 'Missing Thumb Image');

	  		//check  image scale
	  		$expected_optimized_size = Array (Paycart::IMAGE_OPTIMIZE_HEIGHT, Paycart::IMAGE_OPTIMIZE_WIDTH);
	  		//height, width 
	  		list($actual_optimized_size[], $actual_optimized_size[]) = getimagesize($result['optimized_image']);
	  		
			$this->assertSame($expected_optimized_size, $actual_optimized_size);
			
	  		$expected_thumb_size = Array (Paycart::IMAGE_THUMB_HEIGHT, Paycart::IMAGE_THUMB_WIDTH);
	  		list($actual_thumb_size[], $actual_thumb_size[]) = getimagesize($result['thumb_image']);
	  		
	  		$this->assertSame($expected_thumb_size, $actual_thumb_size);
	  	}
	  	
	  	// After test clean all files
		JFile::delete(glob(RBTEST_BASE.'/tmp/*.*'));

	  	// Revert Paycart config
	  	PayCartTestReflection::setValue('PaycartFactory', '_config', $backupConfig);
	}
	
	
	/**
	 * 
	 * Test _ImageProcess method
	 */
	public function test_ImageProcess()
	{
		// Backup Paycart Config
		$backupConfig = PayCartTestReflection::getValue('PaycartFactory', '_config');
		// before test clean all files
		JFile::delete(glob(RBTEST_BASE.'/tmp/*.*'));
		
		// Dependency :Create Mock for PaycartConfig 
		$mockConfig = $this->getMock('Rb_Registry');
		$mockConfig->expects($this->any())
					->method('get')
					->will($this->returnCallback(Array(__CLASS__, 'callback_ImageProcess' )));

		// Set Mockconfig
	  	PayCartTestReflection::setValue('PaycartFactory', '_config', $mockConfig);
		
	  	
		//uploaded file
		$imageFile['size'] 		= 5242880; 		// 5MB
		$imageFile['type'] 		= 'image/png';
		// Source image
		$imageFile['tmp_name']	= RBTEST_BASE . '/_data/images/paycart.png' ;
		// Output image
		$data['cover_media'] 	= 'tmp/tested_pc.png';
		
		$product = PaycartProduct::getInstance(0, $data);
		
		// Invoke SUT 
		//@Case :: If new uploaded image on new created product
	  	PayCartTestReflection::invoke($product, '_ImageProcess', $imageFile, null);
	  	
		// Assert : Image properly created or not
		$this->assertFileExists(RBTEST_BASE.'/tmp/'.Paycart::IMAGE_ORIGINAL_PREFIX.'tested_pc'.Paycart::IMAGE_ORIGINAL_SUFIX, 'Missing Original Image');
	  	$this->assertFileExists(RBTEST_BASE.'/tmp/tested_pc.png', 'Missing Optimized Image');
	  	$this->assertFileExists(RBTEST_BASE.'/tmp/'.Paycart::IMAGE_THUMB_PREFIX.'tested_pc.png', 'Missing Thumb Image');

	  	
	  	$imageFile['type'] 		= 'image/jpeg';
		// Source image
		$imageFile['tmp_name']	= RBTEST_BASE . '/_data/images/paycart.jpg' ;
		// Output image
		$data['cover_media'] 	= 'tmp/existing_pc.png';
		
		$object = PaycartProduct::getInstance(0, $data);
		//@Case :: If new uploaded image on existing Product (Product already have image)
	  	PayCartTestReflection::invoke($object, '_ImageProcess', $imageFile, $product);
	  	
	  	// Assert : Old files Must vbe deleted
		$this->assertFileNotExists(RBTEST_BASE.'/tmp/'.Paycart::IMAGE_ORIGINAL_PREFIX.'tested_pc'.Paycart::IMAGE_ORIGINAL_SUFIX, 'Original Image should be deleted');
	  	$this->assertFileNotExists(RBTEST_BASE.'/tmp/tested_pc.png', 'Optimized Image should be deleted');
	  	$this->assertFileNotExists(RBTEST_BASE.'/tmp/'.Paycart::IMAGE_THUMB_PREFIX.'tested_pc.png', 'Thumb Image should be deleted');
	 
	  	// Assert : Image properly created or not
	  	$this->assertFileExists(RBTEST_BASE.'/tmp/'.Paycart::IMAGE_ORIGINAL_PREFIX.'existing_pc'.Paycart::IMAGE_ORIGINAL_SUFIX, 'Missing Original Image');
	  	$this->assertFileExists(RBTEST_BASE.'/tmp/existing_pc.png', 'Missing Optimized Image');
	  	$this->assertFileExists(RBTEST_BASE.'/tmp/'.Paycart::IMAGE_THUMB_PREFIX.'existing_pc.png', 'Missing Thumb Image');
	  	
	  	// Revert Paycart config
	  	PayCartTestReflection::setValue('PaycartFactory', '_config', $backupConfig);
	  	// After test clean all files
		JFile::delete(glob(RBTEST_BASE.'/tmp/*.*'));
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $prop
	 * @param unknown_type $default
	 */
	public function callback_ImageProcess($prop, $default) 
	{
		switch ($prop) 
		{
			case 'image_maximum_upload_limit' :
				return 5;
				
			case 'image_upload_directory' :
				return RBTEST_BASE;
			
			case 'image_extension'	:
				return '.png';
				
			default:
				return $default;		
		}
	}
	
	
	public function XX_test_ProcessCoverMedia()
	{
		// create Mock object
		$mockProduct = $this->getMockBuilder('PaycartProduct', array('_ImageProcess', '_ImageCreate'))
							->disableOriginalConstructor()
							->getMock();
		// create dummy data	
		$uploadFile = Array(
							'name'		=> 'paycart.png',
							'size' 		=> 5242880,
							'type' 		=> 'image/png',
							'tmp_name'	=> RBTEST_BASE . '/_data/images/paycart.png' 
							);
		// setup mock 					
		$mockProduct->expects($this->once())
					->method('_ImageProcess')
					->with($this->equalTo($uploadFile), null)
					->will($this->returnValue(true));
		
		$mockProduct->upload_files['cover_media'] = $uploadFile; 						
		PayCartTestReflection::setValue($mockProduct, 'cover_media', 'tmp/tested_pc.png');
		
		PayCartTestReflection::invoke($mockProduct, '_ProcessCoverMedia', null);
							
	}
}
