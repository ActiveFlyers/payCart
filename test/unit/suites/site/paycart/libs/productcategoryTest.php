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
include_once JPATH_ROOT.'/components/com_paycart/paycart/tables/productcategory.php';
include_once JPATH_ROOT.'/components/com_paycart/paycart/models/productcategory.php';

class PaycartProductcategoryTest extends PayCartTestCaseDatabase
{
	/**
	 * Test design structure of PayCartCategory class.  
	 * Test => Class Structure + Structure of Class Behavior
	 * 
	 * @return void 
	 */
	public function testClassDesign() 
	{
		$reflection  = new ReflectionClass('PaycartProductcategory');
		
		//Test parent class
		$this->assertEquals('PaycartLib', $reflection->getParentClass()->getname(), 'MisMatch : Parent Class');
  		
  		$expectedProperty = Array ( 'productcategory_id', 'status', 'parent', 'cover_media', 
  									'created_date',	'modified_date', '_language'	
  								 );

  		//test class property
  		$this->assertEquals($expectedProperty, PayCartTestReflection::getClassAttribute('PaycartProductcategory'));

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
		$this->assertInstanceOf('PaycartProductcategory', PaycartProductcategory::getInstance());
	}
	
	/**
	 * Test default values of class insance
	 * 
	 * @depends testGetInstance
	 * @return void
	 */
	public function testReset() 
	{
		$instance = PaycartProductcategory::getInstance();
		
		// Test default values
		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'productcategory_id'));
		$this->assertSame(Paycart::STATUS_PUBLISHED, PaycartTestReflection::getValue($instance, 'status'));
		$this->assertSame(0, PaycartTestReflection::getValue($instance, 'parent'));
		$this->assertEmpty(PaycartTestReflection::getValue($instance, 'cover_media'));
		
		$this->assertInstanceOf('Rb_Date', PaycartTestReflection::getValue($instance, 'created_date'));
 		$this->assertInstanceOf('Rb_Date', PaycartTestReflection::getValue($instance, 'modified_date'));
 		
 		$language = new stdClass();
 		$language->productcategory_lang_id	= 0;
 		
 		$language->lang_code 			= PaycartFactory::getLanguageTag(); //Current Paycart language Tag	
		$language->title				= '';
		$language->alias				= '';
		$language->description			= ''; 	

		$language->metadata_title		 = '';
		$language->metadata_keywords	 = '';
		$language->metadata_description = '';
		
 		
		$this->assertEquals($language, PaycartTestReflection::getValue($instance, '_language'));
		
 		// test return type
		$this->assertInstanceOf('PaycartProductcategory', $instance);
	}
	
	/**
	 * 
	 * Test PaycartCategory lib method
	 * 
	 */
	public function testSave()
	{
		// Mock Dependancy
//		$session = PaycartFactory::$session;
//		$options = Array(
//						'get.user.id' 		=>  44,
//						'get.user.name'		=> '_MANISH_TRIVEDI_',
//						'get.user.username' => 'mManishTrivedi',
//						'get.user.guest'	=>	0
//						);
//		// MockSession and set 44 user id in session
//		PaycartFactory::$session = $this->getMockSession($options);

		// Test Alias auto creation as per title
		$data    = Array(
							'_language' => Array('title' => 'testing_title_1',	'lang_code'	=>'en-GB',
												 'description'=>'description1',	'metadata_title' => 'metadata_title1', 
												 'metadata_keywords' => 'keyword1, keyword2',	
												 'metadata_description'=> 'description1' )
						);
						
		$this->assertInstanceOf('PaycartProductcategory', PaycartProductcategory::getInstance(0, $data)->save());

		// Test: Alias should be unique
		$data    = Array(
							'_language' => Array('title' => 'testing_title_2', 'lang_code'	=>'hi-IN', 'alias'=>'cat-3',
												 'description'=>'description2',	'metadata_title' => 'metadata_title2', 
												 'metadata_keywords' 	=> 'keyword21, keyword22',	
												 'metadata_description'	=> 'description2' )
						);
						
		$instance = PaycartProductcategory::getInstance(0, $data)->save();
		$this->assertInstanceOf('PaycartProductcategory', $instance);
		
		
		// Test: Alias should be unique and auto increment
		$data    = Array(
							'_language' => Array('title' => 'testing_title_en', 'lang_code'	=>'en-GB', 'alias'=>'cat-3',
												 'description'=>'description3',	'metadata_title' => 'metadata_title3', 
												 'metadata_keywords' => 'keyword31, keyword32',	'metadata_description'=> 'description3' )
						);
						
		$new_instance = PaycartProductcategory::getInstance(0, $data)->save();
		
		$this->assertInstanceOf('PaycartProductcategory', $new_instance);
		
		// resave data with diffrent language
		$data    = Array(	'parent'	=> $instance->getId(),
							'status' 	=> Paycart::STATUS_UNPUBLISHED,
							'_language' => Array('title' => 'testing_title_hi', 'lang_code'	=>'hi-IN', 'alias'=>'cat-3',
												 'description'=>'description41',	'metadata_title' => 'metadata_title4', 
												 'metadata_keywords' => 'keyword41, keyword42',	
												 'metadata_description'=> 'description4' )
						);
		$this->assertInstanceOf('PaycartProductcategory', $new_instance->bind($data)->save());
		
		
		// Expected data
		$au_data = $this->auData_testSave();
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);

		$this->compareTables(	Array(	'jos_paycart_productcategory','jos_paycart_productcategory_lang'), 
								$expectedDataSet,
								Array('jos_paycart_productcategory' => Array( 'created_date','modified_date','cover_media'))
							);
		
		// revert cached stuff
