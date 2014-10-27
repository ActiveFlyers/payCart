<?php

/**
* @copyright        Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Product
* @subpackage       Productcategory
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @author Rimjhim Jain
*/
class PlgPaycartGroupruleProductCategory extends RB_Plugin
{
	function __construct(& $subject, $config = array())
	{
    	parent::__construct($subject, $config);

		$helper = PaycartFactory::getHelper('group');		                      
        $helper->push(Paycart::GROUPRULE_TYPE_PRODUCT, 'PaycartGroupruleProductCategory',
						array(
							'filepath' 	=> dirname(__FILE__).'/rules/productcategory/productcategory.php',
							'type'		=> paycart::GROUPRULE_TYPE_PRODUCT,
							'title' 	=> 'PLG_PAYCART_GROUPRULE_PRODUCT_CATEGORY_TITLE',
							'description' => 'PLG_PAYCART_GROUPRULE_PRODUCT_CATEGORY_DESC',
							'icon' 		=> ''
                      ));
	}
}
