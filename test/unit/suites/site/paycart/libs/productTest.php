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
	private $_session; 
	// temp files like images
	private $_files; 
	
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
		$data1 	 = Array('_upload_files' => Array('uploaded file here'),'title'=>'testing');
		$ignore1 = Array();
		$result1 = Array(
						'_upload_files' 	=> Array('uploaded file here'),
						'_attributeValue'	=> Array()
						);

		$data2 	 = Array('_upload_files' => Array('uploaded file here'), 'title'=>'testing');
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
         			 ->setMethods(Array('setAttributeValues', 'getid'))
                     ->getMock();
                     
		// mock dependency
		$stub->expects($this->once())	// call on after bind
			 ->method('getid')
			 ->will($this->returnValue(0));
			 
        $stub->expects($this->once())
			 ->method('setAttributeValues')
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
			$this->assertSame($value, $stub->get($key));	
		}
	}
	
	
	public $testSetAttributeValues =  
						Array(
								'_data/dataset/product/product-1.php',
								'_data/dataset/attribute/attribute-1.php',
								'_data/dataset/attributevalue/attributevalue-1.php'
							);
	 
	/**
	 * 
	 * Test SetAttributes Method
	 */
	public function testSetAttributeValues() 
	{		        
		// Dummy instance and empty Attribute data
		$product = PaycartProduct::getInstance();
		$this->assertFalse(PayCartTestReflection::invoke($product, 'setAttributeValues'));

		// get instance and setAttributeValues will be called on bind method 
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
	
	
	/**
	 * 
	 * test save task 
	 * Case : Post only required data i.e. title (Save only title with default data)
	 */
	public function testSave_Case1()
	{
		// Mock Dependancy
		$this->_beforeSaveTest();
		
		// Case : Post only required data i.e. title (Save only with title)
		$data = Array('title' => 'Product-1');
		//SUT
		$this->assertInstanceOf('PaycartProduct', PaycartProduct::getInstance(0,$data)->save());
		
		// Expected data
		$row	 = $this->auDataProduct();
		$au_data = Array( "jos_paycart_product" => Array ($row[1]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_product', $expectedDataSet, Array( 'publish_down', 'publish_up','created_date', 'modified_date', 
																			'description','teaser','cover_media','file'));
		
		// revert dependency stuff
		$this->_afterSaveTest();
	}
	
	/**
	 * 
	 * test save task 
	 * Case : Post redundant data for unique key
	 * @depends testSave_Case1
	 */
	public function testSave_Case2()
	{
		// Multiple testing data will be availble by Invoke testSave_Case1 
		$this->testSave_Case1();
		
		// Mock Dependancy
		$this->_beforeSaveTest();
		
		// Case : Post redundant data for unique key
		$data = Array(
						'title' => 'Product-1',
						'alias' => 'Product-1',
						'sku' 	=> 'Product-1',
						'category_id' =>1
					 );
					 
		$this->assertInstanceOf('PaycartProduct', PaycartProduct::getInstance(0,$data)->save());
		
		// Expected data
		$row	 = $this->auDataProduct();
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2]));
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_product', $expectedDataSet, Array( 'publish_down', 'publish_up','created_date', 'modified_date',
																			'description','teaser','cover_media','file'));
		
		// revert dependency stuff
		$this->_afterSaveTest();
		
	}
	
	/**
	 * 
	 * test save task 
	 * Case : Post redundant data for unique key
	 * @depends testSave_Case2
	 */
	public function testSave_Case3()
	{
		// Multiple testing data will be availble by Invoke testSave_Case1 
		$this->testSave_Case2();
		
		// Mock Dependancy
		$this->_beforeSaveTest();
		
		// case : Post image with redundant data
		$data = Array(
						'title' => 'Product-1',
						'alias' => 'Product-1',
						'sku' 	=> 'Product-1',
						'category_id' =>1,
						'_upload_files' => 
								Array('cover_media'=> 
										Array(
											'name'		=> 'paycart.png',
											'size' 		=> 2097152,
											'type' 		=> 'image/png',
											'tmp_name'	=> RBTEST_BASE . '/_data/images/paycart.png' 
											)
									)
					);
					
		$product = PaycartProduct::getInstance(0,$data)->save();
		
		$this->assertInstanceOf('PaycartProduct', $product);
		
		
		$path 		 = PaycartFactory::getConfig()->get('image_upload_directory', JPATH_ROOT.Paycart::IMAGES_ROOT_PATH);
		$imageFile 	 = $path.$product->getCoverMedia();
		
		$image = PaycartFactory::getHelper('image');
		
		$imageDetail =  $image->imageInfo($imageFile);
		
		$this->_files = Array();
		// Original Image 
		$this->_files[]	=	$imageDetail['dirname'].'/'.Paycart::IMAGE_ORIGINAL_PREFIX.$imageDetail['filename'].Paycart::IMAGE_ORIGINAL_SUFIX;
		// Optimized Image
		$this->_files[]	=	$imageFile;
		// thumb image
		$this->_files[]	=	$imageDetail['dirname'].'/'.Paycart::IMAGE_THUMB_PREFIX.$imageDetail['filename'].'.'.$imageDetail['extension'] ;
		
		// Assert : Image properly created  and exist
		foreach ($this->_files as $file) {	
	  		$this->assertFileExists($file, 'Missing Image Files');
		}
		
		// case : Upload new image on existing product
		// it will remove pre image file and upload new
		$data = Array('_upload_files' => 
						Array('cover_media'=> 
							Array(
								'name'		=> 'paycart.jpg',
								'size' 		=> 2097152,
								'type' 		=> 'image/jpeg',
								'tmp_name'	=> RBTEST_BASE . '/_data/images/paycart.jpg' 
								)
						));
					
		$product->bind($data)->save();
		// Assert : Previous Image deleted
		foreach ($this->_files as $file) {	
	  		$this->assertFileNotExists($file, 'Missing Image Files');
		}
		// get new cover media file
		$imageFile 	 = $path.$product->getCoverMedia();
		
		$imageDetail =  $image->imageInfo($imageFile);
		
		$this->_files = Array();
		// Original Image 
		$this->_files[]	=	$imageDetail['dirname'].'/'.Paycart::IMAGE_ORIGINAL_PREFIX.$imageDetail['filename'].Paycart::IMAGE_ORIGINAL_SUFIX;
		// Optimized Image
		$this->_files[]	=	$imageFile;
		// thumb image
		$this->_files[]	=	$imageDetail['dirname'].'/'.Paycart::IMAGE_THUMB_PREFIX.$imageDetail['filename'].'.'.$imageDetail['extension'] ;
		
		// Assert : New uploaded Image properly created  and exist
		foreach ($this->_files as $file) {	
	  		$this->assertFileExists($file, 'Missing Image Files');
		}

		// delete images (unused images)
		//$image->delete($imageFile	);
		
		// Expected data
		$row	 = $this->auDataProduct();
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3]));
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_product', $expectedDataSet, Array('publish_down', 'publish_up','created_date', 'modified_date', 'cover_media',
																			'description','teaser','file'));
		
		// revert dependency stuff
		$this->_afterSaveTest();
		
	}
	
	
	public $testSave_Case4 = Array('_data/dataset/attribute/attribute-1.php');

	/**
	 * 
	 * test save task 
	 * Case : Post redundant data for unique key
	 * @depends testSave_Case3
	 */
	public function testSave_Case4()
	{
		// Multiple testing data will be availble by Invoke testSave_Case1 
		$this->testSave_Case3();
		
		// Mock Dependancy
		$this->_beforeSaveTest();
		
		// attribute data {Array(attribute_id=>Array(value='', 'order'=>''))} in request data
		$attributes = Array(
						1 => Array('value'=>'_MANISH_', 	'order'=>1),
						2 => Array('value'=>'option-23', 	'order'=>2),
						3 => Array('value'=>'option-C', 	'order'=>3),
						4 => Array('value'=>'_PUNEET_', 	'order'=>4)					
						);
						
		// Case : Post redundant data for unique key and attributes values 
		$data = Array(
						'title' 		=> 'Product-1',
						'alias' 		=> 'Product-1',
						'sku' 			=> 'Product-1',
						'category_id' 	=> 1,
						'attributes'	=> $attributes
					 );

		$product = PaycartProduct::getInstance(0,$data)->save();	

		// compare product table
		// Expected data
		$row	 = $this->auDataProduct();
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3], $row[4]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_product', $expectedDataSet, Array( 'publish_down', 'publish_up','created_date', 'modified_date', 
																			'description','teaser','cover_media','file'));
		
		// Expected data
		$row	 = $this->auDataAttributeValue();
		$au_data = Array( "jos_paycart_attributevalue" => Array ($row[1], $row[2], $row[3], $row[4]));
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);

		$this->compareTable('jos_paycart_attributevalue', $expectedDataSet);
		
		// reset data and save
		$attributes = Array(
						1 => Array('value'=>'_TRIVEDI_', 	'order'=>1),
						2 => Array('value'=>'option-21', 	'order'=>2),
						3 => Array('value'=>'option-A', 	'order'=>3),
						4 => Array('value'=>'_SINGHAL_', 	'order'=>4)					
						);
						
		$product->bind(Array('attributes'	=> $attributes))->save();
		
		$au_data = Array( "jos_paycart_attributevalue" => Array ($row[5], $row[6], $row[7], $row[8]));
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);

		$this->compareTable('jos_paycart_attributevalue', $expectedDataSet);
		
		// revert dependency stuff
		$this->_afterSaveTest();
		
	}
					 
	/**
	 * 
	 * Gold table (data for dataset)
	 */
	protected function auDataAttributeValue() 
	{
		$row	= Array();
		
		$row[0]	= array_merge(Array('attributevalue_id'=>0), include RBTEST_PATH_DATASET.'/attributevalue/tmpl.php');
		
		$row[1]	= array_replace($row[0], Array('attributevalue_id'=>1, "product_id" => 4, "attribute_id" => 1,  
												"value" => '_MANISH_', "order" => 1 ));
								
		$row[2]	= array_replace($row[0], Array( 'attributevalue_id'=>2, "product_id" => 4, "attribute_id" => 2,  
												"value" => 'option-23', "order" => 2 ));

		$row[3]	= array_replace($row[0], Array( 'attributevalue_id'=>3, "product_id" => 4, "attribute_id" => 3,  
												"value" => 'option-C', "order" => 3 ));
		
		$row[4]	= array_replace($row[0], Array( 'attributevalue_id'=>4, "product_id" => 4, "attribute_id" => 4,  
												"value" => '_PUNEET_', "order" => 4 ));
								
		$row[5]	= array_replace($row[1], Array('attributevalue_id'=>5, "value" => '_TRIVEDI_'));
								
		$row[6]	= array_replace($row[2], Array( 'attributevalue_id'=>6, "value" => 'option-21'));

		$row[7]	= array_replace($row[3], Array( 'attributevalue_id'=>7, "value" => 'option-A'));
		
		$row[8]	= array_replace($row[4], Array( 'attributevalue_id'=>8, "value" => '_SINGHAL_'));
		
		$row[9]	= array_replace($row[5], Array( 'product_id' => 8));
								
		$row[10]= array_replace($row[6], Array( 'product_id' => 8));

		$row[11]= array_replace($row[7], Array( 'product_id' => 8));
		
		$row[12]= array_replace($row[8], Array( 'product_id' => 8));
									
		return $row;
	}
	
	/**
	 * 
	 * Gold table (data for dataset)
	 */
	protected function auDataProduct() 
	{
		static $row	= Array();
		
		if(!empty($row)) {
			return $row;
		}
		
		$row[0] 	= array_merge(Array('product_id'=>0), include RBTEST_PATH_DATASET.'/product/tmpl.php');
		

		$row[1]	=  array_replace($row[0], Array(
											"product_id" =>1, "title" => 'Product-1', "alias" => 'product-1',
											"sku" => 'product-1', "publish_down" => '0000-00-00 00:00:00', 
											"created_by" => 662, "ordering" => 1
									));
		
		$row[2]	=  array_replace($row[1], Array( "product_id" =>2,  "alias" => 'product-2',"sku" => 'product-2',
												  "category_id" => 1, "ordering" => 2 ));
		
		$row[3]	=  array_replace($row[2], Array( "product_id" =>3,  "alias" => 'product-3',"sku" => 'product-3',
												  "ordering" => 3 ));
		
		$row[4]	=  array_replace($row[2], Array( "product_id" =>4,  "alias" => 'product-4',"sku" => 'product-4',
												  "ordering" => 4 ));
		
		$row[5]	=  array_replace($row[1], Array( "product_id" =>5,  "alias" => 'product-5',"sku" => 'product-5',
												  "ordering" => 5, 'variation_of'=>1 ));
		
		$row[6]	=  array_replace($row[5], Array( "product_id" =>6,  "alias" => 'product-6',"sku" => 'product-6',
												  "ordering" => 6 ));
		
		$row[7]	=  array_replace($row[3], Array( "product_id" =>7,  "alias" => 'product-7',"sku" => 'product-7',
												  "ordering" => 7, 'variation_of'=>3 ));
		
		$row[8]	=  array_replace($row[4], Array( "product_id" =>8,  "alias" => 'product-8',"sku" => 'product-8',
												  "ordering" => 8, 'variation_of'=>4 ));
	
		return $row;
						
	}
	
	/**
	 * 
	 * execute it before test save task 
	 */
	protected function _beforeSaveTest() 
	{
		// Mock Dependancy
		$this->_session = PaycartFactory::$session;
		$options = Array(
						'get.user.id' 		=>  662,
						'get.user.name'		=> '_MANISH_TRIVEDI_',
						'get.user.username' => 'mManishTrivedi',
						'get.user.guest'	=>	0
						);
		// MockSession and set 44 user id in session
		PaycartFactory::$session = $this->getMockSession($options);
	}

	/**
	 * 
	 * execute it After test save task 
	 */
	protected function _afterSaveTest()
	{
		// revert cached stuff
		PaycartFactory::$session = $this->_session ;
	}
	
	public $testAddVariant = Array('_data/dataset/attribute/attribute-1.php');
	/**
	 * 
	 * test AddVariant task 
	 * @depends testSave_Case4
	 */
	public function testAddVariant() 
	{
		// Multiple testing data will be availble by Invoke testSave_Case4 like data with image
		$this->testSave_Case4();
		
		// Mock Dependancy
		$this->_beforeSaveTest();
		
		// Expected data
		$row	 = $this->auDataProduct();
		
		//Case-1: Create normal variant (nither images nor custom attributes)only change alias,sku
		$product = PaycartProduct::getInstance(1);
		
		//SUT
		$this->assertInstanceOf('PaycartProduct', $product->addVariant());
		
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3], $row[4], $row[5]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_product', $expectedDataSet, Array( 'publish_down', 'publish_up','created_date', 'modified_date', 
																			'description','teaser','cover_media','file'));
		
		//Case-2: Create varaint of existing variant then variation_of should be root product
		$product = PaycartProduct::getInstance(5);
		
		//SUT
		$this->assertInstanceOf('PaycartProduct', $product->addVariant());
		
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3], $row[4], $row[5], $row[6]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_product', $expectedDataSet, Array( 'publish_down', 'publish_up','created_date', 'modified_date', 
																			'description','teaser','cover_media','file'));
		
		//Case-3: Create varaint with existing product cover-media, category etc
		$product = PaycartProduct::getInstance(3);
		
		//SUT
		$this->assertInstanceOf('PaycartProduct', $product->addVariant());
		
		// Assert that images should be created new
		$path 		 = PaycartFactory::getConfig()->get('image_upload_directory', JPATH_ROOT.Paycart::IMAGES_ROOT_PATH);
		$imageFile 	 = $path.$product->getCoverMedia();

		$imageDetail = PaycartFactory::getHelper('image')->imageInfo($imageFile);
		
		$files = Array();
		// Original Image 
		$files[]	=	$imageDetail['dirname'].'/'.Paycart::IMAGE_ORIGINAL_PREFIX.$imageDetail['filename'].Paycart::IMAGE_ORIGINAL_SUFIX;
		// Optimized Image
		$files[]	=	$imageFile;
		// thumb image
		$files[]	=	$imageDetail['dirname'].'/'.Paycart::IMAGE_THUMB_PREFIX.$imageDetail['filename'].'.'.$imageDetail['extension'] ;
		
		// Assert : New uploaded Image properly created  and exist
		foreach ($files as $file) {	
			$this->assertFileExists($file, 'Missing Image Files');
			// it will be deleted at the end of test cases 
			$this->_files[] = $file; 
		}
		
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_product', $expectedDataSet, Array( 'publish_down', 'publish_up','created_date', 'modified_date', 
																			'description','teaser','cover_media','file'));
		
		
		//Case-4: Create varaint with existing product-attributes (custom attribute)
		$product = PaycartProduct::getInstance(4);
		
		//SUT
		$this->assertInstanceOf('PaycartProduct', $product->addVariant());
		
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_product', $expectedDataSet, Array( 'publish_down', 'publish_up','created_date', 'modified_date', 
																			'description','teaser','cover_media','file'));
		
		// comapare attribute data
		$row = $this->auDataAttributeValue();
		
		$au_data = Array( "jos_paycart_attributevalue" => Array($row[5], $row[6], $row[7],$row[8], $row[9], $row[10], $row[11], $row[12]));
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);

		$this->compareTable('jos_paycart_attributevalue', $expectedDataSet, Array('attributevalue_id'));
		
		
		// revert dependency stuff
		$this->_afterSaveTest();
	}
	
	protected function tearDown()
	{
		// remove files created on testing time
		if(!empty($this->_files)) {
			JFile::delete($this->_files);
		}
		parent::tearDown();
	}
}
