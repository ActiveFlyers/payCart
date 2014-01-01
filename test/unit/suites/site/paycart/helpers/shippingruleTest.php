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
    	
    	return array(
    			// with 1 address, 1 product, 1 shipping rule
        		array(	array(1 => array(1)),
        				array(1 => array(1)),
        				array(1 => array(0 => array('product_list' => array(1), 'shippingrule_list' => array(1))))),
        				
        		// with 1 address, 1 product, 3 shipping rule
        		array(	array(1 => array(1)),
        				array(1 => array(1,2,3)),
        				array(1 => array(0 => array('product_list' => array(1), 'shippingrule_list' => array(1,2,3))))),
        				
        		// with 1 address, 3 product, 1 shipping rule
        		array(	array(1 => array(1,2,3)),
        				array(1 => array(1), 2 => array(1), 3 => array(1)),
        				array(1 => array(0 => array('product_list' => array(1,2,3), 'shippingrule_list' => array(1))))),
        				
  				// with 1 address, 3 product, 3 shipping rule (un-common)
        		array(	array(1 => array(1,2,3)),
        				array(1 => array(1), 2 => array(2), 3 => array(3)),
        				array(1 => array(0 => array('product_list' => array(1), 'shippingrule_list' => array(1)),
        								 1 => array('product_list' => array(2), 'shippingrule_list' => array(2)),
        								 2 => array('product_list' => array(3), 'shippingrule_list' => array(3))))),
        								 
				// with 1 address, 3 product, 3 shipping rule (with 2 for each product)
        		array(	array(1 => array(1,2,3)),
        				array(1 => array(1,2), 2 => array(2,3), 3 => array(1,3)),
        				array(1 => array(0 => array('product_list' => array(1), 'shippingrule_list' => array(1,2)),
        								 1 => array('product_list' => array(2,3), 'shippingrule_list' => array(3))))),
        								         								 
   				// with 1 address, 3 product, 3 shipping rule (random)
        		array(	array(1 => array(1,2,3)),
        				array(1 => array(1, 2, 3), 2 => array(2,3), 3 => array(1,3)),
        				array(1 => array(0 => array('product_list' => array(1,2,3), 'shippingrule_list' => array(3))))),  
        				
        		// with 1 address, 3 product, 3 shipping rule (random)
        		// note : strange behaviour of arsort
        		array(	array(1 => array(1,2,3)),
        				array(1 => array(1), 2 => array(2,3), 3 => array(1,3)),
        				array(1 => array(0 => array('product_list' => array(1,3), 'shippingrule_list' => array(1)),
        								 1 => array('product_list' => array(2), 'shippingrule_list' => array(2,3))))),
        								 
        		// with 1 address, 3 product, 3 shipping rule (random)
        		array(	array(1 => array(1,2,3)),
        				array(1 => array(1), 2 => array(2), 3 => array(1,3)),
        				array(1 => array(0 => array('product_list' => array(1,3), 'shippingrule_list' => array(1)),
        								 1 => array('product_list' => array(2), 'shippingrule_list' => array(2))))),
        								 
				// with 1 address, 3 product, 3 shipping rule (random)
        		array(	array(1 => array(1,2,3)),
        				array(1 => array(1), 2 => array(2,3), 3 => array(2,3)),
        				array(1 => array(0 => array('product_list' => array(1), 'shippingrule_list' => array(1)),
        								 1 => array('product_list' => array(2,3), 'shippingrule_list' => array(2,3))))),

        		// with 3 address, 3 product, 1 shipping rule
        		array(	array(1 => array(1), 2 => array(2), 3 => array(3)),
        				array(1 => array(1), 2 => array(1), 3 => array(1)),
        				array(1 => array(0 => array('product_list' => array(1), 'shippingrule_list' => array(1))),
        					  2 => array(0 => array('product_list' => array(2), 'shippingrule_list' => array(1))),
        					  3 => array(0 => array('product_list' => array(3), 'shippingrule_list' => array(1))))),
        		
        		// with 3 address, 3 product, 3 shipping rule (each)
        		array(	array(1 => array(1), 2 => array(2), 3 => array(3)),
        				array(1 => array(1), 2 => array(2), 3 => array(3)),
        				array(1 => array(0 => array('product_list' => array(1), 'shippingrule_list' => array(1))),
        					  2 => array(0 => array('product_list' => array(2), 'shippingrule_list' => array(2))),
        					  3 => array(0 => array('product_list' => array(3), 'shippingrule_list' => array(3))))),
        					  
			    // with 3 address, 3 product, 3 shipping rule (each)
        		array(	array(1 => array(1), 2 => array(2), 3 => array(3)),
        				array(1 => array(1,2), 2 => array(2,3), 3 => array(3,1)),
        				array(1 => array(0 => array('product_list' => array(1), 'shippingrule_list' => array(1,2))),
        					  2 => array(0 => array('product_list' => array(2), 'shippingrule_list' => array(2,3))),
        					  3 => array(0 => array('product_list' => array(3), 'shippingrule_list' => array(3,1))))),
        		
        		// with 2 address, 3 product, 3 shipping rule (each)
        		array(	array(1 => array(1, 2), 2 => array(3)),
        				array(1 => array(1,2), 2 => array(2,3), 3 => array(3,1)),
        				array(1 => array(0 => array('product_list' => array(1,2), 'shippingrule_list' => array(2))),
        					  2 => array(0 => array('product_list' => array(3), 'shippingrule_list' => array(3,1))))),
        		
				// with 5 address, 10 product, 5 shipping rule
        		array(	array(1 => array(1, 2), 2 => array(3,4), 3 => array(5,6), 4 => array(7,8,9), 5 => array(10)),
        				array(1 => array(1,2,3,4), 2 => array(1,3,5), 3 => array(1), 4 => array(5,3,4,2), 5 => array(2,5),
        					  6 => array(3,4,5,1), 7 => array(1,3,5), 8 => array(5), 9 => array(1,2,3,4), 10 => array(2,4)),
        				array(1 => array(0 => array('product_list' => array(1,2), 'shippingrule_list' => array(1,3))),
        					  2 => array(0 => array('product_list' => array(3), 'shippingrule_list' => array(1)),
        					  	   		 1 => array('product_list' => array(4), 'shippingrule_list' => array(5,3,4,2))),
        					  3 => array(0 => array('product_list' => array(5,6), 'shippingrule_list' => array(5))),
        					  4 => array(0 => array('product_list' => array(7,9), 'shippingrule_list' => array(1,3)),
        					  	   		 1 => array('product_list' => array(8), 'shippingrule_list' => array(5))),
        					  5 => array(0 => array('product_list' => array(10), 'shippingrule_list' => array(2,4)))))
        					            
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
}
