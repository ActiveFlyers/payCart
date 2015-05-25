<?php

/**
* @copyright        Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Product
* @subpackage       Product
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @author Garima Agal
*/
class PlgPaycartGroupruleProduct extends RB_Plugin
{
	function __construct(& $subject, $config = array())
	{
    	parent::__construct($subject, $config);

		$helper = PaycartFactory::getHelper('group');		                      
        $helper->push(Paycart::GROUPRULE_TYPE_PRODUCT, 'PaycartGroupruleProduct',
						array(
							'filepath' 	=> dirname(__FILE__).'/rules/product/product.php',
							'type'		=> Paycart::GROUPRULE_TYPE_PRODUCT,
							'title' 	=> 'PLG_PAYCART_GROUPRULE_PRODUCT_TITLE',
							'description' => 'PLG_PAYCART_GROUPRULE_PRODUCT_DESC',
							'icon' 		=> ''
                      ));
	}
}
