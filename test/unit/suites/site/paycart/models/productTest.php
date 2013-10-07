<?php

/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/


/** 
 * Product Model Test 
 */
class PaycartModelProductTest extends PayCartTestCaseDatabase
{
	
	public $testValidate = Array('_data/dataset/product/product-2.php');
	/**
	 *
	 * test validate task of ProductModel 
	 * @return void
	 */
	public function testValidate() 
	{
		$paycartModel = PaycartFactory::getInstance('product','model');

		$productTmpl =  array_merge(Array('product_id'=>0), include RBTEST_PATH_DATASET.'/product/tmpl.php');
		// Case1 : If normal product save without any variation
		
		$data 	= array_replace($productTmpl, Array('variation_of' => 0, 'alias'=> 'alias-1','sku'=>'sku'));
		
		$this->assertTrue($paycartModel->validate($data));
		
		// Case2 : If product save with any variation
		$data 	= array_replace($data, Array('variation_of' => 3));
		$this->assertTrue($paycartModel->validate($data));
		
		// Case3 : Product save with multi-level variation (A->B->C)
		// it will fire UnexpectedValueException exception
		$data 	= array_replace($data, Array('variation_of' => 8));
		$msg = '';
		
		try{
			$paycartModel->validate($data);
		}catch (UnexpectedValueException $e) {
			$msg = $e->getMessage();
		}
		
		$this->assertSame(Rb_Text::_('COM_PAYCART_NOT_SUPPORT_MULTILEVEL_VARIATION'), $msg, 'Exceptiom should be fired');
		
		//case4 : re-save existing product with variation
		$data 	= array_replace($data, Array('product_id'=> 8, 'variation_of' => 3));
		$this->assertTrue($paycartModel->validate($data));

	}
}