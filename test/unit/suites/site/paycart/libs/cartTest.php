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
	/**
	 * testing save 
	 * Case 1 : Only buyer_id is being posted(when blank cart is created)
	 */
	public function testSave_case1()
	{
		// prepare data having only buyer_id
		$data = Array('buyer_id' => '46');
		
		//Save data
		$this->assertInstanceOf('PaycartCart', PaycartCart::getInstance(0,$data)->save());
		
		// Expected data
		$row	 = $this->auDataCart();
		$au_data = Array( "jos_paycart_cart" => Array ($row[1]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_cart', $expectedDataSet, Array( 'modifiers', 'created_date','modified_date', 'checkout_date','params', 
																		 'paid_date','complete_date','cancellation_date','refund_date','currency'));
		
		//check records in paycart_cartparticulars, it should be empty
		$records = PaycartFactory::getInstance('cartparticulars','model')->loadRecords();
		
		$this->assertSame(array(),$records);
	}
	
	
	public $testSave_case2 = Array(
								'_data/dataset/product/product-1.php',
								'_data/dataset/attribute/attribute-1.php',
								'_data/dataset/attributevalue/attributevalue-1.php'
							 );
	/**
	 * testing save 
	 * Case 2 : Post single data in cartParticular 
	 */					
	public function testSave_case2()
	{
		$this->testSave_case1();
		
		// prepare data having only buyer_id
		$data = Array('buyer_id' => '47',
					  'cartparticulars' => array( 1 => array( 
													'product_id' => 1,
													'quantity'   => 5,
												))
					 );
					 
		//Save data
		$this->assertInstanceOf('PaycartCart', PaycartCart::getInstance(0,$data)->save());
		
		//comparing cart
		// Expected data
		$row	 = $this->auDataCart();
		$au_data = Array( "jos_paycart_cart" => Array ($row[1],$row[2]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_cart', $expectedDataSet, Array( 'modifiers', 'created_date','modified_date', 'checkout_date','params', 
																		 'paid_date','complete_date','cancellation_date','refund_date','currency'));
		
		//comparing cartparticulars
		// Expected data
		$row	 = $this->auDataCartParticulars();
		$au_data = Array( "jos_paycart_cartparticulars" => Array ($row[2]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_cartparticulars', $expectedDataSet, array('tax','discount','price','shipment_date',
																							    'reversal_date','delivery_date','params'));
		
		
		
	}
	
	public $testSave_case3 = Array(
								'_data/dataset/product/product-1.php',
								'_data/dataset/attribute/attribute-1.php',
								'_data/dataset/attributevalue/attributevalue-1.php'
							 );
	/**
	 * testing save 
	 * Case 3 : Post multiple cartparticulars in cartParticular 
	 */					
	public function testSave_case3()
	{
		$this->testSave_case2();
		
		// prepare data having only buyer_id
		$data = Array('buyer_id' => '48',
					  'cartparticulars' => array( 1 => array( 
															'product_id' => 3,
															'quantity'   => 5
															),
												  2 => array(
												  			'product_id' => 4,
												  			'quantity'	 => 10
												  			)
												)
					 );
					 
		//Save data
		$this->assertInstanceOf('PaycartCart', PaycartCart::getInstance(0,$data)->save());
		
		//comparing cart
		// Expected data
		$row	 = $this->auDataCart();
		$au_data = Array( "jos_paycart_cart" => Array ($row[1], $row[2], $row[3]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_cart', $expectedDataSet, Array( 'modifiers', 'created_date','modified_date', 'checkout_date','params', 
																		 'paid_date','complete_date','cancellation_date','refund_date','currency'));

		//comparing cartparticulars
		// Expected data
		$row	 = $this->auDataCartParticulars();
		$au_data = Array( "jos_paycart_cartparticulars" => Array ($row[2],$row[3],$row[4]) );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_cartparticulars', $expectedDataSet, array('tax','discount','price','shipment_date',
																							    'reversal_date','delivery_date','params'));
	}
	
	public $testSave_case4 = Array(
								'_data/dataset/product/product-1.php',
								'_data/dataset/attribute/attribute-1.php',
								'_data/dataset/attributevalue/attributevalue-1.php'
							 );
	/**
	 * testing save 
	 * Case 4 : when product doesn't exist in records
	 */
	public function testSave_case4()
	{
		// prepare data having only buyer_id
		$data = Array('buyer_id' => '49',
					  'cartparticulars' => array( 1 => array( 
															'product_id' => 66,
															'quantity'   => 2
															))
					 );
					 
		//Save data
		$this->assertInstanceOf('PaycartCart', PaycartCart::getInstance(0,$data)->save());
		
		// Expected data
		$row	 = $this->auDataCart();
		$au_data = Array( "jos_paycart_cart" => Array ($row[4]));
		
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);
		
		// Compare table
		$this->compareTable('jos_paycart_cart', $expectedDataSet, Array( 'modifiers', 'created_date','modified_date', 'checkout_date','params', 
																		 'paid_date','complete_date','cancellation_date','refund_date','currency'));
		
		//check records in paycart_cartparticulars, it should be empty
		$records = PaycartFactory::getInstance('cartparticulars','model')->loadRecords();
		
		$this->assertSame(array(),$records);
					 
					 
	}
	
	/**
	 * Gold table for Cart
	 */
	public function auDataCart()
	{
		static $row	= Array();
		
		if(!empty($row)) {
			return $row;
		}
		
		$row[0] 	= array_merge(Array('cart_id'=>0), include RBTEST_PATH_DATASET.'/cart/tmpl.php');
		
		$row[1]		= array_replace($row[0], Array('cart_id' => 1, 'buyer_id' => '46', 'subtotal' => 0.00 , 'total' => 0.00,
		                                           'status' => Paycart::CART_STATUS_NONE, 'address_id'=> 0));
		$row[2]		= array_replace($row[1], Array('cart_id' => 2, 'buyer_id' => '47'));
		
		$row[3]		= array_replace($row[1], Array('cart_id' => 3, 'buyer_id' => '48'));
		
		$row[4]		= array_replace($row[1], Array('cart_id' => 1, 'buyer_id' => '49'));
		
		return $row;
	}
	
	/**
	 * Gold table for CartParticulars
	 */
	public function auDataCartParticulars()
	{
		$row = Array();
		
		$row[0]  = array_merge(Array('cartparticulars_id'=>0), include RBTEST_PATH_DATASET.'/cartparticulars/tmpl.php');
		
		$row[1]	 = $row[0];
		
		$row[2]	 = array_replace($row[1], Array('cartparticulars_id'=>1, 'buyer_id' => 47, 'product_id' => '1','cart_id' => '2', 
												'title' => 'Product-1', 'quantity' => '5', 'unit_cost' => 0.00));
		
		$row[3]	 = array_replace($row[1], Array('cartparticulars_id'=>2, 'buyer_id' => 48, 'product_id' => '3','cart_id' => '3', 
												'title' => 'Product-3', 'quantity' => '5', 'unit_cost' => 200.00));
		
		$row[4]	 = array_replace($row[1], Array('cartparticulars_id'=>3, 'buyer_id' => 48, 'product_id' => '4','cart_id' => '3', 
												'title' => 'Product-4', 'quantity' => '10', 'unit_cost' => 250.00));
		
		return $row;
	}	 
}