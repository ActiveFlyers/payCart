<?php

/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		rimjhim
*/

/** 
 * Test Cart Lib
 * @author rimjhim
 */
class PaycartCartTest extends PayCartTestCaseDatabase
{
	
	public function test_add() 
	{
		$this->markTestIncomplete();
	}
	
	public function test_calculate() 
	{
		$this->markTestIncomplete();
	}
	
	public function test_calculate_DutiesParticular() 
	{
		$this->markTestIncomplete();
	}
	
	/**
	 * 
	 * Test Calculate Promotion(cart-discount) on Cart
	 * @param unknown_type $cart
	 * @param unknown_type $changed_property
	 * @param unknown_type $cart_total
	 * @param unknown_type $expectedPromotion
	 * 
	 * @dataProvider provider_test_Calculate_PromotionParticular
	 */
	public function test_Calculate_PromotionParticular($cart, $changed_property, $cart_total, $expectedPromotion)  
	{
		// mock helper
		$mockHelper = $this->getMockHelper('PaycartHelperCart');
		// apply discount rule
		$mockHelper->expects($this->exactly(1))
				   ->method('applyDiscountrule')
				   ->will($this->returnCallback(
				   			function ($currentCart, $promotionParticular) use ($changed_property)
				   			{
				   				$promotionParticular->setDiscount($changed_property['discount']);
				   				
				   			}
				   	));
		
		$key = '_MANISH_';
		// get hash code
		$mockHelper->expects($this->exactly(1))
				   ->method('getHash')
				   ->will($this->returnValue($key));

		PayCartTestReflection::setValue($cart, '_helper', $mockHelper);
		
		//call calculation on promotion
		$cart->calculatePromotionParticular();
		
		// check cart total
		$this->assertEquals($cart_total, $cart->getTotal());
		
		// check particular set on cart
		$actualParticular = $cart->getParticulars(Paycart::CART_PARTICULAR_TYPE_PROMOTION);
		
		$this->assertEquals($expectedPromotion, $actualParticular[$key]);		
	}
	
