<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license                GNU/GPL, see LICENSE.php
* @package                 Joomla.Plugin
* @subpackage        Paycart
* @contact                support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @author Gaurav Jain
*/
class PlgPaycartGroupruleCartaddress extends RB_Plugin
{
	function __construct(& $subject, $config = array())
	{
    	parent::__construct($subject, $config);

		$helper = PaycartFactory::getHelper('group');
		$helper->push(Paycart::GROUPRULE_TYPE_CART, 'PaycartGroupruleCartaddress',
						array(
							'filepath' 	=> dirname(__FILE__).'/rules/cartaddress/cartaddress.php',
							'type'		=> paycart::GROUPRULE_TYPE_CART,
							'title' 	=> 'PLG_PAYCART_GROUPRULECARTADDRESS_TITLE',
							'description'=> 'PLG_PAYCART_GROUPRULECARTADDRESS_DESC',
							'icon' 		=> ''
                      ));
	}
}
