<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Product
* @subpackage       ProductGroup
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Cart Total Group Rule
 *
 * @author Rimjhim Jain
 */
class PaycartGroupruleCartTotal extends PaycartGrouprule
{	
	public function isApplicable($cart_id)
	{
		$operator = $this->config->get('operator', '=');
		
		$total = PaycartCart::getInstance($cart_id)->getTotal();
		
		$amount = $this->config->get('amount', 0);
		$result = true;
		
		switch ($operator){
			case '=' : $result = (floatval($total) == floatval($amount))?true:false;
					   break;
			case '<' : $result = (floatval($total) < floatval($amount))?true:false;
					   break;
			case '>' : $result = (floatval($total) > floatval($amount))?true:false;
		}
		
		return $result;
	}
	
	/**
	 * Gets the html and js script call of parameteres 
	 * @return array() Array of Html and JavaScript functions to be called
	 */
	public function getConfigHtml($namePrefix = '')
	{
		$config 	= $this->config->toArray();
		
		ob_start();
		include dirname(__FILE__).'/tmpl/config.php';
		$contents = ob_get_contents();
		ob_end_clean();
		
		return array($contents, array());
	}
}