	public function provider_test_Calculate_PromotionParticular() 
	{
		$mockConfig 		= $this->getMockPaycartConfig();
		$cartHelper = PaycartFactory::getHelper('cart');
				
		PayCartTestReflection::setValue('paycartfactory', '_config', $mockConfig);
		
		$cart1 = PaycartCart::getInstance();
		
		// Set dummy Product particulars on cart 
		$bindData1 = Array(	'quantity' => 2, 'unit_price' => 10, 'price' => 20, 'total' => 20, 
							'discount' => 0, 'tax'	=> 0 , 'type' => Paycart::CART_PARTICULAR_TYPE_PRODUCT);

		$productParticular1 = $this->getDummyParticular($bindData1);

		$bindData2 = Array(	'quantity' => 3, 'unit_price' => 30, 'price' => 90, 'total' => 90, 
							'discount' => 0, 'tax'	=> 0 , 'type' => Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		
		$productParticular2 = $this->getDummyParticular($bindData2);
		
		$hash1 = $cartHelper->gethash($productParticular1);
		$hash2 = $cartHelper->gethash($productParticular2);
		
		$particulars[Paycart::CART_PARTICULAR_TYPE_PRODUCT] 	=	Array($hash1 => $productParticular1, $hash2 = $productParticular2);
		$particulars[Paycart::CART_PARTICULAR_TYPE_PROMOTION] 	=	array();
		$particulars[Paycart::CART_PARTICULAR_TYPE_DUTIES] 	 	=	array();
		$particulars[Paycart::CART_PARTICULAR_TYPE_SHIPPING] 	=	array();
		
		//set product particular on cart
		PaycartTestReflection::setValue($cart1, '_particulars', $particulars);
		PaycartTestReflection::setValue($cart1, '_total', 110);

		$changed_property1 	= Array('discount' => -15);
		$cart_total1		= 95; 
		
		$bindData 				= Array('price' =>110, 'unit_price' =>110, 
										'discount' => -15, 'total' => -15, 
										'type'=>Paycart::CART_PARTICULAR_TYPE_PROMOTION);
		$promotionParticular1 	= $this->getDummyParticular($bindData);
		
		$cart2 = $cart1->getClone();
		$cart2->setId(5);		// set cart Id
		PaycartTestReflection::setValue($cart2, 'buyer_id', 63);		// set buyer id
		
		$changed_property2 	= Array('discount' => -20);
		$cart_total2		= 90; 
		
		$particulars[Paycart::CART_PARTICULAR_TYPE_PROMOTION] 	=	array('_MANISH_' => $this->getDummyParticular(Array('cartparticular_id'=>2)));
		PaycartTestReflection::setValue($cart2, '_particulars', $particulars);
		
		$bindData['cartparticular_id']	= 2;
		$bindData['discount']			= -20;
		$bindData['total'] 				= -20;
		$bindData['cart_id'] 			= 5;
		$bindData['buyer_id'] 			= 63;
		
		$promotionParticular2 	= $this->getDummyParticular($bindData);
		 
		
		return Array(
						 Array($cart1, $changed_property1, $cart_total1, $promotionParticular1)
						,Array($cart2, $changed_property2, $cart_total2, $promotionParticular2)
//						Array(Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_TAX_DISCOUNT)
					);
	}
	
	/**
	 * Calculate Promotion(cart-discount) on Cart 
	 * 
	 * @return PaycartLib Instance
	 */
	public function calculatePromotionParticular()  
	{
		//Since, We have already reset Product-Promotion
		//Therefore, We need to re-build {product-promotion} type cart-particular. Hence-Proved  
		$requestObject = new stdClass();
		$requestObject->particular_id = 0;
		
		// Product-Promotion have single value array 
		foreach ($this->getParticulars(Paycart::CART_PARTICULAR_TYPE_PROMOTION) as $key => $particular) {
			//Already created {Product-promotion} type cart-particulars
			$requestObject->particular_id = $particular->getId();
		}
		
		//create product-promotion particular
		$particular = $this->createPromotionParticular($requestObject);
		
		//invoke tax system
		$this->_helper->applyDiscountrule($this, $particular);
		
		//get hash key
		$key = $this->_helper->getHash($particular);
		
		$this->_particulars[$key] 	= $particular;
				
		return $this;
	}
	
	
	
	/**
	 * Enter description here ...
	 * @param  $sequence
	 * 
	 * @dataProvider provider_test_Calculate_ProductsParticular
	 */
	public function test_Calculate_ProductsParticular($sequence)
	{
		## Case 1: Checkout sequence is TD
		// mock Paycart config
		$mockConfig = $this->getMockPaycartConfig();
		
		$mockConfig->expects($this->any())
					->method('get')
					->with($this->logicalOr(
                     				$this->equalTo('currency'), 
                     				$this->equalTo('checkout_tax_discount_sequence')))
					->will($this->returnCallback(
							function($prop, $default) use ($sequence)
							{
								switch($prop) {
				                	case 'checkout_tax_discount_sequence' :
				                		return $sequence;
				                	case 'currency' :
				                		return '$';
				                	default:
				                		return $default;	
									}
				             }
						));

		PaycartTestReflection::setValue('paycartfactory', '_config', $mockConfig);
		
		$cart = PaycartCart::getInstance();
		
		// Set dummy Product particulars on cart 
		$bindData1 = Array(	'quantity' => 2, 'unit_price' => 10, 'price' => 20, 'total' => 20, 
							'discount' => 0, 'tax'	=> 0 , 'type' => Paycart::CART_PARTICULAR_TYPE_PRODUCT);

		$productParticular1 = $this->getDummyParticular($bindData1);

		$bindData2 = Array(	'quantity' => 3, 'unit_price' => 30, 'price' => 90, 'total' => 90, 
							'discount' => 0, 'tax'	=> 0 , 'type' => Paycart::CART_PARTICULAR_TYPE_PRODUCT);

		$productParticular2 = $this->getDummyParticular($bindData2);
		
		$cartHelper = PaycartFactory::getHelper('cart');
		
		$hash1 = $cartHelper->gethash($productParticular1);
		$hash2 = $cartHelper->gethash($productParticular2);
		
		$productParticulars = Array($hash1 => $productParticular1, $hash2 = $productParticular2);

		//set product particular on cart
		PaycartTestReflection::setValue($cart, '_particulars', Array(Paycart::CART_PARTICULAR_TYPE_PRODUCT => $productParticulars));
		
		
			
			
		
		//mock method and cart-helper
		$mappedMethod = 	Array(
									'applyDiscountrule' => Array( $this , 'mock_ApplyDiscountrule'),
									'applyTaxrule'		=> Array( $this , 'mock_ApplyTaxrule')
								 ); 
		
		PaycartTestReflection::setValue($cart, '_helper', $this->getMockHelper('paycarthelpercart', $mappedMethod));
		
		//Invoke calculateProductParticulars
		$cart->calculateProductsParticular();
		
		// Test : Cart total is changed
		$this->assertEquals(97, $cart->getTotal());
		
		// Test : Product Particular set on cart and changed particular property {discount, tax, total}
		$bindData1['tax'] = 2;
		$bindData2['tax'] = 5;
		
		$bindData1['discount'] = -5;
		$bindData2['discount'] = -15;
		
		$bindData1['total'] = 17;
		$bindData2['total'] = 80;
		
		$productParticular1 = $this->getDummyParticular($bindData1);
		$productParticular2 = $this->getDummyParticular($bindData2);
		$productParticulars = Array($hash1 => $productParticular1, $hash2 = $productParticular2);
		
		foreach ($cart->getParticulars(paycart::CART_PARTICULAR_TYPE_PRODUCT) as $key => $particular ) {
			$this->assertEquals($productParticulars[$key], $particular);
		}
		
	} 
	
	public function provider_test_Calculate_ProductsParticular() 
	{
		return Array(
						Array(Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_DISCOUNT_TAX),
						Array(Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_TAX_DISCOUNT)
					);
	}
	
	static $invokeCounter = 1 ;
	
	public function mock_ApplyDiscountrule(PaycartCart $cart, PaycartCartparticular $particular )
	{	
		$a=2;	
		switch (self::$invokeCounter)
		{
			case 1 :
			case 6 :	// for product-1		
				$particular->setDiscount(5);
				break;
			case 3 :	// for product-2
			case 8 :
				$particular->setDiscount(-15);
				break;
			default:
				$this->assertTrue(false, "Checkout sequence is not properly working for Product-particular");
		}
		self::$invokeCounter++;
	}
	
	public function mock_ApplyTaxrule(PaycartCart $cart, PaycartCartparticular $particular )
	{
		$a = 4;
		switch (self::$invokeCounter)
		{
			case 2 :
			case 5 :	// for product-1
				$particular->setTax(2);
				break;
			case 4 :	// for product-2
			case 7 :
				$particular->setTax(5);
				break;
			default:
				$this->assertTrue(false, "Checkout sequence is not properly working for Product-particular");
		}
		
		self::$invokeCounter++;
	}
	
	/**
	 * 
	 * test create-Shipping-Particular 
	 */
	public function test_Create_ShippingParticular()
	{
		$this->markTestIncomplete('Need to imaplement code then write test case');
	} 
	
	/**
	 * 
	 * test create-Duties-Particular 
	 */
	public function test_Create_DutiesParticular() 
	{
		$cart = PaycartCart::getInstance();
		
		## Case 1 : cart empty and nothing in args
		
		$bindData = Array('type'=>Paycart::CART_PARTICULAR_TYPE_DUTIES);
		
		$expectedParticular = $this->getDummyParticular($bindData);
		$actualParticular	= $cart->createDutiesParticular();
		
		$this->assertEquals($expectedParticular, $actualParticular);
		
		## Case 2 : Cart is set and args is empty
		$productParticular1 = $this->getDummyParticular(Array('tax'=>20, 'type'=>Paycart::CART_PARTICULAR_TYPE_PRODUCT));
		$productParticular2 = $this->getDummyParticular(Array('tax'=>30, 'type'=>Paycart::CART_PARTICULAR_TYPE_PRODUCT));
		
		//set product particular on cart
		TestReflection::setValue($cart, '_particulars', Array(Paycart::CART_PARTICULAR_TYPE_PRODUCT => array($productParticular1,$productParticular2)));
		
		$bindData = Array('type'=>Paycart::CART_PARTICULAR_TYPE_DUTIES, 'price'=>50,  'unit_price' => 50);
		
		$expectedParticular = $this->getDummyParticular($bindData);
		$actualParticular	= $cart->createDutiesParticular();
		
		$this->assertEquals($expectedParticular, $actualParticular);
		
		## Case 3: cart is already set but args is fwd
		$particular = new stdClass();
		$particular->price 		= 15;
		$particular->unit_price = 25;
		$particular->total 		= 125;
		
		$bindData = Array('type' => Paycart::CART_PARTICULAR_TYPE_DUTIES, 'unit_price' => 25, 'price' =>15, 'total' => 125 );
		$expectedParticular = $this->getDummyParticular($bindData);
		$actualParticular	= $cart->createDutiesParticular($particular);
		
		$this->assertEquals($expectedParticular, $actualParticular);  
	}
	
	/**
	 * 
	 * Test Create Product-Promotion cart-particular
	 */
	public function test_Create_PromotionParticular() 
	{
		$particular 	= new stdClass();
		$cart 			= PaycartCart::getInstance();
		
		##Case-1: Initial data build without any args
		//set cart total
		PayCartTestReflection::setValue($cart, '_total', 100);
		
		$bindData = Array(        				
        					'type'  => Paycart::CART_PARTICULAR_TYPE_PROMOTION, 
        					'unit_price'=>100, 'price' => 100
        				) ;
        				
        $expectedParicular	= $this->getDummyParticular($bindData);
        $actualParticular	= $cart->createPromotionParticular($particular);

        $this->assertEquals($expectedParicular, $actualParticular);
        
        ## Case-2: Existing particular
        $particular->cartparticular_id = 1;
        $particular->unit_price	= 10;
        $particular->price		= 20;
        $particular->total		= -40;
        
        $bindData = Array(
        				'cartparticular_id' => 1, 'type'  => Paycart::CART_PARTICULAR_TYPE_PROMOTION,
        				'unit_price'=>10, 'price' => 20, 'total' => -40
        				) ;
        				
        $expectedParicular	= $this->getDummyParticular($bindData);
        $actualParticular	= $cart->createPromotionParticular($particular);

        $this->assertEquals($expectedParicular, $actualParticular);
	}
	
	/**
	 * 
	 * Test create Product type particular
	 */
	public function test_Create_ProductParticular() 
	{
		$particular 	= new stdClass();
		$actualMessage 	= 'ERROR';
		$cart 			= PaycartCart::getInstance();
		
		##Case-1# Particular id is not exist in args
		try{
			$cart->createProductParticular($particular);
			
		} catch (InvalidArgumentException $e) {
			$actualMessage = $e->getMessage();
		}
		
		$this->assertEquals(Rb_Text::_('COM_PAYCART_CART_PARTICULAR_EMPTY_PRODUCT'), $actualMessage);
        
        ##Case-2# if default quantity is greater than quantity args
        $particular->particular_id = 1;
        $particular->quantity	   = 0;
        try{
			$cart->createProductParticular($particular);
			
		} catch (InvalidArgumentException $e) {
			$actualMessage = $e->getMessage();
		}
		
		$this->assertEquals(Rb_Text::_('COM_PAYCART_CART_PARTICULAR_QUANTITY_UNDERFLOW'), $actualMessage);
		
		##case-3#. Load product form Product lib
		// initial data
		$particular->particular_id = 1;
        unset($particular->quantity); // Set default quantity automatic
        
        // Mock dependency 
		$mockLib = $this->getMockLib('PaycartProduct');
		
		$mockLib->expects($this->any())
             	->method('getPrice')
             	->will($this->returnValue('100'));
        $mockLib->expects($this->any())
             	->method('getId')
             	->will($this->returnValue('1'));
        
        PaycartLib::$instance['paycartproduct'][1] = $mockLib;
        
        $bindData = Array(
        				'particular_id' => 1,
        				'type'  => Paycart::CART_PARTICULAR_TYPE_PRODUCT, 'quantity' => Paycart::CART_PARTICULAR_QUANTITY_MINIMUM,
        				'unit_price'=>100, 'price' => 100, 'total' => 100
        				) ;
        				
        $expectedParicular	= $this->getDummyParticular($bindData);
        $actualParticular	= $cart->createProductParticular($particular);

        $this->assertEquals($expectedParicular, $actualParticular);
        
        ## Case-4: Existing particular
        $particular->cartparticular_id = 1;
        $bindData = Array(
        				'particular_id' => 1, 'cartparticular_id' => 1,
        				'type'  => Paycart::CART_PARTICULAR_TYPE_PRODUCT, 'quantity' => Paycart::CART_PARTICULAR_QUANTITY_MINIMUM,
        				'unit_price'=>100, 'price' => 100, 'total' => 100
        				) ;
        				
        $expectedParicular	= $this->getDummyParticular($bindData);
        $actualParticular	= $cart->createProductParticular($particular);

        $this->assertEquals($expectedParicular, $actualParticular);
        
        
        ## Case-5: Predefine tax,descount etc
        
        $particular->tax		= 50;
        $particular->discount	= -25;
        
        $bindData = Array(
        				'particular_id' => 1, 'cartparticular_id' => 1, 'tax'=> 50, 'discount' => -25,
        				'type'  => Paycart::CART_PARTICULAR_TYPE_PRODUCT, 'quantity' => Paycart::CART_PARTICULAR_QUANTITY_MINIMUM,
        				'unit_price'=>100, 'price' => 100, 'total' => 125
        				) ;
        				
		$expectedParicular	= $this->getDummyParticular($bindData);
        $actualParticular	= $cart->createProductParticular($particular);

        $this->assertEquals($expectedParicular, $actualParticular);
	}
	
	/**
	 * 
	 * new instance of PaycartCartparticular
	 * @param Array $bindData
	 * 
	 * @return PaycartCartparticular instance
	 */
	public function getDummyParticular(Array $bindData)
	{
		$data = Array(
        				'cartparticular_id' => 0, 'cart_id'=> 0, 'buyer_id' => 0, 'particular_id' => 0,
        				'type'  => '', 'unit_price' => 0, 'quantity' => Paycart::CART_PARTICULAR_QUANTITY_MINIMUM,
        				'price' => 0 , 'tax' => 0, 'discount' => 0, 'total' => 0, 'title' => '',
						'message' => ''
        			) ;
        			       			
        $data = array_replace($data, $bindData);
        			
        return PaycartCartparticular::getInstance(0, $data);
	}
}