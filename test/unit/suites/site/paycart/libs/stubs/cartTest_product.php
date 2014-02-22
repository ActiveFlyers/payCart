<?php

/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/

/**
 * 
 * Stub for PaycartFactoryTest 
 * @author mManishTrivedi
 *
 */
class CartTestProductStub extends PaycartProduct
{
	
	public function getPrice() 
	{
		switch($this->getId()){
			case  16:
				// product price does not change
				return 10;
			case  22:
				// changed product price 30 to 40
				return 40;
				
			default:
				return 0;
		}
	}
	
	public function getName()
	{
		return 'product';
	}
	
}
