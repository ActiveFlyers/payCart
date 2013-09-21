<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/

/**
 * 
 * Test Paycart global stuff 
 * @author mManishTrivedi
 *
 */
class PaycartTest extends PayCartTestCase 
{
	
	/**
	 * 
	 * Test all Available global stuff
	 * 
	 * @return void
	 */
	public function testPaycartConstant() 
	{
		$paycart = new Paycart();
		
		// Product Type
		$this->assertEquals(10, $paycart::PRODUCT_TYPE_PHYSICAL);
		$this->assertEquals(20, $paycart::PRODUCT_TYPE_DIGITAL);
		// Images Constant
		$this->assertEquals('/media/com_paycart/images/', $paycart::IMAGES_ROOT_PATH);
		$this->assertEquals('original_', $paycart::IMAGE_ORIGINAL_PREFIX);
		$this->assertEquals('.orig', $paycart::IMAGE_ORIGINAL_SUFIX);
		$this->assertEquals(200, $paycart::IMAGE_OPTIMIZE_HEIGHT);
		$this->assertEquals(200, $paycart::IMAGE_OPTIMIZE_WIDTH);
		$this->assertEquals('thumb_', $paycart::IMAGE_THUMB_PREFIX);
		$this->assertEquals(100, $paycart::IMAGE_THUMB_HEIGHT);
		$this->assertEquals(133, $paycart::IMAGE_THUMB_WIDTH);
		$this->assertEquals(2, $paycart::IMAGE_FILE_DEFAULT_MAXIMUM_SIZE); //2MB
		$this->assertEquals('.png', $paycart::IMAGE_FILE_DEFAULT_EXTENSION);
		// Cart Entity Status
		$this->assertEquals(0, $paycart::CART_STATUS_NONE);
		$this->assertEquals(5000, $paycart::CART_STATUS_CHECKOUT);	
		$this->assertEquals(5001, $paycart::CART_STATUS_PAID);
		$this->assertEquals(5002, $paycart::CART_STATUS_SHIPPED);
		$this->assertEquals(5003, $paycart::CART_STATUS_DELIVERED);
		$this->assertEquals(5004, $paycart::CART_STATUS_CANCEL);
		$this->assertEquals(5005, $paycart::CART_STATUS_REFUND);
		$this->assertEquals(5006, $paycart::CART_STATUS_REVERSAL);
		$this->assertEquals(5007, $paycart::CART_STATUS_COMPLETE);
		
		$reflaction  = new ReflectionClass('Paycart');
  		//Check number of attribute in class
		$this->assertEquals(21, count($reflaction->getConstants()), 'Check total number of attributes');

	}
		
}
