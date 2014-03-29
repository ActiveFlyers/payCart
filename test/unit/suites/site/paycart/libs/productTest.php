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

		// test default reset
		$this->_testReset($instance);

		// Change $insatance value
		PaycartTestReflection::setValue($instance, 'price', 25);
		PaycartTestReflection::setValue($instance, 'status', 'published');
		
		// Test value changed properly
		$this->assertSame(25,PaycartTestReflection::getValue($instance, 'price'));
		$this->assertSame('published',PaycartTestReflection::getValue($instance, 'status'));
		
		// reset instance
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
		$this->assertSame('',$instance->get('status'));
		$this->assertSame(Paycart::PRODUCT_TYPE_PHYSICAL,$instance->get('type'));
		$this->assertSame(0.00,$instance->get( 'price'));
		$this->assertSame(0,$instance->get( 'quantity'));
		$this->assertSame('',$instance->get('sku'));	
		$this->assertSame(0,$instance->get('variation_of')); 	
		$this->assertSame(0,$instance->get('productcategory_id'));
		$this->assertSame(NULL,$instance->get('cover_media'));	 	
		$this->assertInstanceOf('Rb_Date',$instance->get('created_date'));	
		$this->assertInstanceOf('Rb_Date',$instance->get('modified_date'));
		$this->assertSame(0,$instance->get('ordering'));
		$this->assertSame(0,$instance->get('featured'));
		$this->assertSame(0.00,$instance->get('weight')); 
		$this->assertSame(0.00,$instance->get('height'));
		$this->assertSame(0.00,$instance->get('length'));
		$this->assertSame(0.00,$instance->get('depth'));
		$this->assertSame('',$instance->get('weight_unit'));
		$this->assertSame('',$instance->get('dimension_unit'));
		$this->assertSame(0,$instance->get('stockout_limit'));
		$this->assertSame('',$instance->get('config'));
		
		//check other data
		$language = new stdClass();
 		$language->product_lang_id	   = 0;
		$language->product_id 		   = 0;	
		$language->lang_code 		   = PaycartFactory::getLanguageTag();
		$language->title	 		   = '';	
		$language->alias  			   = '';
		$language->teaser 			   = '';
		$language->description 		   = '';	
		$language->metadata_title  	   = '';
		$language->metadata_keywords   = '';
		$language->metadata_description = '';

		$this->assertEquals($language, $instance->get('_language'));
		
		$this->assertInstanceOf('stdclass', $instance->get('_attributeValues'));
	}	
	
	/**
	 * Provider for testBind
	 */
	public function providerTestBind() 
	{
		$data1 	 = Array('_uploaded_files' => Array('uploaded file here'),'quantity'=>150);
		$ignore1 = Array();
		$result1 = Array(
						'_uploaded_files' 	=> Array('uploaded file here'),
						'_attributeValues'	=> new stdClass()
						);

		$data2 	 = Array('_uploaded_files' => Array('uploaded file here'), 'quantity'=>150);
		$ignore2 = Array('quantity');

		
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
         			 ->setMethods(Array('setAttributeValues', 'getid', 'setLanguageData'))
                     ->getMock();
                     
		// mock dependency
		$stub->expects($this->once())	// call on after bind
			 ->method('getid')
			 ->will($this->returnValue(0));
			 
        $stub->expects($this->once())
			 ->method('setAttributeValues')
	 		 ->will($this->returnValue(false));
	 		 
	 	 $stub->expects($this->once())
			 ->method('setLanguageData')
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
			$this->assertEquals($value, $stub->get($key));	
		}
	}
	
	
	public $testSetAttributeValues =  
						Array(
								'_data/dataset/product/product-1.php',
								'_data/dataset/productattribute/productattribute-1.php',
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
		$attributeValue = $product->get('_attributeValues');

		// Number of attribute
		$this->assertSame(4, count(get_object_vars($attributeValue)));
		
		$expectedAttributeValue =
								Array( //attribute_id => value
										1 => Array(2),
										2 => Array(1),
										3 => Array(0 => 4, 1 => 2),
										4 => Array(1)
									);

		// Test attribute value type nd order
		foreach ($attributeValue as $id => $value)  {
			$this->assertEquals($expectedAttributeValue[$id], $value);
		}
	}
	
	public $testSetLanguageData =  
						Array(
								'_data/dataset/product/product-1.php'
							);
	
	function testSetLanguageData()
	{
		// Dummy instance and empty data
		$product = PaycartProduct::getInstance();
		$this->assertFalse(PayCartTestReflection::invoke($product, 'setLanguageData'));
		
		$product = PaycartProduct::getInstance(1);
		$language = $product->get('_language');

		// Number of data
		$this->assertSame(1, count($language));
		
		$expectedLanguageData = Array(1 => Array('product_id'=> 1 , 'title'=>'Product-1', 'alias'=>'Product-1'));		
				
		foreach($expectedLanguageData as $key=>$expectedData){
			$this->assertEquals($expectedData['product_id'], $language->product_id, "Product id didn't match");
			$this->assertEquals($expectedData['title'], $language->title, "Title didn't match");
			$this->assertEquals($expectedData['alias'], $language->alias, "Alias didn't match");
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
	 * Case : Post nothing
	 */
	public function testSave_Case1()
	{
		$data = Array();
		
		$product = PaycartProduct::getInstance(0,$data);
		try{
			$product->save();
		}catch (Exception $e) {
			$msg = $e->getMessage();
			$this->assertInstanceOf('UnexpectedValueException', $e,"Exception didn't match");
			$this->assertSame(Rb_Text::sprintf('COM_PAYCART_TITLE_REQUIRED', $product->getName()), $msg, 'Exceptiom should be fired');
		}
	}
	
	
	/**
	 * 
	 * test save task 
	 * Case : Post only required data i.e. title (Save only title with default data)
	 */
	public function testSave_Case2()
	{
		// Mock Dependancy
		$this->_beforeSaveTest();
		
		// Case : Post only required data i.e. title (Save only with title)
		$data = Array('language' => array('title' => 'Product-1'));
				
		$this->assertInstanceOf('PaycartProduct', PaycartProduct::getInstance(0,$data)->save());
		
		$product = PaycartProduct::getInstance(1);
		
		// Expected data
		list($product,$productLang)	 = $this->auDataProduct();
		
		$auData = array('jos_paycart_product' => array($product[1]), 'jos_paycart_product_lang' =>  array($productLang[1]));
		
		// Compare table
		$this->compareTables(array_keys($auData), 
					$auData, Array( 'jos_paycart_product' => array('created_date', 'modified_date','cover_media','height','weight','depth','price','length')));
		
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
		
		// Case : Post redundant data for unique key
		$data = Array(
						'language' => array('title' => 'Product-1',
								 			 'alias' => 'product-1'),
						'sku' 	=> 'product-2',
						'productcategory_id' => 1
					 );
					 
		$this->assertInstanceOf('PaycartProduct', PaycartProduct::getInstance(0,$data)->save());
		
		// Expected data
		list($row , $rowLang) = $this->auDataProduct();
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2]),
		                  "jos_paycart_product_lang" => Array($rowLang[1],$rowLang[2]));
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTables(array_keys($au_data), $au_data,  Array( 'jos_paycart_product' => array('created_date', 'modified_date','cover_media','height','weight','depth','price','length')));
		
		// revert dependency stuff
		$this->_afterSaveTest();
		
	}
	
	/**
	 * 
	 * test save task 
	 * Case : Post redundant data for unique key
	 * @depends testSave_Case2
	 */
	public function testSave_Case4()
	{
		// Multiple testing data will be availble by Invoke testSave_Case1 
		$this->testSave_Case3();
		
		// Mock Dependancy
		$this->_beforeSaveTest();
		
		// case : Post image with redundant data
		$data = Array(
						'language' => array('title' => 'Product-3', 'alias' => 'product-1'),
						'sku' 	=> 'product-3',
						'productcategory_id' =>1,
						'_uploaded_files' => 
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
		$data = Array('_uploaded_files' => 
						Array('cover_media'=> 
							Array(
								'name'		=> 'paycart.jpg',
								'size' 		=> 2097152,
								'type' 		=> 'image/jpeg',
								'tmp_name'	=> RBTEST_BASE . '/_data/images/paycart.jpg' 
								)
						));

		// create instance again, otherwise the changes after saving in next step won't reflect on object
		$product = PaycartProduct::getInstance(3);
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
		list($row,$rowLang)	 = $this->auDataProduct();
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3]),
						  "jos_paycart_product_lang" => Array($rowLang[1],$rowLang[2],$rowLang[3]));
		
		
		// Compare table
		$this->compareTables(array_keys($au_data), $au_data, Array('jos_paycart_product' => array('created_date', 'modified_date', 'cover_media','height',
																								  'weight','depth','price','length')));
		
		// revert dependency stuff
		$this->_afterSaveTest();
		
	}
	
	
	public $testSave_Case5 = Array('_data/dataset/productattribute/productattribute-1.php');

	/**
	 * 
	 * test save task 
	 * Case : Post redundant data for unique key
	 * @depends testSave_Case4
	 */
	public function testSave_Case5()
	{
		// Multiple testing data will be availble by Invoke testSave_Case1 
		$this->testSave_Case4();
		
		// Mock Dependancy
		$this->_beforeSaveTest();
		
		// attribute data {Array(attribute_id=>Array(value='', 'order'=>''))} in request data
		$attributes = Array(
						1 => Array('option-1'),
						2 => Array('option-2'),
						3 => Array('option-3','option-5'),
						4 => Array('option-4')					
						);
						
		// Case : Post redundant data for unique key and attributes values 
		$data = Array(
						'sku' 			=> 'Product-4',
						'productcategory_id'	=> 2,
						'attributes'	=> $attributes,
		 				'language'		=> array('title' => 'Product-4','alias' => 'Product-1',)
					 );

		$product = PaycartProduct::getInstance(0,$data)->save();	

		// compare product table
		// Expected data
		list($row,$rowLang)	 = $this->auDataProduct();
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3],$row[4]),
						  "jos_paycart_product_lang" => Array($rowLang[1],$rowLang[2],$rowLang[3],$rowLang[4]));
		
		// Compare table
		$this->compareTables(array_keys($au_data), $au_data, Array('jos_paycart_product' => array('created_date', 'modified_date', 'cover_media','height',
																								  'weight','depth','price','length')));
		
		// Expected attribute data
		$row	 = $this->auDataAttributeValue();
		$au_data = Array( "jos_paycart_productattribute_value" => Array ($row[1], $row[2], $row[3], $row[4], $row[5]));
		
		//$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);

		$this->compareTable('jos_paycart_productattribute_value', $au_data);
		
		// reset data and save
		$attributes = Array(
						1 => Array('option-3'),
						2 => Array('option-1'),
						3 => Array('option-2','option-1'),
						4 => Array('option-2')					
						);
						
		$product = PaycartProduct::getInstance(4);
		$product->bind(Array('attributes'	=> $attributes))->save();
		
		$au_data = Array( "jos_paycart_productattribute_value" => Array ($row[6], $row[7], $row[8], $row[9], $row[10]));

		$this->compareTable('jos_paycart_productattribute_value', $au_data);
		
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
		
		$row[0]	=  include RBTEST_PATH_DATASET.'/attributevalue/tmpl.php';
		
		$row[1]	= array_replace($row[0], Array("product_id" => 4, "productattribute_id" => 1,  
												"productattribute_value" => 'option-1' ));
								
		$row[2]	= array_replace($row[0], Array(  "product_id" => 4, "productattribute_id" => 2,  
												"productattribute_value" => 'option-2'));

		$row[3]	= array_replace($row[0], Array(  "product_id" => 4, "productattribute_id" => 3,  
												"productattribute_value" => 'option-3'));
		
		$row[4]	= array_replace($row[0], Array(  "product_id" => 4, "productattribute_id" => 3,  
												"productattribute_value" => 'option-5'));
		
		$row[5]	= array_replace($row[0], Array( "product_id" => 4, "productattribute_id" => 4,  
												"productattribute_value" => 'option-4'));
								
		$row[6]	= array_replace($row[0], Array("product_id" => 4, "productattribute_id" => 1,  
												"productattribute_value" => 'option-3' ));
								
		$row[7]	= array_replace($row[0], Array("product_id" => 4, "productattribute_id" => 2,  
												"productattribute_value" => 'option-1' ));

		$row[8]	= array_replace($row[0], Array("product_id" => 4, "productattribute_id" => 3,  
												"productattribute_value" => 'option-2' ));
		
		$row[9]	= array_replace($row[0], Array("product_id" => 4, "productattribute_id" => 3,  
												"productattribute_value" => 'option-1' ));
		
		$row[10]	= array_replace($row[0],Array("product_id" => 4, "productattribute_id" => 4,  
												"productattribute_value" => 'option-2' ));
		
		$row[11]	= array_replace($row[6], Array("product_id" => 8));
								
		$row[12]	= array_replace($row[7], Array("product_id" => 8));
		
		$row[13]	= array_replace($row[8], Array("product_id" => 8));
		
		$row[14]	= array_replace($row[9], Array("product_id" => 8));
		
		$row[15]	= array_replace($row[10], Array("product_id" => 8));
		
		return $row;
	}
	
	/**
	 * 
	 * Gold table (data for dataset)
	 */
	protected function auDataProduct() 
	{
		$row	 = Array();
		$rowLang = Array();
		
		
		list($tmpl, $tmplLang) = include RBTEST_PATH_DATASET.'/product/tmpl.php';
		
		$row[0] = array_merge(Array('product_id'=>0), $tmpl);
		

		$row[1]	=  array_replace($row[0], Array("product_id" =>1, 'variation_of'=>1,
											    "sku" => 'product-1', "ordering" => 1));
		
		$row[2]	=  array_replace($row[1], Array( "product_id" =>2, "sku" => 'product-2',
												  "productcategory_id" => 1, "ordering" => 2,'variation_of'=>2));
		
		$row[3]	=  array_replace($row[2], Array( "product_id" =>3, "sku" => 'product-3',
												  "ordering" => 3, 'variation_of'=>3));
		
		$row[4]	=  array_replace($row[3], Array( "product_id" =>4, "sku" => 'product-4',
												  "ordering" => 4, 'variation_of'=>4,'productcategory_id'=> 2));
		
		$row[5]	=  array_replace($row[1], Array( "product_id" =>5, "sku" => 'product-5',
												  "ordering" => 5, 'variation_of'=>1 ));
		
		$row[6]	=  array_replace($row[5], Array( "product_id" =>6, "sku" => 'product-6',
												  "ordering" => 6 ,'variation_of'=>1));
		
		$row[7]	=  array_replace($row[3], Array( "product_id" =>7, "sku" => 'product-7',
												  "ordering" => 7, 'variation_of'=>3 ));
		
		$row[8]	=  array_replace($row[4], Array( "product_id" =>8, "sku" => 'product-8',
												  "ordering" => 8, 'variation_of'=>4 ));
	
		//language data
		$rowLang[0] = array_merge(Array('product_lang_id'=>0), $tmplLang);
		
		$rowLang[1] = array_replace($rowLang[0], Array('product_lang_id'=>1 , "product_id" =>1 , "title" => 'Product-1', "alias" => 'product-1'));
		
		$rowLang[2] = array_replace($rowLang[1], Array('product_lang_id'=>2 , "product_id" =>2 , "title" => 'Product-1', "alias" => 'product-2'));
		
		$rowLang[3] = array_replace($rowLang[2], Array('product_lang_id'=>3 , "product_id" =>3 , "title" => 'Product-3', "alias" => 'product-3'));
		
		$rowLang[4] = array_replace($rowLang[3], Array('product_lang_id'=>4 , "product_id" =>4 , "title" => 'Product-4', "alias" => 'product-4'));
		
		$rowLang[5] = array_replace($rowLang[4], Array('product_lang_id'=>5 , "product_id" =>5 , "title" => 'Product-1', "alias" => 'product-5'));
		
		$rowLang[6] = array_replace($rowLang[5], Array('product_lang_id'=>6 , "product_id" =>6 , "title" => 'Product-1', "alias" => 'product-6'));
		
		$rowLang[7] = array_replace($rowLang[6], Array('product_lang_id'=>7 , "product_id" =>7 , "title" => 'Product-3', "alias" => 'product-7'));
		
		$rowLang[8] = array_replace($rowLang[7], Array('product_lang_id'=>8 , "product_id" =>8 , "title" => 'Product-4', "alias" => 'product-8'));
		
		return array($row, $rowLang);
						
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
	
	public $testAddVariant = Array('_data/dataset/productattribute/productattribute-1.php');
	/**
	 * 
	 * test AddVariant task 
	 * @depends testSave_Case5
	 */
	public function testAddVariant() 
	{
		// Multiple testing data will be availble by Invoke testSave_Case5 like data with image
		$this->testSave_Case5();
		
		// Mock Dependancy
		$this->_beforeSaveTest();
		
		// Expected data
		list($row,$rowLang)	 = $this->auDataProduct();
		
		//Case-1: Create normal variant (nither images nor custom attributes)only change alias,sku
		$product = PaycartProduct::getInstance(1);
		
		$a = $product->addVariant();
		//SUT
		$this->assertInstanceOf('PaycartProduct', $a);
		
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3], $row[4], $row[5]),
						  "jos_paycart_product_lang" => Array($rowLang[1], $rowLang[2], $rowLang[3], $rowLang[4], $rowLang[5]) );
		
		
		// Compare table
		$this->compareTables(array_keys($au_data), $au_data, Array('jos_paycart_product' => array('created_date', 'modified_date', 'cover_media','height',
																								  'weight','depth','price','length')));
		
		//Case-2: Create varaint of existing variant then variation_of should be root product
		$product = PaycartProduct::getInstance(5);
		
		//SUT
		$this->assertInstanceOf('PaycartProduct', $product->addVariant());
		
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3], $row[4], $row[5],$row[6]),
						  "jos_paycart_product_lang" => Array($rowLang[1], $rowLang[2], $rowLang[3], $rowLang[4], $rowLang[5],$rowLang[6]));
		
		
		// Compare table
		$this->compareTables(array_keys($au_data), $au_data, Array('jos_paycart_product' => array('created_date', 'modified_date', 'cover_media','height',
																								  'weight','depth','price','length')));
		
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
		
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3], $row[4], $row[5],$row[6],$row[7]),
						  "jos_paycart_product_lang" => Array($rowLang[1], $rowLang[2], $rowLang[3], $rowLang[4], $rowLang[5],$rowLang[6],$rowLang[7]));
		
		
		// Compare table
		$this->compareTables(array_keys($au_data), $au_data, Array('jos_paycart_product' => array('created_date', 'modified_date', 'cover_media','height',
																								  'weight','depth','price','length')));
		
		
		//Case-4: Create varaint with existing product-attributes (custom attribute)
		$product = PaycartProduct::getInstance(4);
		
		//SUT
		$this->assertInstanceOf('PaycartProduct', $product->addVariant());
		
		$au_data = Array( "jos_paycart_product" => Array ($row[1], $row[2], $row[3], $row[4], $row[5],$row[6],$row[7],$row[8]),
						  "jos_paycart_product_lang" => Array($rowLang[1], $rowLang[2], $rowLang[3], $rowLang[4], $rowLang[5],$rowLang[6],$rowLang[7],$rowLang[8]));
		
		// Compare table
		$this->compareTables(array_keys($au_data), $au_data, Array('jos_paycart_product' => array('created_date', 'modified_date', 'cover_media','height',
																								  'weight','depth','price','length')));
		
		// comapare attribute data
		$row = $this->auDataAttributeValue();
		
		$au_data = Array( "jos_paycart_productattribute_value" => Array($row[6], $row[7], $row[8],$row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15]));

		$this->compareTable('jos_paycart_productattribute_value', $au_data);
		
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
