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
		$this->assertEquals('draft',	Paycart::STATUS_CART_DRAFT);		// none
		$this->assertEquals('checkout',	Paycart::STATUS_CART_CHECKOUT);
		$this->assertEquals('paid',		Paycart::STATUS_CART_PAID);
		$this->assertEquals('cancel', 	Paycart::STATUS_CART_CANCEL);
		$this->assertEquals('complete',	Paycart::STATUS_CART_COMPLETE);
		
		// JOOMLA System Message Type
		$this->assertEquals(Paycart::MESSAGE_TYPE_MESSAGE,	'message'); 
		$this->assertEquals(Paycart::MESSAGE_TYPE_WARNING,	'warning');
		$this->assertEquals(Paycart::MESSAGE_TYPE_NOTICE,	'notice');
		$this->assertEquals(Paycart::MESSAGE_TYPE_ERROR,	'error');
			
		//Processor type
		$this->assertEquals(Paycart::PROCESSOR_TYPE_DISCOUNTRULE,	'discountrule');
		$this->assertEquals(Paycart::PROCESSOR_TYPE_TAXRULE,		'taxrule');
		$this->assertEquals(Paycart::PROCESSOR_TYPE_SHIPPINGRULE,	'shippingrule');
			
		//Paycart Status
		$this->assertEquals(Paycart::STATUS_PUBLISHED, 	 'published');		// Content publish for end-user
		$this->assertEquals(Paycart::STATUS_TRASHED,	 'trashed');		// Trashed content 
		$this->assertEquals(Paycart::STATUS_UNPUBLISHED, 'unpublished');	// Unpublish content. Visible only backend
		$this->assertEquals(Paycart::STATUS_INVISIBLE,	 'invisible');		// Not listed in front-end, access by url
		//@PCTODO :: admin or owner
		$this->assertEquals(Paycart::STATUS_ADMIN,		 'admin');			// visible only for admin-user (at front-end)
				
		// shipping rule
		$this->assertEquals(Paycart::SHIPPINGRULE_LIST_ORDER_BY_PRICE, 		 'price');
		$this->assertEquals(Paycart::SHIPPINGRULE_LIST_ORDER_BY_ORDERING, 	 'ordering');
		$this->assertEquals(Paycart::SHIPPINGRULE_LIST_ORDER_IN_ASC, 		 'ASC');
		$this->assertEquals(Paycart::SHIPPINGRULE_LIST_ORDER_IN_DESC,		 'DESC');
		
		
			// Cart Particulars Type
		$this->assertEquals(Paycart::CART_PARTICULAR_TYPE_PRODUCT, 			 'product');
		$this->assertEquals(Paycart::CART_PARTICULAR_TYPE_PROMOTION,	 	 'promotion');
		$this->assertEquals(Paycart::CART_PARTICULAR_TYPE_DUTIES, 			 'duties');
		$this->assertEquals(Paycart::CART_PARTICULAR_TYPE_SHIPPING,			 'shipping');
			// @Future Purpose
		//$this->assertEquals(Paycart::CART_PARTICULAR_TYPE_SHIPPING_PROMOTION,	 'shippingpromotion';
		$this->assertEquals(Paycart::CART_PARTICULAR_TYPE_ADJUSTMENT,			 'adjustment');
		
		$this->assertEquals(Paycart::CART_PARTICULAR_QUANTITY_MINIMUM,  1);
		
		$this->assertEquals(Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_TAX_DISCOUNT,  'td');
		$this->assertEquals(Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_DISCOUNT_TAX,  'dt');
			
		$this->assertEquals(Paycart::GROUPRULE_TYPE_PRODUCT,  'product');
		$this->assertEquals(Paycart::GROUPRULE_TYPE_BUYER, 	  'buyer');
		
		$this->assertEquals(Paycart::ATTRIBUTE_PATH_MEDIA,	 '/media/com_paycart/media/');

	}
		
}
