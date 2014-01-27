<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support_paycart@readybytes.in
* @author 		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Product Category Helper Test
 */
class PaycartHelperProductcategoryTest extends PayCartTestCase
{	
	/**
	 * Test TranslateAliasToKey
	 *
	 */
	public function testTranslateAliasToKey() 
	{	
		// Create mock data
		$mock_productCategoryLangModel  = $this->getMock('PaycartModelProductcategorylang', Array('loadRecords'));
		
		// Set mock on model 
		PayCartTestReflection::setValue('paycartfactory', '_mocks', Array('paycartmodelproductcategorylang' => $mock_productCategoryLangModel));
		
		$records	= new stdClass();
		
		$records->productcategory_lang_id	= 5;
		$records->productcategory_id		= 21;
		$records->alias						= 'alias';
		$records->lang_code					= 'en_GB';
		$records->column1 					= 'en_GB';
		$records->column2 					= 'en_GB';
		
		$mock_productCategoryLangModel->expects($this->once())						// call on process
			 						  ->method('loadRecords')						// method name
			     					  ->will($this->returnValue(Array($records)));	// return value
		
		$helper = PaycartFactory::getHelper('Productcategory');
		
		// Test : default return value (productcategory_lang_id = 5)
		$this->assertSame(5, $helper->translateAliasToKey('alias'));
		
		// Test : return all expected value of key
		foreach ($records as $key => $value) {
			$this->assertSame($value, $helper->translateAliasToKey('alias', $key));
		}

		// Test : if key is not exist
		try {
			$column_is_not_exist = 'mManish';
			
			$helper->translateAliasToKey('alias',$column_is_not_exist);
		}catch(InvalidArgumentException $e){
			
		}
		
		$this->assertInstanceOf('InvalidArgumentException', $e, 'Wrong exception fire');
		$this->assertSame(Rb_Text::sprintf('COM_PAYCART_INVALID_ARGS', $key),$e->getMessage(), 'MisMatch: exception message ');
		
		
		
		// Test : 
		// 		1. execute query again when reset is true 
		//		2. when result is empty
		$mock_productCategoryLangModel  = $this->getMock('PaycartModelProductcategorylang', Array('loadRecords'));
		
		// Set mock on model 
		PayCartTestReflection::setValue('paycartfactory', '_mocks', Array('paycartmodelproductcategorylang' => $mock_productCategoryLangModel));
		
		$mock_productCategoryLangModel->expects($this->once())						// call on process
			 						  ->method('loadRecords')						// method name
			     					  ->will($this->returnValue(''));	// return value
	
		// execute query one more time and result is empty
		$this->assertNull($helper->translateAliasToKey('alias','productcategory_lang_id', true));
	}
}
