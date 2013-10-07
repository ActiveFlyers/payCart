<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 		PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 * @author 		Manish Trivedi
*/

/**
 * 
 * Product Helper Test
 * @author manish
 *
 */
class PaycartHelperProductTest extends PayCartTestCase
{	
	/**
	* @return array of availble product types.
	*/
	public function testGetTypes() 
	{
		$expectedTypes =  Array(
								Paycart::PRODUCT_TYPE_PHYSICAL		=>	'COM_PAYCART_PRODUCT_TYPE_PHYSICAL',
								Paycart::PRODUCT_TYPE_DIGITAL		=>	'COM_PAYCART_PRODUCT_TYPE_DIGITAL'	
							  );
							  
		$this->assertSame($expectedTypes, PaycartHelperProduct::getTypes());
	}	
}
