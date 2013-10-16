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
		
		// Product Type
		$this->assertEquals(10, PayCart::PRODUCT_TYPE_PHYSICAL);
		$this->assertEquals(20, PayCart::PRODUCT_TYPE_DIGITAL);
		// Images Constant
		$this->assertEquals('/media/com_paycart/images/', PayCart::IMAGES_ROOT_PATH);
		$this->assertEquals('original_', PayCart::IMAGE_ORIGINAL_PREFIX);
		$this->assertEquals('.orig', PayCart::IMAGE_ORIGINAL_SUFIX);
		$this->assertEquals(200, PayCart::IMAGE_OPTIMIZE_HEIGHT);
		$this->assertEquals(200, PayCart::IMAGE_OPTIMIZE_WIDTH);
		$this->assertEquals('thumb_', PayCart::IMAGE_THUMB_PREFIX);
		$this->assertEquals(100, PayCart::IMAGE_THUMB_HEIGHT);
		$this->assertEquals(133, PayCart::IMAGE_THUMB_WIDTH);
		$this->assertEquals(2, PayCart::IMAGE_FILE_DEFAULT_MAXIMUM_SIZE); //2MB
		$this->assertEquals('.png', PayCart::IMAGE_FILE_DEFAULT_EXTENSION);
		// Cart Entity Status
		$this->assertEquals(0, PayCart::CART_STATUS_NONE);
		$this->assertEquals(5000, PayCart::CART_STATUS_CHECKOUT);	
		$this->assertEquals(5001, PayCart::CART_STATUS_PAID);
		$this->assertEquals(5002, PayCart::CART_STATUS_SHIPPED);
		$this->assertEquals(5003, PayCart::CART_STATUS_DELIVERED);
		$this->assertEquals(5004, PayCart::CART_STATUS_CANCEL);
		$this->assertEquals(5005, PayCart::CART_STATUS_REFUND);
		$this->assertEquals(5006, PayCart::CART_STATUS_REVERSAL);
		$this->assertEquals(5007, PayCart::CART_STATUS_COMPLETE);
		
		$this->assertEquals('attribute_', PayCart::PRODUCT_FILTER_FIELD_PREFIX);
		
		$reflaction  = new ReflectionClass('Paycart');
  		//Check number of attribute in class
		$this->assertEquals(22, count($reflaction->getConstants()), 'Check total number of attributes');

	}
		
}