//		PaycartFactory::$session = $session ;		  
	}

	protected function auData_testSave() 
	{	
		$tmpl 		= array_merge(Array('productcategory_id'=>0), 		include RBTEST_PATH_DATASET.'/productcategory/tmpl.php');
		
		$row[]		= array_replace($tmpl, Array( 'productcategory_id'=>1, 'ordering'=>1, 'status' => Paycart::STATUS_PUBLISHED, 'parent'	=> 0));
		
		$row[]		= array_replace($tmpl, Array( 'productcategory_id'=>2, 'ordering'=>2, 'status' => Paycart::STATUS_PUBLISHED, 'parent'	=> 0));
	
		$row[]		= array_replace($tmpl, Array( 'productcategory_id'=> 3, 'ordering'=>3, 'status' => Paycart::STATUS_UNPUBLISHED, 'parent'	=> 2));
		
		$return['jos_paycart_productcategory']	= $row;
		
		
		$tmplLang 	= array_merge(Array('productcategory_lang_id'=>0), 	include RBTEST_PATH_DATASET.'/productcategory/tmpl_lang.php');
		
		$rowLang[]	= array_replace($tmplLang, Array( 	'productcategory_lang_id'=> 1, 'productcategory_id'=>1, 
														'title'=>'testing_title_1','alias'=>'testing-title-1',
														'lang_code' => 'en-GB','description'=>'description1',	'metadata_title' => 'metadata_title1', 
													 	'metadata_keywords' => 'keyword1, keyword2',	'metadata_description'=> 'description1'
												));
		
		$rowLang[]	= array_replace($tmplLang, Array( 	'productcategory_lang_id'=> 2, 'productcategory_id'=> 2, 
														'title'=>'testing_title_2','alias'=>'cat-3',
														'lang_code' => 'hi-IN', 'description'=>'description2',	
														'metadata_title' => 'metadata_title2', 'metadata_keywords' => 'keyword21, keyword22',
														'metadata_description'=> 'description2'
													));
	
		$rowLang[]	= array_replace($tmplLang, Array( 	'productcategory_lang_id'=> 3, 'productcategory_id'=>3, 
														'title'=>'testing_title_en','alias'=>'cat-4',
														'lang_code' => 'en-GB', 'description'=>'description3',	
														'metadata_title' => 'metadata_title3', 'metadata_keywords' => 'keyword31, keyword32',
														'metadata_description'=> 'description3'
												));
		
		$rowLang[]	= array_replace($tmplLang, Array( 	'productcategory_lang_id'=> 4, 'productcategory_id'=>3, 
														'title'=>'testing_title_hi','alias'=>'cat-5',
														'lang_code' => 'hi-IN', 'description'=>'description41',	
														'metadata_title' => 'metadata_title4', 'metadata_keywords' => 'keyword41, keyword42',
														'metadata_description'=> 'description4'
												));
												
		$return['jos_paycart_productcategory_lang'] = $rowLang;
	
		return $return;
	}
	
	/**
	 * Test delete task. Its depends on save data because we are using save data for deletion
	 * 
	 * @depends testSave
	 * @return void
	 */
	public function test_Delete()
	{
		$this->testSave();
		
		// delete id 3 data
		$this->assertInstanceOf('PaycartProductcategory', PaycartProductcategory::getInstance(3)->delete());
		
		$au_data = $this->auData_testSave();
		
		//unset deleted data
		unset($au_data['jos_paycart_productcategory'][2]);
		unset($au_data['jos_paycart_productcategory_lang'][2]);
		unset($au_data['jos_paycart_productcategory_lang'][3]);
		
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);

		$this->compareTables(	Array(	'jos_paycart_productcategory','jos_paycart_productcategory_lang'), 
								$expectedDataSet,
								Array('jos_paycart_productcategory' => Array( 'created_date','modified_date','cover_media'))
							);
		
	}
	
	
}
