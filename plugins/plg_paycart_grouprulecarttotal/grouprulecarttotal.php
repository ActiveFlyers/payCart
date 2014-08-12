<?php

/**
* @copyright        Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Cart
* @subpackage       Cartgroup
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @author Rimjhim Jain
*/
class PlgPaycartGroupruleCartTotal extends RB_Plugin
{
	function __construct(& $subject, $config = array())
	{
    	parent::__construct($subject, $config);

		$helper = PaycartFactory::getHelper('group');		                      
        $helper->push(Paycart::GROUPRULE_TYPE_CART, 'PaycartGroupruleCartTotal',
						array(
							'filepath' 	=> dirname(__FILE__).'/rules/carttotal/carttotal.php',
							'type'		=> paycart::GROUPRULE_TYPE_CART,
							'title' 	=> 'PLG_PAYCART_GROUPRULE_CART_TOTAL_TITLE',
							'description' => 'PLG_PAYCART_GROUPRULE_CART_TOTAL_DESC',
							'icon' 		=> ''
                      ));
	}
}
