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
require_once 'stubs/cartTest_product.php';
class PaycartCartTest extends PayCartTestCaseDatabase
{
	
	public function test_add() 
	{
		$this->markTestIncomplete();
	}

	/**
	 * 
	 * Test Calculation
	 * @param Paycart $cart
	 * @param Paycart $cart_after_reinitialize
	 * @dataProvider provider_test_calculate 
	 */
	public function test_calculate(PaycartCart $cart, PaycartCart $cart_after_reinitialize) 
	{
		$this->assertEquals($cart_after_reinitialize, $cart->calculate());
	}
	
	public function provider_test_calculate() 
	{
		
		// mock paycart config
		$mappedMethod = Array('get' => Array( $this , 'stub_paycart_config'));
		
		$mockConfig = $this->getMockPaycartConfig($mappedMethod);
		 
		PayCartTestReflection::setValue('paycartfactory', '_config', $mockConfig);
		
		$cart1 = PaycartCart::getInstance();
		
		// Set dummy Product particulars on cart 
		$bindData1 = Array(	'quantity' => 2, 'unit_price' => 10, 'price' => 20, 'particular_id' => 16,	//total =>32
							'discount' => -2, 'tax'	=> 10 , 'type' => Paycart::CART_PARTICULAR_TYPE_PRODUCT); 

		$bindData2 = Array(	'quantity' => 3, 'unit_price' => 30, 'price' => 90,   'particular_id' => 22,	//total =>107
							'discount' => -3, 'tax'	=> 20 , 'type' => Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		
		$bindData3 = Array(	'unit_price' => 107, 'price' => 107, 	// total => -7
							'discount' 	=> -7, 'type' => Paycart::CART_PARTICULAR_TYPE_PROMOTION);

		$bindData4 = Array(	'unit_price' => 30, 'price' => 30, 	// total => 10
							'tax'	=> 10 , 'type' => Paycart::CART_PARTICULAR_TYPE_DUTIES);
		
		//@PCTODO:: Add Shipping Particular Data 
		$bindData5 = Array(	'type' => Paycart::CART_PARTICULAR_TYPE_SHIPPING);

		PayCartTestReflection::setValue($cart1, 'is_locked', true);
		PayCartTestReflection::setValue($cart1, '_total', 142);
		
		$productParticular1  = $this->getDummyParticular($bindData1);
		$productParticular2	 = $this->getDummyParticular($bindData2);
		
		$promotionParticular = $this->getDummyParticular($bindData3);
		$dutiesParticular	 = $this->getDummyParticular($bindData4);
		
		$shippingParticular	 = $this->getDummyParticular($bindData5);
		
		
		$cartHelper = PaycartFactory::getHelper('cart'); 
		
		$particulars	= Array();
	
		$particulars[Paycart::CART_PARTICULAR_TYPE_PRODUCT] 	=	Array('_product1_'	=> $productParticular1, '_product2_' => $productParticular2);
		$particulars[Paycart::CART_PARTICULAR_TYPE_PROMOTION] 	=	Array('_promotion_'	=> $promotionParticular);
		$particulars[Paycart::CART_PARTICULAR_TYPE_DUTIES] 	 	=	Array('_duties_' 	=> $dutiesParticular);
		$particulars[Paycart::CART_PARTICULAR_TYPE_SHIPPING] 	=	Array('_shipping_' 	=> $shippingParticular );
		
		PayCartTestReflection::setValue($cart1, '_particulars', $particulars);
		
		$cart_after_reinitialize1 = $cart1->getClone();
		
		// Case-2 # when reinitialize everything
		$cart2 = $cart1->getClone();
		PayCartTestReflection::setValue($cart2, 'is_locked', false);

		// stubbing product lib
		$stubProduct1 = new CartTestProductStub;
		$stubProduct1->setId(16);
		$stubProduct2 = new CartTestProductStub;
		$stubProduct2->setId(22);
		
		PaycartLib::$instance['paycartproduct'][16] = $stubProduct1;
		PaycartLib::$instance['paycartproduct'][22] = $stubProduct2;
		
		$callback = array(get_called_class(), 'stub_paycart_carthelper_applyTaxrule');
		// mock helper
		$mockHelper = $this->getMockHelper('PaycartHelperCart', Array('getHash' => Array($this, 'stub_paycart_carthelper_getHash')));
		
		// apply Tax rule
		$mockHelper->expects($this->exactly(3))		// call it 3 times. (Product16 + Product22 +Duties)
				   ->method('applyTaxrule')
				   ->will($this->returnCallback(array(get_called_class(), 'stub_paycart_carthelper_applyTaxrule')));
		
		// apply discount rule
		$mockHelper->expects($this->exactly(3))		// call it 3 times. (Product16 + Product22 + Promotion)
				   ->method('applyDiscountrule')
				   ->will($this->returnCallback(array(get_called_class(), 'stub_paycart_carthelper_applyDiscountrule')));
				   
		PayCartTestReflection::setValue($cart2, '_helper', $mockHelper);
		
		
		$cart_after_reinitialize2 = $cart2->getClone();
		
		// After reinitilaization changed values

		$bindData1 = Array(	'quantity' => 2, 	'unit_price' => 10, 'price' => 20, 'particular_id' => 16, 'total' =>25,
							'discount' => -10, 	'tax'	=> 15 , 'type' => Paycart::CART_PARTICULAR_TYPE_PRODUCT ); 

		$bindData2 = Array(	'quantity' => 3, 'unit_price' => 40, 'price' => 120,   'particular_id' => 22, 'total' =>130,
							'discount' => -15, 'tax'	=> 25 , 'type' => Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		
		$bindData3 = Array(	'unit_price' => 155, 'price' => 155, 'total' => -10,
							'discount' 	=> -10, 'type' => Paycart::CART_PARTICULAR_TYPE_PROMOTION);

		$bindData4 = Array(	'unit_price' => 40, 'price' => 40, 'total' => 35,
							'tax'	=> 35 , 'type' => Paycart::CART_PARTICULAR_TYPE_DUTIES);
		
		$bindData5 = Array(	'type' => Paycart::CART_PARTICULAR_TYPE_SHIPPING);
		
		$particulars	= Array();
		
		$productParticular1  = $this->getDummyParticular($bindData1);
		$productParticular2	 = $this->getDummyParticular($bindData2);
		
		$promotionParticular = $this->getDummyParticular($bindData3);
		$dutiesParticular	 = $this->getDummyParticular($bindData4);
		
		$shippingParticular	 = $this->getDummyParticular($bindData5);
		
		$particulars[Paycart::CART_PARTICULAR_TYPE_PRODUCT] 	=	Array('_product1_'	=> $productParticular1, '_product2_' => $productParticular2);
		$particulars[Paycart::CART_PARTICULAR_TYPE_PROMOTION] 	=	Array('_promotion_'	=> $promotionParticular);
		$particulars[Paycart::CART_PARTICULAR_TYPE_DUTIES] 	 	=	Array('_duties_' 	=> $dutiesParticular);
		$particulars[Paycart::CART_PARTICULAR_TYPE_SHIPPING] 	=	Array('_shipping_' 	=> $shippingParticular );

		PayCartTestReflection::setValue($cart_after_reinitialize2, '_particulars', $particulars);
		PayCartTestReflection::setValue($cart_after_reinitialize2, '_total', 180);
		// cart2 internally call and set _name property
		$cart_after_reinitialize2->getName();
		
		return 	Array(
						Array($cart1, $cart_after_reinitialize1),
						//@JENKINSTODO:: @Manish,  caching issue. Not working with all test cases
						//Array($cart2, $cart_after_reinitialize2)
					);
		
	}
	
	function stub_paycart_config($prop, $default)
	{
		switch($prop) 
		{
			case 'checkout_tax_discount_sequence' :
				return $default;
			case 'currency' :
				return '$';
            default:
                return $default;	
		}
	}
	
	function stub_paycart_carthelper_applyTaxrule(PaycartCart $cart, PaycartCartparticular $particular)
	{	
		switch ($particular->getType()) 
		{
			case Paycart::CART_PARTICULAR_TYPE_PRODUCT : 	// if particule is product 
				if ($particular->getParticularId() == 16) {
					$particular->addTax(15);
				}elseif ($particular->getParticularId() == 22) {
					$particular->addTax(25);
				}
				break;
			case Paycart::CART_PARTICULAR_TYPE_DUTIES :  	// if particule is Duties
				$particular->addTax(35);
				break;
			case Paycart::CART_PARTICULAR_TYPE_SHIPPING :  	// if particule is Duties
				//$particular->addTax(15);
				break;
			default :
				throw new InvalidArgumentException('INVALID :: Particular Type');
		}
		
		$cart->updateTotal();
	}

	
	function stub_paycart_carthelper_applyDiscountrule(PaycartCart $cart, PaycartCartparticular $particular)
	{
		switch ($particular->getType()) 
		{
			case Paycart::CART_PARTICULAR_TYPE_PRODUCT : 	// if particule is product 
				if ($particular->getParticularId() == 16) {
					$particular->addDiscount(-10);
				}elseif ($particular->getParticularId() == 22) {
					$particular->addDiscount(-15);
				}
				break;
			case Paycart::CART_PARTICULAR_TYPE_PROMOTION :  	// if particule is promotion
				$particular->addDiscount(-10);
				break;
			case Paycart::CART_PARTICULAR_TYPE_SHIPPING :  	// if particule is Duties
				//$particular->addDiscount(-35);
				break;
			default :
				throw new InvalidArgumentException('INVALID :: Particular Type');
		}
		$cart->updateTotal();
	}
	
	
	function stub_paycart_carthelper_getHash(PaycartCartparticular $particular)
	{
		switch ($particular->getType()) 
		{
			case Paycart::CART_PARTICULAR_TYPE_PRODUCT : 	// if particule is product 
				if ($particular->getParticularId() == 16) {
					$hash = '_product1_';
				}elseif ($particular->getParticularId() == 22) {
					$hash = '_product2_';
				}
				break;
			case Paycart::CART_PARTICULAR_TYPE_PROMOTION :  	// if particule is promotion
				$hash = '_promotion_';
				break;
			case Paycart::CART_PARTICULAR_TYPE_DUTIES :  	// if particule is Duties
				$hash = '_duties_';
				break;
			case Paycart::CART_PARTICULAR_TYPE_SHIPPING :  	// if particule is Duties
				$hash = '_shipping_';
				break;
			default :
				throw new InvalidArgumentException('INVALID :: Particular Type');
		}
		
		return $hash;
	}

	
	/**
	 * 
	 * Test Calculate Duties(cart-duties) on Cart
	 * @param unknown_type $cart
	 * @param unknown_type $changed_property
	 * @param unknown_type $cart_total
	 * @param unknown_type $expectedPromotion
	 * 
	 * @dataProvider provider_test_calculate_DutiesParticular
	 */
	public function test_calculate_DutiesParticular($cart, $changed_property, $cart_total, $expectedPromotion)  
	{
		// mock helper
		$mockHelper = $this->getMockHelper('PaycartHelperCart');
		// apply discount rule
		$mockHelper->expects($this->exactly(1))
				   ->method('applyTaxrule')
				   ->will($this->returnCallback(
				   			function ($currentCart, $dutiesParticular) use ($changed_property)
				   			{
				   				$dutiesParticular->addTax($changed_property['tax']);
				   				$currentCart->updateTotal();
				   			}
				   	));
		
		$key = '_MANISH_';
		// get hash code
		$mockHelper->expects($this->exactly(1))
				   ->method('getHash')
				   ->will($this->returnValue($key));

		PayCartTestReflection::setValue($cart, '_helper', $mockHelper);
		
		//call calculation on promotion
		$cart->calculateDutiesParticular();
		
		// check cart total
		$this->assertEquals($cart_total, $cart->getTotal());
		
		// check particular set on cart
		$actualParticular = $cart->getParticulars(Paycart::CART_PARTICULAR_TYPE_DUTIES);
		
		$this->assertEquals($expectedPromotion, $actualParticular[$key]);		
	}
	
	public function provider_test_calculate_DutiesParticular() 
	{
		$mockConfig 		= $this->getMockPaycartConfig();
		$cartHelper = PaycartFactory::getHelper('cart');
				
		PayCartTestReflection::setValue('paycartfactory', '_config', $mockConfig);
		
		$cart1 = PaycartCart::getInstance();
		
		// Set dummy Product particulars on cart 
		$bindData1 = Array(	'quantity' => 2, 'unit_price' => 10, 'price' => 20, 'total' => 30, 
							'discount' => 0, 'tax'	=> 10 , 'type' => Paycart::CART_PARTICULAR_TYPE_PRODUCT);

		$productParticular1 = $this->getDummyParticular($bindData1);

		$bindData2 = Array(	'quantity' => 3, 'unit_price' => 30, 'price' => 90, 'total' => 115, 
							'discount' => 0, 'tax'	=> 25 , 'type' => Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		
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

		// change on particular
		$changed_property1 	= Array('tax' => 15 );
		$cart_total1		= 160; 
		
		$bindData 				= Array('price' =>35, 'unit_price' =>35, 
										'tax' => 15, 'total' => 15, 
										'type'=>Paycart::CART_PARTICULAR_TYPE_DUTIES);
		$dutiesParticular1 		= $this->getDummyParticular($bindData);
		
		$cart2 = $cart1->getClone();
		$cart2->setId(5);		// set cart Id
		PaycartTestReflection::setValue($cart2, 'buyer_id', 63);		// set buyer id
		
		$changed_property2 	= Array('tax' => 20 );
		$cart_total2		= 165; 
		
		$particulars[Paycart::CART_PARTICULAR_TYPE_DUTIES] 	=	array('_MANISH_' => $this->getDummyParticular(Array('cartparticular_id'=>2)));
		PaycartTestReflection::setValue($cart2, '_particulars', $particulars);
		
		$bindData['cartparticular_id']	= 2;
		$bindData['tax']				= 20;
		$bindData['total'] 				= 20;
		$bindData['cart_id'] 			= 5;
		$bindData['buyer_id'] 			= 63;
		
		$dutiesParticular2 	= $this->getDummyParticular($bindData);
		 
		
		return Array(
						 Array($cart1, $changed_property1, $cart_total1, $dutiesParticular1)
						,Array($cart2, $changed_property2, $cart_total2, $dutiesParticular2)
					);
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
				   				$promotionParticular->addDiscount($changed_property['discount']);
				   				$currentCart->updateTotal();
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
					);
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
		switch (self::$invokeCounter)
		{
			case 1 :
			case 6 :	// for product-1		
				$particular->addDiscount(-5);
				break;
			case 3 :	// for product-2
			case 8 :
				$particular->addDiscount(-15);
				break;
			default:
				$this->assertTrue(false, "Checkout sequence is not properly working for Product-particular");
		}
		
		$cart->updateTotal();
		self::$invokeCounter++;
	}
	
	public function mock_ApplyTaxrule(PaycartCart $cart, PaycartCartparticular $particular )
	{
		switch (self::$invokeCounter)
		{
			case 2 :
			case 5 :	// for product-1
				$particular->addTax(2);
				break;
			case 4 :	// for product-2
			case 7 :
				$particular->addTax(5);
				break;
			default:
				$this->assertTrue(false, "Checkout sequence is not properly working for Product-particular");
		}
		
		$cart->updateTotal();
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

		$cart = PaycartCart::getInstance();
		
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