<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in 
*/

/**
 * 
 * Shipping Rule Helper Test
 * @author Gaurav Jain
 */
class PaycartHelperShippingruleTest extends PayCartTestCase
{	
	public static function providerTestGetPackageList()
    {
    	/**
    	 * 	arg format
    	 *
    	 * 	$product_grouped_by_address			: 	array($address_id_1 => array($product_id_1, $pruduct_id_2, ...), ...),
         * 	$shippingrules_grouped_by_product	:	array($product_id_1 => array($shippingrule_id_1, $shippingrule_id_2...), ...)
         * 	$package_list						:	array($addressId => array(
         *					 								$package_id_1 => array(
         * 														'product_list' => array($product_id_1, ...),
         * 														'shippingrule_list' => array($shippingrule_id_1, ...)),
         * 														.....),
         * 													......)	
         * 													)    	
    	 */
    	
    	
    	for($counter = 0; $counter <= 10; $counter++){
    		foreach(array('PRODUCT', 'ADDRESS', 'SHIPPING_RULE', 'PACKAGE') as $entity){
    			if(!defined('PAYCART_TEST_'.$entity.'_'.$counter)){
    				define('PAYCART_TEST_'.$entity.'_'.$counter, $counter);
    			}  	
    		}
    	}
    	
    	return array(
    			// with 1 address, 1 product, 1 shipping rule
        		array(	array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PRODUCT_1)),
        				array(PAYCART_TEST_PRODUCT_1 	=> array(	PAYCART_TEST_SHIPPING_RULE_1)),
        				array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PACKAGE_0 =>	array(	
        																				'product_list' => array(PAYCART_TEST_PRODUCT_1), 
        																				'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1))))),
        				
        		// with 1 address, 1 product, 3 shipping rule
        		array(	array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PRODUCT_1)),
        				array(PAYCART_TEST_PRODUCT_1 	=> array(	PAYCART_TEST_SHIPPING_RULE_1,
        															PAYCART_TEST_SHIPPING_RULE_2,
        															PAYCART_TEST_SHIPPING_RULE_3)),
        				array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PACKAGE_0 => array(
        																				'product_list' => array(PAYCART_TEST_PRODUCT_1), 
        																				'shippingrule_list' => array(	PAYCART_TEST_SHIPPING_RULE_1,
        																												PAYCART_TEST_SHIPPING_RULE_2,
        																												PAYCART_TEST_SHIPPING_RULE_3))))),
        				
        		// with 1 address, 3 product, 1 shipping rule
        		array(	array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PRODUCT_1,
        															PAYCART_TEST_PRODUCT_2,
        															PAYCART_TEST_PRODUCT_3)),
        				array(	PAYCART_TEST_PRODUCT_1 	=> array(	PAYCART_TEST_SHIPPING_RULE_1), 
        					 	PAYCART_TEST_PRODUCT_2 	=> array(	PAYCART_TEST_SHIPPING_RULE_1), 
        					 	PAYCART_TEST_PRODUCT_3 	=> array(	PAYCART_TEST_SHIPPING_RULE_1)),
        				array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PACKAGE_0 => array(
        																				'product_list' => array(	PAYCART_TEST_PRODUCT_1,
        																											PAYCART_TEST_PRODUCT_2,
        																											PAYCART_TEST_PRODUCT_3), 
        																				'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1))))),
        				
  				// with 1 address, 3 product, 3 shipping rule (un-common)
        		array(	array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PRODUCT_1,
        															PAYCART_TEST_PRODUCT_2,
        															PAYCART_TEST_PRODUCT_3)),
        				array(	PAYCART_TEST_PRODUCT_1 	=> array(	PAYCART_TEST_SHIPPING_RULE_1), 
        						PAYCART_TEST_PRODUCT_2 	=> array(	PAYCART_TEST_SHIPPING_RULE_2), 
        						PAYCART_TEST_PRODUCT_3 	=> array(	PAYCART_TEST_SHIPPING_RULE_3)),
        				array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PACKAGE_0 => array(
        																				'product_list' => array(PAYCART_TEST_PRODUCT_1), 
        																				'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1)),
        															PAYCART_TEST_PACKAGE_1 => array(
        																				'product_list' => array(PAYCART_TEST_PRODUCT_2), 
        																				'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_2)),
        									 						PAYCART_TEST_PACKAGE_2 => array(
        									 											'product_list' => array(PAYCART_TEST_PRODUCT_3), 
        									 											'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_3))))),
        								 
				// with 1 address, 3 product, 3 shipping rule (with 2 for each product)
        		array(	array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PRODUCT_1,
        															PAYCART_TEST_PRODUCT_2,
        															PAYCART_TEST_PRODUCT_3)),
        				array(	PAYCART_TEST_PRODUCT_1 	=> array(	PAYCART_TEST_SHIPPING_RULE_1,
        															PAYCART_TEST_SHIPPING_RULE_2), 
        					 	PAYCART_TEST_PRODUCT_2 	=> array(	PAYCART_TEST_SHIPPING_RULE_2,
        					 										PAYCART_TEST_SHIPPING_RULE_3), 
        					 	PAYCART_TEST_PRODUCT_3 	=> array(	PAYCART_TEST_SHIPPING_RULE_1,
        					 										PAYCART_TEST_SHIPPING_RULE_3)),
        				array(	PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PACKAGE_0 => array(
        																				'product_list' => array(PAYCART_TEST_PRODUCT_1), 
        																				'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1,
        																											 PAYCART_TEST_SHIPPING_RULE_2)),
        								 							PAYCART_TEST_PACKAGE_1 => array(
        								 												'product_list' => array(	PAYCART_TEST_PRODUCT_2,
        								 																			PAYCART_TEST_PRODUCT_3), 
        								 												'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_3))))),
        								         								 
   				// with 1 address, 3 product, 3 shipping rule (random)
        		array(	array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PRODUCT_1,
        															PAYCART_TEST_PRODUCT_2,
        															PAYCART_TEST_PRODUCT_3)),
        				array(	PAYCART_TEST_PRODUCT_1 	=> array(	PAYCART_TEST_SHIPPING_RULE_1, 
        															PAYCART_TEST_SHIPPING_RULE_2, 
        															PAYCART_TEST_SHIPPING_RULE_3), 
        						PAYCART_TEST_PRODUCT_2 => array(	PAYCART_TEST_SHIPPING_RULE_2,
        															PAYCART_TEST_SHIPPING_RULE_3), 
        						PAYCART_TEST_PRODUCT_3 => array(	PAYCART_TEST_SHIPPING_RULE_1,
        															PAYCART_TEST_SHIPPING_RULE_3)),
        				array(	PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PACKAGE_0 => array(
        																				'product_list' => array(	PAYCART_TEST_PRODUCT_1,
        																											PAYCART_TEST_PRODUCT_2,
        																											PAYCART_TEST_PRODUCT_3), 
        																				'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_3))))),  
        				
        		// with 1 address, 3 product, 3 shipping rule (random)
        		array(	array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PRODUCT_1,PAYCART_TEST_PRODUCT_2,PAYCART_TEST_PRODUCT_3)),
        				array(PAYCART_TEST_PRODUCT_1 	=> array(	PAYCART_TEST_SHIPPING_RULE_1), PAYCART_TEST_PRODUCT_2 => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3), PAYCART_TEST_PRODUCT_3 => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_3)),
        				array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_1), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1)),
        								 							PAYCART_TEST_PACKAGE_1 => array('product_list' => array(PAYCART_TEST_PRODUCT_2,PAYCART_TEST_PRODUCT_3), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_3))))),
        								 
        		// with 1 address, 3 product, 3 shipping rule (random)
        		array(	array(	PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PRODUCT_1,PAYCART_TEST_PRODUCT_2,PAYCART_TEST_PRODUCT_3)),
        				array(	PAYCART_TEST_PRODUCT_1 	=> array(	PAYCART_TEST_SHIPPING_RULE_1), 
        						PAYCART_TEST_PRODUCT_2 	=> array(	PAYCART_TEST_SHIPPING_RULE_2), 
        						PAYCART_TEST_PRODUCT_3 	=> array(	PAYCART_TEST_SHIPPING_RULE_1,
        															PAYCART_TEST_SHIPPING_RULE_3)),
        				array(	PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_1,PAYCART_TEST_PRODUCT_3), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1)),
        								 							PAYCART_TEST_PACKAGE_1 => array('product_list' => array(PAYCART_TEST_PRODUCT_2), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_2))))),
        								 
				// with 1 address, 3 product, 3 shipping rule (random)
        		array(	array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PRODUCT_1,PAYCART_TEST_PRODUCT_2,PAYCART_TEST_PRODUCT_3)),
        				array(PAYCART_TEST_PRODUCT_1 	=> array(	PAYCART_TEST_SHIPPING_RULE_1), PAYCART_TEST_PRODUCT_2 => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3), PAYCART_TEST_PRODUCT_3 => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3)),
        				array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_1), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1)),
        								 							PAYCART_TEST_PACKAGE_1 => array('product_list' => array(PAYCART_TEST_PRODUCT_2,PAYCART_TEST_PRODUCT_3), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3))))),

        		// with 3 address, 3 product, 1 shipping rule
        		array(	array(PAYCART_TEST_ADDRESS_1 => array(PAYCART_TEST_PRODUCT_1), PAYCART_TEST_ADDRESS_2 => array(PAYCART_TEST_PRODUCT_2), PAYCART_TEST_ADDRESS_3 => array(PAYCART_TEST_PRODUCT_3)),
        				array(PAYCART_TEST_PRODUCT_1 => array(PAYCART_TEST_SHIPPING_RULE_1), PAYCART_TEST_PRODUCT_2 => array(PAYCART_TEST_SHIPPING_RULE_1), PAYCART_TEST_PRODUCT_3 => array(PAYCART_TEST_SHIPPING_RULE_1)),
        				array(PAYCART_TEST_ADDRESS_1 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_1), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1))),
        					  PAYCART_TEST_ADDRESS_2 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_2), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1))),
        					  PAYCART_TEST_ADDRESS_3 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_3), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1))))),
        		
        		// with 3 address, 3 product, 3 shipping rule (each)
        		array(	array(	PAYCART_TEST_ADDRESS_1 => array(PAYCART_TEST_PRODUCT_1), 
        						PAYCART_TEST_ADDRESS_2 => array(PAYCART_TEST_PRODUCT_2), 
        						PAYCART_TEST_ADDRESS_3 => array(PAYCART_TEST_PRODUCT_3)),
        				array(PAYCART_TEST_PRODUCT_1 => array(PAYCART_TEST_SHIPPING_RULE_1), PAYCART_TEST_PRODUCT_2 => array(PAYCART_TEST_SHIPPING_RULE_2), PAYCART_TEST_PRODUCT_3 => array(PAYCART_TEST_SHIPPING_RULE_3)),
        				array(PAYCART_TEST_ADDRESS_1 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_1), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1))),
        					  PAYCART_TEST_ADDRESS_2 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_2), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_2))),
        					  PAYCART_TEST_ADDRESS_3 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_3), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_3))))),
        					  
			    // with 3 address, 3 product, 3 shipping rule (each)
        		array(	array(	PAYCART_TEST_ADDRESS_1 => array(PAYCART_TEST_PRODUCT_1), 
        						PAYCART_TEST_ADDRESS_2 => array(PAYCART_TEST_PRODUCT_2), 
        						PAYCART_TEST_ADDRESS_3 => array(PAYCART_TEST_PRODUCT_3)),
        				array(PAYCART_TEST_PRODUCT_1 => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_2), PAYCART_TEST_PRODUCT_2 => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3), PAYCART_TEST_PRODUCT_3 => array(PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_1)),
        				array(PAYCART_TEST_ADDRESS_1 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_1), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_2))),
        					  PAYCART_TEST_ADDRESS_2 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_2), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3))),
        					  PAYCART_TEST_ADDRESS_3 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_3), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_1))))),
        		
        		// with 2 address, 3 product, 3 shipping rule (each)
        		array(	array(	PAYCART_TEST_ADDRESS_1 => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2), 
        						PAYCART_TEST_ADDRESS_2 => array(PAYCART_TEST_PRODUCT_3)),
        				array(PAYCART_TEST_PRODUCT_1 => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_2), PAYCART_TEST_PRODUCT_2 => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3), PAYCART_TEST_PRODUCT_3 => array(PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_1)),
        				array(PAYCART_TEST_ADDRESS_1 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_1,PAYCART_TEST_PRODUCT_2), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_2))),
        					  PAYCART_TEST_ADDRESS_2 => array(PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_3), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_1))))),
        		
				// with 5 address, 10 product, 5 shipping rule
        		array(	array(	PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2), 
        						PAYCART_TEST_ADDRESS_2 	=> array(	PAYCART_TEST_PRODUCT_3,PAYCART_TEST_PRODUCT_4), 
        						PAYCART_TEST_ADDRESS_3 	=> array(	PAYCART_TEST_PRODUCT_5,PAYCART_TEST_PRODUCT_6), 
        						PAYCART_TEST_ADDRESS_4 	=> array(	PAYCART_TEST_PRODUCT_7,PAYCART_TEST_PRODUCT_8,PAYCART_TEST_PRODUCT_9), 
        						PAYCART_TEST_ADDRESS_5 	=> array(	PAYCART_TEST_PRODUCT_10)),
        				array(PAYCART_TEST_PRODUCT_1 	=> array(	PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_4), PAYCART_TEST_PRODUCT_2 => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_5), PAYCART_TEST_PRODUCT_3 => array(PAYCART_TEST_SHIPPING_RULE_1), PAYCART_TEST_PRODUCT_4 => array(PAYCART_TEST_SHIPPING_RULE_5,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_4,PAYCART_TEST_SHIPPING_RULE_2), PAYCART_TEST_PRODUCT_5 => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_5),
        					  PAYCART_TEST_PRODUCT_6 	=> array(	PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_4,PAYCART_TEST_SHIPPING_RULE_5,PAYCART_TEST_SHIPPING_RULE_1), PAYCART_TEST_PRODUCT_7 => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_5), PAYCART_TEST_PRODUCT_8 => array(PAYCART_TEST_SHIPPING_RULE_5), PAYCART_TEST_PRODUCT_9 => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_4), PAYCART_TEST_PRODUCT_10 => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_4)),
        				array(PAYCART_TEST_ADDRESS_1 	=> array(	PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_1,PAYCART_TEST_PRODUCT_2), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_3))),
        					  PAYCART_TEST_ADDRESS_2 	=> array(	PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_3), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1)),
        					  	   		 							PAYCART_TEST_PACKAGE_1 => array('product_list' => array(PAYCART_TEST_PRODUCT_4), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_5,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_4,PAYCART_TEST_SHIPPING_RULE_2))),
        					  PAYCART_TEST_ADDRESS_3 	=> array(	PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_5,PAYCART_TEST_PRODUCT_6), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_5))),
        					  PAYCART_TEST_ADDRESS_4 	=> array(	PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_7,PAYCART_TEST_PRODUCT_8), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_5)),
        					  	   		 							PAYCART_TEST_PACKAGE_1 => array('product_list' => array(PAYCART_TEST_PRODUCT_9), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_4))),
        					  PAYCART_TEST_ADDRESS_5 => array(		PAYCART_TEST_PACKAGE_0 => array('product_list' => array(PAYCART_TEST_PRODUCT_10), 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_4)))))
        					            
        );
    }
    
	 /**
     * @dataProvider providerTestGetPackageList
     */	
	public function testGetPackageList($product_grouped_by_address, $shippingrules_grouped_by_product, $packages) 
	{
		$helper = new PaycartHelperShippingRule();		
		$result = $helper->getPackageList($product_grouped_by_address, $shippingrules_grouped_by_product);
		$this->assertEquals($packages, $result, 'Package list does not match with actual result.');
	}	
	
	public static function providerTestSortAccordingToCounter()
	{
		return array(	
						// odd numer
						array( array(1=>1, 2=>1, 3=>1), array(3,2,1)),
						array( array(1=>2, 2=>1, 3=>2), array(3,1,2)),
						array( array(1=>2, 2=>1, 3=>1), array(1,3,2)),
						
						//even number
						array( array(1=>1, 2=>1, 3=>1, 4=>1), array(4,3,2,1)),
						array( array(1=>1, 2=>2, 3=>1, 4=>1), array(2,4,3,1)),
						array( array(1=>1, 2=>2, 3=>2, 4=>1), array(3,2,4,1)),
						array( array(1=>1, 2=>3, 3=>2, 4=>1), array(2,3,4,1))
					);
	}
	
	/**
     * @dataProvider providerTestSortAccordingToCounter
     */
	public function testSortAccordingToCounter($input, $output)
	{
		$helper = new PaycartHelperShippingRule();		
		$result = $helper->sortAccordingToCounter($input);
		$this->assertEquals($output, array_keys($result), 'Sorting mismatch');	
	}
	
	public static function providerTestGetBestRule()
	{
		for($counter = 0; $counter <= 2; $counter++){
    		foreach(array('PRODUCT', 'SHIPPING_RULE') as $entity){
    			if(!defined('PAYCART_TEST_'.$entity.'_'.$counter)){
    				define('PAYCART_TEST_'.$entity.'_'.$counter, $counter);
    			}  	
    		}
    	}
    	
    	
    	/**
    	 *  shipping rul list and it price 	: array(PAYCART_TEST_SHIPPING_RULE_1 => array('grade', 'without_tax', 'with_tax'), ...),
    	 *  output 							: array(best_shippingrule_in_price, best_shippingrule_in_grade, array(PAYCART_TEST_SHIPPING_RULE_1 =>array(without_tax, with_tax)) 
    	 */
		return array(
				// case 1 : 1Rule, with no tax
				array(	array(PAYCART_TEST_SHIPPING_RULE_1=> array(9, 10,10)),						
						array(PAYCART_TEST_SHIPPING_RULE_1, 
							  PAYCART_TEST_SHIPPING_RULE_1, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10)))
					),
					
				//case 2 :	2 rule, diff grade, diff price, no tax
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(8, 10, 10),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(9, 11, 11)),							  
						array(PAYCART_TEST_SHIPPING_RULE_1, 
							  PAYCART_TEST_SHIPPING_RULE_2, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 11,
																		'with_tax' 		=> 11)))
					),
					
				//case 3 :	2 rule, diff grade, diff price, no tax 
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(9, 10, 10),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(8, 11, 11)),							  
						array(PAYCART_TEST_SHIPPING_RULE_1, 
							  PAYCART_TEST_SHIPPING_RULE_1, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 11,
																		'with_tax' 		=> 11)))
					),

				//case 4 :	2 rule, diff grade, diff price, no tax 
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(8, 11, 11),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(9, 10, 10)),							  
						array(PAYCART_TEST_SHIPPING_RULE_2, 
							  PAYCART_TEST_SHIPPING_RULE_2, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 11,
																		'with_tax' 		=> 11),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10)))
					),
					
				//case 5 :	2 rule, diff grade, diff price, no tax 
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(9, 11, 11),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(8, 10, 10)),							  
						array(PAYCART_TEST_SHIPPING_RULE_2, 
							  PAYCART_TEST_SHIPPING_RULE_1, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 11,
																		'with_tax' 		=> 11),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10)))
					),
					
				//case 6 :	2 rule, same grade, diff price, no tax
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(9, 10, 10),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(9, 11, 11)),
						array(PAYCART_TEST_SHIPPING_RULE_1, 
							  PAYCART_TEST_SHIPPING_RULE_1, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 11,
																		'with_tax' 		=> 11)))
					),
					
				//case 7 :	2 rule, same grade, diff price, no tax : reverse of case 6
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(9, 11, 11),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(9, 10, 10)),
						array(PAYCART_TEST_SHIPPING_RULE_2, 
							  PAYCART_TEST_SHIPPING_RULE_1, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 11,
																		'with_tax' 		=> 11),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10)))
					),
					
				//case 8 :	2 rule, same grade, same price, no tax
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(9, 10, 10),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(9, 10, 10)),
						array(PAYCART_TEST_SHIPPING_RULE_1, 
							  PAYCART_TEST_SHIPPING_RULE_1, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10)))
					),
					
				//case 9 :	2 rule, diff grade, same price, no tax
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(8, 10, 10),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(9, 10, 10)),
						array(PAYCART_TEST_SHIPPING_RULE_1, 
							  PAYCART_TEST_SHIPPING_RULE_2, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10)))
					),
					
				//case 10 :	2 rule, diff grade, same price, no tax :: revese of case 7
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(9, 10, 10),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(8, 10, 10)),
						array(PAYCART_TEST_SHIPPING_RULE_1, 
							  PAYCART_TEST_SHIPPING_RULE_1, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10)))
					),
					
				//case 11 :	2 rule, diff grade, diff tax price, with tax 
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(8, 10, 11),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(9, 10, 10)),							  
						array(PAYCART_TEST_SHIPPING_RULE_2, 
							  PAYCART_TEST_SHIPPING_RULE_2, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 11),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 10)))
					),
					
				//case 12 :	2 rule, diff grade, diff price, no tax 
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(8, 10, 11),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(9, 10, 11)),							  
						array(PAYCART_TEST_SHIPPING_RULE_1, 
							  PAYCART_TEST_SHIPPING_RULE_2, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 11),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 11)))
					),
					
				//case 12 :	2 rule, diff grade, diff tax price, no tax 
				array(	array(PAYCART_TEST_SHIPPING_RULE_1 => array(9, 10, 12),
							  PAYCART_TEST_SHIPPING_RULE_2 => array(8, 10, 11)),							  
						array(PAYCART_TEST_SHIPPING_RULE_2, 
							  PAYCART_TEST_SHIPPING_RULE_1, 
							  array(PAYCART_TEST_SHIPPING_RULE_1 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 12),
							  		PAYCART_TEST_SHIPPING_RULE_2 => array(
																		'without_tax' 	=> 10,
																		'with_tax' 		=> 11)))
					),
				
		);
	}
	
	/**
     * @dataProvider providerTestGetBestRule
     */
	public function testGetBestRule($shipingrule_list, $output)
	{	
		$instances = array();
		foreach($shipingrule_list as $id => $price){
			$grade = array_shift($price);
			// Mock the object
			$mock = $this->getMock('PaycartShippingrule', array('getPackageShippingCost', 'getId', 'getGrade'), array(), '', false);
			
			// Set expectations and return values
			$mock->expects($this->once())
				 ->method('getId')
				 ->will($this->returnValue($id));
			
			// Set expectations and return values
			$mock->expects($this->once())
				 ->method('getPackageShippingCost')
				 ->with(array())
				 ->will($this->returnValue($price));
				 
			// Set expectations and return values
			$mock->expects($this->once())
				 ->method('getGrade')				 
				 ->will($this->returnValue($grade));
				 
			$instances[$id] = $mock;
		}
		
		// Replace protected self reference with mock object
		$ref = new ReflectionProperty('PaycartShippingrule', 'instance');
		$ref->setAccessible(true);
		$ref->setValue(null, array('paycartshippingrule' => $instances));		

		// Test
		$helper = new PaycartHelperShippingRule();		
		$this->assertEquals($output, $helper->getBestRule(array_keys($shipingrule_list), array()));  // second arg is not required for this case as it is used in another function in the called fucntion

		// clean up
		$ref->setAccessible(true);
		$ref->setValue(null, null);
	}
	
	public static function providerTestGetBestPriceDeliveryOption()
	{
		for($counter = 0; $counter <= 10; $counter++){
    		foreach(array('PRODUCT', 'ADDRESS', 'SHIPPING_RULE', 'PACKAGE') as $entity){
    			if(!defined('PAYCART_TEST_'.$entity.'_'.$counter)){
    				define('PAYCART_TEST_'.$entity.'_'.$counter, $counter);
    			}  	
    		}
    	}
    	
    	return array(
    			// case1 : 1 package => 1 product => 1 shipping rule
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1))),
    				array(),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_1),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 12))),
    				array(PAYCART_TEST_SHIPPING_RULE_1.',' 	=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 12,
    																					'price_without_tax'	=> 10,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1))),
																	 'is_best_price' => true,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => true))    			
    			),
    			
    			// case 2 : 2 packages => 2 product => 2 shipping rule // all are different    			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_2))),
    				array(),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_1,
    				      PAYCART_TEST_PACKAGE_2 			=> PAYCART_TEST_SHIPPING_RULE_2),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 12)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 20, 'with_tax' => 22))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_2.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 12,
    																					'price_without_tax'	=> 10,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1)),
    																			PAYCART_TEST_SHIPPING_RULE_2	=> 
    																			array(	'price_with_tax' 	=> 22,
    																					'price_without_tax'	=> 20,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => true,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => false))),

				// case 3 : 2 packages => 2 product => 1 shipping rule    			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1))),
    				array(),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_1,
    				      PAYCART_TEST_PACKAGE_2 			=> PAYCART_TEST_SHIPPING_RULE_1),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 12)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 20, 'with_tax' => 22))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_1.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 34,
    																					'price_without_tax'	=> 30,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => true,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => true))
    				),
    																			
				// case 4 : 2 packages => 2 product => 3 shipping rule => 1 common    			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_2)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_3))),
    				array(),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_1,
    				      PAYCART_TEST_PACKAGE_2 			=> PAYCART_TEST_SHIPPING_RULE_1),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 10),
    																 PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 20, 'with_tax' => 20)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 100, 'with_tax' => 100),
    					  											 PAYCART_TEST_SHIPPING_RULE_3 => array('without_tax' => 300, 'with_tax' => 300))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_1.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 110,
    																					'price_without_tax'	=> 110,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => true,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => true))    			
    			),
    			
    			// case 4 : 2 packages => 2 product => 3 shipping rule     			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_2)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_3))),
    				array(),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_2,
    				      PAYCART_TEST_PACKAGE_2 			=> PAYCART_TEST_SHIPPING_RULE_3),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 10),
    																 PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 20, 'with_tax' => 20)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 100, 'with_tax' => 100),
    					  											 PAYCART_TEST_SHIPPING_RULE_3 => array('without_tax' => 300, 'with_tax' => 300))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_2.','.PAYCART_TEST_SHIPPING_RULE_3.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_2	=> 
    																			array(	'price_with_tax' 	=> 20,
    																					'price_without_tax'	=> 20,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1)),
    																		  PAYCART_TEST_SHIPPING_RULE_3	=> 
    																			array(	'price_with_tax' 	=> 300,
    																					'price_without_tax'	=> 300,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => true,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => false))    			
    			),
    	);
	}
	
	/**
     * @dataProvider providerTestGetBestPriceDeliveryOption
     */
	public function testGetBestPriceDeliveryOption($packages, $deliveryOption, $best_price_shippingrules, $shippingrules_price, $output)
	{
		$helper = new PaycartHelperShippingRule();		
		$result = $helper->getBestPriceDeliveryOption($packages, $deliveryOption, $best_price_shippingrules, $shippingrules_price);
		$this->assertEquals($output, $result);				
	}	
	
	
	public static function providerTestGetBestGradeDeliveryOption()
	{
		for($counter = 0; $counter <= 10; $counter++){
    		foreach(array('PRODUCT', 'ADDRESS', 'SHIPPING_RULE', 'PACKAGE') as $entity){
    			if(!defined('PAYCART_TEST_'.$entity.'_'.$counter)){
    				define('PAYCART_TEST_'.$entity.'_'.$counter, $counter);
    			}  	
    		}
    	}
    	
    	return array(
    			// case1 : 1 package => 1 product => 1 shipping rule
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1))),
    				array(),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_1),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 12))),
    				array(PAYCART_TEST_SHIPPING_RULE_1.',' 	=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 12,
    																					'price_without_tax'	=> 10,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1))),
																	 'is_best_price' => false,
																	 'is_best_grade' => true,
																 	 'unique_shippingrule' => true))    			
    			),
    			
    			// case 2 : 2 packages => 2 product => 2 shipping rule // all are different    			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_2))),
    				array(),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_1,
    				      PAYCART_TEST_PACKAGE_2 			=> PAYCART_TEST_SHIPPING_RULE_2),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 12)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 20, 'with_tax' => 22))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_2.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 12,
    																					'price_without_tax'	=> 10,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1)),
    																			PAYCART_TEST_SHIPPING_RULE_2	=> 
    																			array(	'price_with_tax' 	=> 22,
    																					'price_without_tax'	=> 20,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => false,
																	 'is_best_grade' => true,
																 	 'unique_shippingrule' => false))),

				// case 3 : 2 packages => 2 product => 1 shipping rule    			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1))),
    				array(),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_1,
    				      PAYCART_TEST_PACKAGE_2 			=> PAYCART_TEST_SHIPPING_RULE_1),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 12)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 20, 'with_tax' => 22))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_1.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 34,
    																					'price_without_tax'	=> 30,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => false,
																	 'is_best_grade' => true,
																 	 'unique_shippingrule' => true))
    				),
    																			
				// case 4 : 2 packages => 2 product => 3 shipping rule => 1 common    			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_2)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_3))),
    				array(),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_1,
    				      PAYCART_TEST_PACKAGE_2 			=> PAYCART_TEST_SHIPPING_RULE_1),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 10),
    																 PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 20, 'with_tax' => 20)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 100, 'with_tax' => 100),
    					  											 PAYCART_TEST_SHIPPING_RULE_3 => array('without_tax' => 300, 'with_tax' => 300))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_1.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 110,
    																					'price_without_tax'	=> 110,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => false,
																	 'is_best_grade' => true,
																 	 'unique_shippingrule' => true))    			
    			),
    			
    			// case 4 : 2 packages => 2 product => 3 shipping rule     			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_2)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_3))),
    				array(),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_2,
    				      PAYCART_TEST_PACKAGE_2 			=> PAYCART_TEST_SHIPPING_RULE_3),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 10),
    																 PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 20, 'with_tax' => 20)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 100, 'with_tax' => 100),
    					  											 PAYCART_TEST_SHIPPING_RULE_3 => array('without_tax' => 300, 'with_tax' => 300))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_2.','.PAYCART_TEST_SHIPPING_RULE_3.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_2	=> 
    																			array(	'price_with_tax' 	=> 20,
    																					'price_without_tax'	=> 20,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1)),
    																		  PAYCART_TEST_SHIPPING_RULE_3	=> 
    																			array(	'price_with_tax' 	=> 300,
    																					'price_without_tax'	=> 300,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => false,
																	 'is_best_grade' => true,
																 	 'unique_shippingrule' => false))    			
    			),
    			
    			// case 5 : 2 packages => 2 product => 3 shipping rule // alreadt set in delivery ption     			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_2)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_3))),
    				array(PAYCART_TEST_SHIPPING_RULE_2.','.PAYCART_TEST_SHIPPING_RULE_3.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_2	=> 
    																			array(	'price_with_tax' 	=> 20,
    																					'price_without_tax'	=> 20,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1)),
    																		  PAYCART_TEST_SHIPPING_RULE_3	=> 
    																			array(	'price_with_tax' 	=> 300,
    																					'price_without_tax'	=> 300,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => true,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => false)),
    				array(PAYCART_TEST_PACKAGE_1 			=> PAYCART_TEST_SHIPPING_RULE_2,
    				      PAYCART_TEST_PACKAGE_2 			=> PAYCART_TEST_SHIPPING_RULE_3),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 10),
    																 PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 20, 'with_tax' => 20)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 100, 'with_tax' => 100),
    					  											 PAYCART_TEST_SHIPPING_RULE_3 => array('without_tax' => 300, 'with_tax' => 300))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_2.','.PAYCART_TEST_SHIPPING_RULE_3.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_2	=> 
    																			array(	'price_with_tax' 	=> 20,
    																					'price_without_tax'	=> 20,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1)),
    																		  PAYCART_TEST_SHIPPING_RULE_3	=> 
    																			array(	'price_with_tax' 	=> 300,
    																					'price_without_tax'	=> 300,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => true,
																	 'is_best_grade' => true,
																 	 'unique_shippingrule' => false))    			
    			),
    	);
	}
	
	/**
     * @dataProvider providerTestGetBestGradeDeliveryOption
     */
	public function testGetBestGradeDeliveryOption($packages, $deliveryOption, $best_price_shippingrules, $shippingrules_price, $output)
	{
		$helper = new PaycartHelperShippingRule();		
		$result = $helper->getBestGradeDeliveryOption($packages, $deliveryOption, $best_price_shippingrules, $shippingrules_price);
		$this->assertEquals($output, $result);				
	}
	
	public static function providerTestGetUniqueDeliveryOption()
	{
		for($counter = 0; $counter <= 10; $counter++){
    		foreach(array('PRODUCT', 'ADDRESS', 'SHIPPING_RULE', 'PACKAGE') as $entity){
    			if(!defined('PAYCART_TEST_'.$entity.'_'.$counter)){
    				define('PAYCART_TEST_'.$entity.'_'.$counter, $counter);
    			}  	
    		}
    	}
    	
    	return array(    			
    			// case 1     			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_2)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_3))),
    				array(),
    				array(PAYCART_TEST_SHIPPING_RULE_1),    				      
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 10),
    																 PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 20, 'with_tax' => 20)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 100, 'with_tax' => 100),
    					  											 PAYCART_TEST_SHIPPING_RULE_3 => array('without_tax' => 300, 'with_tax' => 300))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_1.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 110,
    																					'price_without_tax'	=> 110,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => false,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => true))    			
    			),
    			
    			// case 2 : 2 common shipping rule     			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_2)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_2))),
    				array(),
    				array(PAYCART_TEST_SHIPPING_RULE_2, PAYCART_TEST_SHIPPING_RULE_1),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 10),
    																 PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 20, 'with_tax' => 20)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 100, 'with_tax' => 100),
    					  											 PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 200, 'with_tax' => 200))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_2.','.PAYCART_TEST_SHIPPING_RULE_2.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_2	=> 
    																			array(	'price_with_tax' 	=> 220,
    																					'price_without_tax'	=> 220,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => false,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => true),
						  PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_1.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 110,
    																					'price_without_tax'	=> 110,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => false,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => true))
    			),
    			
    			// case 2 : 2 common shipping rule     			
    			array(
    				array(PAYCART_TEST_PACKAGE_1 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_1), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_2)),
    					  PAYCART_TEST_PACKAGE_2 			=> array('product_list' 	 => array(PAYCART_TEST_PRODUCT_2), 
    																 'shippingrule_list' => array(PAYCART_TEST_SHIPPING_RULE_1, PAYCART_TEST_SHIPPING_RULE_2))),
    				array(PAYCART_TEST_SHIPPING_RULE_2.','.PAYCART_TEST_SHIPPING_RULE_2.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_2	=> 
    																			array(	'price_with_tax' 	=> 220,
    																					'price_without_tax'	=> 220,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => true,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => false),
						  PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_1.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 110,
    																					'price_without_tax'	=> 110,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => false,
																	 'is_best_grade' => true,
																 	 'unique_shippingrule' => false)),
    				array(PAYCART_TEST_SHIPPING_RULE_2, PAYCART_TEST_SHIPPING_RULE_1),
    				array(PAYCART_TEST_PACKAGE_1			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 10, 'with_tax' => 10),
    																 PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 20, 'with_tax' => 20)),
    					  PAYCART_TEST_PACKAGE_2			=> array(PAYCART_TEST_SHIPPING_RULE_1 => array('without_tax' => 100, 'with_tax' => 100),
    					  											 PAYCART_TEST_SHIPPING_RULE_2 => array('without_tax' => 200, 'with_tax' => 200))),
    					  
    				array(PAYCART_TEST_SHIPPING_RULE_2.','.PAYCART_TEST_SHIPPING_RULE_2.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_2	=> 
    																			array(	'price_with_tax' 	=> 220,
    																					'price_without_tax'	=> 220,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => true,
																	 'is_best_grade' => false,
																 	 'unique_shippingrule' => true),
						  PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_1.',' 	
    														=> array('shippingrule_list' => 
    																	array(PAYCART_TEST_SHIPPING_RULE_1	=> 
    																			array(	'price_with_tax' 	=> 110,
    																					'price_without_tax'	=> 110,
    																					'package_list' => array(PAYCART_TEST_PACKAGE_1, PAYCART_TEST_PACKAGE_2),
    																					'product_list' => array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
																	 'is_best_price' => false,
																	 'is_best_grade' => true,
																 	 'unique_shippingrule' => true))
    			)
    	);
	}
	
	/**
     * @dataProvider providerTestGetUniqueDeliveryOption
     */
	public function testGetUniqueDeliveryOption($packages, $deliveryOption, $common_shippingrules, $shippingrules_price, $output)
	{
		$helper = new PaycartHelperShippingRule();		
		$result = $helper->getUniqueDeliveryOption($packages, $deliveryOption, $common_shippingrules, $shippingrules_price);
		$this->assertEquals($output, $result);				
	}	
	
	public static function providerTestSortDeliveryOptionList()
	{
		return array(
					// case1 : both are equal, order by : price, ordern in : ASC
					array(
						array('total_price_with_tax' => 10, 'ordering' => 1), 
						array('total_price_with_tax' => 10, 'ordering' => 1),
						array('shippingrule_list_order_by' => Paycart::SHIPPINGRULE_LIST_ORDER_BY_PRICE, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_ASC),
						-1),	
						
					// case2 : both are equal, order by : price, ordern in : DESC
					array(
						array('total_price_with_tax' => 10, 'ordering' => 1), 
						array('total_price_with_tax' => 10, 'ordering' => 1),
						array('shippingrule_list_order_by' => Paycart::SHIPPINGRULE_LIST_ORDER_BY_PRICE, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_DESC),
						1),
						
					// case3 : both are equal, order by : ordering, ordern in : ASC
					array(
						array('total_price_with_tax' => 10, 'ordering' => 1), 
						array('total_price_with_tax' => 10, 'ordering' => 1),
						array('shippingrule_list_order_by' => Paycart::SHIPPINGRULE_LIST_ORDER_BY_ORDERING, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_ASC),
						-1),
						
					// case4 : both are equal, order by : ordering, ordern in : DESC
					array(
						array('total_price_with_tax' => 10, 'ordering' => 1), 
						array('total_price_with_tax' => 10, 'ordering' => 1),
						array('shippingrule_list_order_by' => Paycart::SHIPPINGRULE_LIST_ORDER_BY_ORDERING, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_DESC),
						1),
						
					// case5 : option 1 is less in price, ordering is same, order by : price, order in : ASC
					array(
						array('total_price_with_tax' => 9, 'ordering' => 1), 
						array('total_price_with_tax' => 10, 'ordering' => 1),
						array('shippingrule_list_order_by' => Paycart::SHIPPINGRULE_LIST_ORDER_BY_PRICE, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_ASC),
						-1),

					// case6 : option 1 is less in price, ordering is same, order by : price, order in : DESC
					array(
						array('total_price_with_tax' => 9, 'ordering' => 1), 
						array('total_price_with_tax' => 10, 'ordering' => 1),
						array('shippingrule_list_order_by' => Paycart::SHIPPINGRULE_LIST_ORDER_BY_PRICE, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_DESC),
						1),

					// case7 : same price, option 2 is lower in ordering, order by : price, order in : ASC
					array(
						array('total_price_with_tax' => 10, 'ordering' => 2), 
						array('total_price_with_tax' => 10, 'ordering' => 1),
						array('shippingrule_list_order_by' => Paycart::SHIPPINGRULE_LIST_ORDER_BY_ORDERING, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_ASC),
						1),

					// case8 : option 1 is less in price, ordering is same, order by : price, order in : DESC
					array(
						array('total_price_with_tax' => 10, 'ordering' => 2), 
						array('total_price_with_tax' => 10, 'ordering' => 1),
						array('shippingrule_list_order_by' => Paycart::SHIPPINGRULE_LIST_ORDER_BY_ORDERING, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_DESC),
						-1)
					);
	}
	
	/**
     * @dataProvider providerTestSortDeliveryOptionList
     */
	public function testSortDeliveryOptionList($option1, $option2, $config, $output)
	{			
		// Replace protected self reference with mock object
		$ref = new ReflectionProperty('PaycartFactory', '_config');
		$ref->setAccessible(true);
		$ref->setValue(null, (object)$config);
		
		$helper = new PaycartHelperShippingRule();		
		$result = $helper->sortDeliveryOptionList($option1, $option2);
		$this->assertEquals($output, $result);

		// clean up
		$ref->setAccessible(true);
		$ref->setValue(null, null);
	}

    public static function providerTestGetDeliveryOptionList()
    {
    	// define constants if not defined
    	for($counter = 0; $counter <= 10; $counter++){
    		foreach(array('PRODUCT', 'ADDRESS', 'SHIPPING_RULE', 'PACKAGE') as $entity){
    			if(!defined('PAYCART_TEST_'.$entity.'_'.$counter)){
    				define('PAYCART_TEST_'.$entity.'_'.$counter, $counter);
    			}
    		}
    	}  	
    	
    	
    	return array(
    			// case : 1 address, 1 product, 1 shipping rule
    			array(
    				array(PAYCART_TEST_ADDRESS_1 		=> array(PAYCART_TEST_PRODUCT_1)),
        			array(PAYCART_TEST_PRODUCT_1 		=> array(PAYCART_TEST_SHIPPING_RULE_1)),
    				array(PAYCART_TEST_SHIPPING_RULE_1	=> array(9, 1, 10, 10)),
    				array('shippingrule_list_order_by'  => Paycart::SHIPPINGRULE_LIST_ORDER_BY_PRICE, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_ASC),
    				array(PAYCART_TEST_ADDRESS_1		=> array( PAYCART_TEST_SHIPPING_RULE_1.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_1 => array(
    																														'price_with_tax'	=> 10,
    																														'price_without_tax' => 10,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => 1,
    																						'is_best_grade' => 1,
    																						'unique_shippingrule' => 1,
    																						'total_price_with_tax' => 10,
    																						'total_price_without_tax' => 10,
    																						'ordering' => 1)))    				
    			),
    			
    			// case : 1 address, 1 product, 3 shipping rule , sor by pice in ASC order
    			array(
    				array(PAYCART_TEST_ADDRESS_1 		=> array(PAYCART_TEST_PRODUCT_1)),
        			array(PAYCART_TEST_PRODUCT_1 		=> array(PAYCART_TEST_SHIPPING_RULE_1,
        														PAYCART_TEST_SHIPPING_RULE_2,
        														PAYCART_TEST_SHIPPING_RULE_3)),
    				array(PAYCART_TEST_SHIPPING_RULE_1	=> array(7, 1, 10, 20),
    					  PAYCART_TEST_SHIPPING_RULE_2	=> array(8, 2, 20, 30),
    					  PAYCART_TEST_SHIPPING_RULE_3	=> array(9, 3, 30, 40)),
    				array('shippingrule_list_order_by'  => Paycart::SHIPPINGRULE_LIST_ORDER_BY_PRICE, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_ASC),
    				array(PAYCART_TEST_ADDRESS_1		=> array( PAYCART_TEST_SHIPPING_RULE_1.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_1 => array(
    																														'price_with_tax'	=> 20,
    																														'price_without_tax' => 10,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => true,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 20,
    																						'total_price_without_tax' => 10,
    																						'ordering' => 1),
    																								
    																PAYCART_TEST_SHIPPING_RULE_2.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_2 => array(
    																														'price_with_tax'	=> 30,
    																														'price_without_tax' => 20,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => false,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 30,
    																						'total_price_without_tax' => 20,
    																						'ordering' => 2),

    															 	 PAYCART_TEST_SHIPPING_RULE_3.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_3 => array(
    																														'price_with_tax'	=> 40,
    																														'price_without_tax' => 30,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => false,
    																						'is_best_grade' => true,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 40,
    																						'total_price_without_tax' => 30,
    																						'ordering' => 3)))		
    			),
    			
    			// case : 1 address, 1 product, 3 shipping rule , sor by pice in DESC order
    			array(
    				array(PAYCART_TEST_ADDRESS_1 		=> array(PAYCART_TEST_PRODUCT_1)),
        			array(PAYCART_TEST_PRODUCT_1 		=> array(PAYCART_TEST_SHIPPING_RULE_1,
        														PAYCART_TEST_SHIPPING_RULE_2,
        														PAYCART_TEST_SHIPPING_RULE_3)),
    				array(PAYCART_TEST_SHIPPING_RULE_1	=> array(7, 1, 10, 20),
    					  PAYCART_TEST_SHIPPING_RULE_2	=> array(8, 2, 20, 30),
    					  PAYCART_TEST_SHIPPING_RULE_3	=> array(9, 3, 30, 40)),
    				array('shippingrule_list_order_by'  => Paycart::SHIPPINGRULE_LIST_ORDER_BY_PRICE, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_DESC),
    				array(PAYCART_TEST_ADDRESS_1		=> array( PAYCART_TEST_SHIPPING_RULE_3.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_3 => array(
    																														'price_with_tax'	=> 40,
    																														'price_without_tax' => 30,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => false,
    																						'is_best_grade' => true,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 40,
    																						'total_price_without_tax' => 30,
    																						'ordering' => 3),
    																								
    																PAYCART_TEST_SHIPPING_RULE_2.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_2 => array(
    																														'price_with_tax'	=> 30,
    																														'price_without_tax' => 20,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => false,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 30,
    																						'total_price_without_tax' => 20,
    																						'ordering' => 2),
    															 	 	
    																PAYCART_TEST_SHIPPING_RULE_1.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_1 => array(
    																														'price_with_tax'	=> 20,
    																														'price_without_tax' => 10,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => true,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 20,
    																						'total_price_without_tax' => 10,
    																						'ordering' => 1)))						
    			),
    			
    			// case : 1 address, 1 product, 3 shipping rule , sor by ordering in ASC order
    			array(
    				array(PAYCART_TEST_ADDRESS_1 		=> array(PAYCART_TEST_PRODUCT_1)),
        			array(PAYCART_TEST_PRODUCT_1 		=> array(PAYCART_TEST_SHIPPING_RULE_1,
        														PAYCART_TEST_SHIPPING_RULE_2,
        														PAYCART_TEST_SHIPPING_RULE_3)),
    				array(PAYCART_TEST_SHIPPING_RULE_1	=> array(7, 3, 10, 20),
    					  PAYCART_TEST_SHIPPING_RULE_2	=> array(8, 1, 20, 30),
    					  PAYCART_TEST_SHIPPING_RULE_3	=> array(9, 2, 30, 40)),
    				array('shippingrule_list_order_by'  => Paycart::SHIPPINGRULE_LIST_ORDER_BY_ORDERING, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_ASC),
    				array(PAYCART_TEST_ADDRESS_1		=> array( PAYCART_TEST_SHIPPING_RULE_2.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_2 => array(
    																														'price_with_tax'	=> 30,
    																														'price_without_tax' => 20,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => false,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 30,
    																						'total_price_without_tax' => 20,
    																						'ordering' => 1),
    																								
    																PAYCART_TEST_SHIPPING_RULE_3.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_3 => array(
    																														'price_with_tax'	=> 40,
    																														'price_without_tax' => 30,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => false,
    																						'is_best_grade' => true,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 40,
    																						'total_price_without_tax' => 30,
    																						'ordering' => 2),
    															 	 	
    																PAYCART_TEST_SHIPPING_RULE_1.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_1 => array(
    																														'price_with_tax'	=> 20,
    																														'price_without_tax' => 10,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => true,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 20,
    																						'total_price_without_tax' => 10,
    																						'ordering' => 3)))						
    			),
    			
    			
    			// case : 1 address, 1 product, 3 shipping rule , sor by ordering in DESC order
    			array(
    				array(PAYCART_TEST_ADDRESS_1 		=> array(PAYCART_TEST_PRODUCT_1)),
        			array(PAYCART_TEST_PRODUCT_1 		=> array(PAYCART_TEST_SHIPPING_RULE_1,
        														PAYCART_TEST_SHIPPING_RULE_2,
        														PAYCART_TEST_SHIPPING_RULE_3)),
    				array(PAYCART_TEST_SHIPPING_RULE_1	=> array(7, 3, 10, 20),
    					  PAYCART_TEST_SHIPPING_RULE_2	=> array(8, 1, 20, 30),
    					  PAYCART_TEST_SHIPPING_RULE_3	=> array(9, 2, 30, 40)),
    				array('shippingrule_list_order_by'  => Paycart::SHIPPINGRULE_LIST_ORDER_BY_ORDERING, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_DESC),
    				array(PAYCART_TEST_ADDRESS_1		=> array( PAYCART_TEST_SHIPPING_RULE_1.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_1 => array(
    																														'price_with_tax'	=> 20,
    																														'price_without_tax' => 10,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => true,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 20,
    																						'total_price_without_tax' => 10,
    																						'ordering' => 3),
    																								
    																PAYCART_TEST_SHIPPING_RULE_3.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_3 => array(
    																														'price_with_tax'	=> 40,
    																														'price_without_tax' => 30,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => false,
    																						'is_best_grade' => true,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 40,
    																						'total_price_without_tax' => 30,
    																						'ordering' => 2),
    																								
    															 	PAYCART_TEST_SHIPPING_RULE_2.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_2 => array(
    																														'price_with_tax'	=> 30,
    																														'price_without_tax' => 20,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1))),
    																						'is_best_price' => false,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 30,
    																						'total_price_without_tax' => 20,
    																						'ordering' => 1)))						
    			),
    			
    			// case : 1 address, 1 product, 3 shipping rule , sor by ordering in DESC order
    			array(
    				array(PAYCART_TEST_ADDRESS_1 		=> array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2), 
        				  PAYCART_TEST_ADDRESS_2 		=> array(PAYCART_TEST_PRODUCT_3,PAYCART_TEST_PRODUCT_4), 
        				  PAYCART_TEST_ADDRESS_3 		=> array(PAYCART_TEST_PRODUCT_5,PAYCART_TEST_PRODUCT_6), 
        				  PAYCART_TEST_ADDRESS_4 		=> array(PAYCART_TEST_PRODUCT_7,PAYCART_TEST_PRODUCT_8,PAYCART_TEST_PRODUCT_9), 
        				  PAYCART_TEST_ADDRESS_5 		=> array(PAYCART_TEST_PRODUCT_10)),
        			array(PAYCART_TEST_PRODUCT_1 		=> array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_4), PAYCART_TEST_PRODUCT_2 => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_5), PAYCART_TEST_PRODUCT_3 => array(PAYCART_TEST_SHIPPING_RULE_1), PAYCART_TEST_PRODUCT_4 => array(PAYCART_TEST_SHIPPING_RULE_5,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_4,PAYCART_TEST_SHIPPING_RULE_2), PAYCART_TEST_PRODUCT_5 => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_5),
        				  PAYCART_TEST_PRODUCT_6 		=> array(PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_4,PAYCART_TEST_SHIPPING_RULE_5,PAYCART_TEST_SHIPPING_RULE_1), PAYCART_TEST_PRODUCT_7 => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_5), PAYCART_TEST_PRODUCT_8 => array(PAYCART_TEST_SHIPPING_RULE_5), PAYCART_TEST_PRODUCT_9 => array(PAYCART_TEST_SHIPPING_RULE_1,PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_3,PAYCART_TEST_SHIPPING_RULE_4), PAYCART_TEST_PRODUCT_10 => array(PAYCART_TEST_SHIPPING_RULE_2,PAYCART_TEST_SHIPPING_RULE_4)),
       				array(PAYCART_TEST_SHIPPING_RULE_1	=> array(7, 3, 10, 20),
    					  PAYCART_TEST_SHIPPING_RULE_2	=> array(8, 5, 40, 50),
    					  PAYCART_TEST_SHIPPING_RULE_3	=> array(9, 2, 50, 60),
    					  PAYCART_TEST_SHIPPING_RULE_4	=> array(8, 1, 20, 30),
    					  PAYCART_TEST_SHIPPING_RULE_5	=> array(8, 4, 30, 40)),
    				array('shippingrule_list_order_by'  => Paycart::SHIPPINGRULE_LIST_ORDER_BY_PRICE, 'shippingrule_list_order_in' => Paycart::SHIPPINGRULE_LIST_ORDER_IN_ASC),
    				array(PAYCART_TEST_ADDRESS_1		=> array( PAYCART_TEST_SHIPPING_RULE_1.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_1 => array(
    																														'price_with_tax'	=> 20,
    																														'price_without_tax' => 10,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
    																						'is_best_price' => true,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 20,
    																						'total_price_without_tax' => 10,
    																						'ordering' => 3),
    																								
    																PAYCART_TEST_SHIPPING_RULE_3.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_3 => array(
    																														'price_with_tax'	=> 60,
    																														'price_without_tax' => 50,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_1, PAYCART_TEST_PRODUCT_2))),
    																						'is_best_price' => false,
    																						'is_best_grade' => true,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 60,
    																						'total_price_without_tax' => 50,
    																						'ordering' => 2)),
    						// (5,3,4,2) -> best in price 4 , best in grade -> 3																
    						PAYCART_TEST_ADDRESS_2		=> array(PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_4.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_1 => array(
    																														'price_with_tax'	=> 20,
    																														'price_without_tax' => 10,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_3)),
    																								PAYCART_TEST_SHIPPING_RULE_4 => array(
    																														'price_with_tax'	=> 30,
    																														'price_without_tax' => 20,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_1),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_4))),
    																						'is_best_price' => true,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => false,
    																						'total_price_with_tax' => 50,
    																						'total_price_without_tax' => 30,
    																						'ordering' => 2),
    																								
    																PAYCART_TEST_SHIPPING_RULE_1.','.PAYCART_TEST_SHIPPING_RULE_3.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_1 => array(
    																														'price_with_tax'	=> 20,
    																														'price_without_tax' => 10,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_3)),
    																								PAYCART_TEST_SHIPPING_RULE_3 => array(
    																														'price_with_tax'	=> 60,
    																														'price_without_tax' => 50,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_1),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_4))),
    																						'is_best_price' => false,
    																						'is_best_grade' => true,
    																						'unique_shippingrule' => false,
    																						'total_price_with_tax' => 80,
    																						'total_price_without_tax' => 60,
    																						'ordering' => 2.5)),
    							// (5) -> best in price 5 , best in grade -> 5, unique -> 5						
    							PAYCART_TEST_ADDRESS_3		=> array(PAYCART_TEST_SHIPPING_RULE_5.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_5 => array(
    																														'price_with_tax'	=> 40,
    																														'price_without_tax' => 30,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_5, PAYCART_TEST_PRODUCT_6))),
    																						'is_best_price' => true,
    																						'is_best_grade' => true,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 40,
    																						'total_price_without_tax' => 30,
    																						'ordering' => 4)),
    																								
    							// (5)(1,2,3,4) -> best in price 5,1 , best in grade -> 5,3,  unique -> 5,_						
    							PAYCART_TEST_ADDRESS_4		=> array(PAYCART_TEST_SHIPPING_RULE_5.','.PAYCART_TEST_SHIPPING_RULE_1.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_5 => array(
    																														'price_with_tax'	=> 40,
    																														'price_without_tax' => 30,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_7, PAYCART_TEST_PRODUCT_8)),
    																								PAYCART_TEST_SHIPPING_RULE_1 => array(
    																														'price_with_tax'	=> 20,
    																														'price_without_tax' => 10,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_1),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_9))),
    																						'is_best_price' => true,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => false,
    																						'total_price_with_tax' => 60,
    																						'total_price_without_tax' => 40,
    																						'ordering' => 3.5),
    																	PAYCART_TEST_SHIPPING_RULE_5.','.PAYCART_TEST_SHIPPING_RULE_3.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_5 => array(
    																														'price_with_tax'	=> 40,
    																														'price_without_tax' => 30,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_7, PAYCART_TEST_PRODUCT_8)),
    																								PAYCART_TEST_SHIPPING_RULE_3 => array(
    																														'price_with_tax'	=> 60,
    																														'price_without_tax' => 50,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_1),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_9))),
    																						'is_best_price' => false,
    																						'is_best_grade' => true,
    																						'unique_shippingrule' => false,
    																						'total_price_with_tax' => 100,
    																						'total_price_without_tax' => 80,
    																						'ordering' => 3)),
    								// (2,4) -> best in price 4 , best in grade -> 2						
    								PAYCART_TEST_ADDRESS_5		=> array(PAYCART_TEST_SHIPPING_RULE_4.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_4 => array(
    																														'price_with_tax'	=> 30,
    																														'price_without_tax' => 20,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_10))),
    																						'is_best_price' => true,
    																						'is_best_grade' => false,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 30,
    																						'total_price_without_tax' => 20,
    																						'ordering' => 1),
    																	 PAYCART_TEST_SHIPPING_RULE_2.',' => array(
    																						'shippingrule_list' => array(
    																								PAYCART_TEST_SHIPPING_RULE_2 => array(
    																														'price_with_tax'	=> 50,
    																														'price_without_tax' => 40,
    																														'package_list'		=> array(PAYCART_TEST_PACKAGE_0),
    																														'product_list' 		=> array(PAYCART_TEST_PRODUCT_10))),
    																						'is_best_price' => false,
    																						'is_best_grade' => true,
    																						'unique_shippingrule' => true,
    																						'total_price_with_tax' => 50,
    																						'total_price_without_tax' => 40,
    																						'ordering' => 5))),
    			)	
    	);
    
    }
    
	/**
     * @dataProvider providerTestGetDeliveryOptionList
     */
	public function testGetDeliveryOptionList($product_grouped_by_address, $shippingrules_grouped_by_product, $shipingrule_list, $config, $output)
	{	
		$instances = array();
		foreach($shipingrule_list as $id => $price){
			$grade = array_shift($price);
			// Mock the object
			$mock = $this->getMock('PaycartShippingrule', array('getPackageShippingCost', 'getId', 'getGrade', 'getOrdering'), array(), '', false);
			
			// Set expectations and return values
			$mock->expects($this->any())
				 ->method('getId')
				 ->will($this->returnValue($id));
			
			
			$ordering = array_shift($price);
			// Set expectations and return values
			$mock->expects($this->any())
				 ->method('getOrdering')				 
				 ->will($this->returnValue($ordering));
				 
			// Set expectations and return values
			$mock->expects($this->any())
				 ->method('getPackageShippingCost')				 
				 ->will($this->returnValue($price));
				 
			// Set expectations and return values
			$mock->expects($this->any())
				 ->method('getGrade')				 
				 ->will($this->returnValue($grade));
				 
			$instances[$id] = $mock;
		}
		
		// set config
		$ref = new ReflectionProperty('PaycartFactory', '_config');
		$ref->setAccessible(true);
		$ref->setValue(null, (object)$config);
		
		// Replace protected self reference with mock object
		$ref = new ReflectionProperty('PaycartShippingrule', 'instance');
		$ref->setAccessible(true);
		$ref->setValue(null, array('paycartshippingrule' => $instances));		
        
		$helper = new PaycartHelperShippingRule();		
		$result = $helper->getDeliveryOptionList($product_grouped_by_address, $shippingrules_grouped_by_product);

		// assert output
		$this->assertEquals($output, $result);
		
		// assert for sequence of shipping options
		foreach($output as $address_id => $rules){
			$this->assertEquals(array_keys($rules), array_keys($result[$address_id]), 'Ordering Mismatch'); 
		}
	}
	
}