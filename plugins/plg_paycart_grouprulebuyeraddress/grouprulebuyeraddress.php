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
class PlgPaycartGroupruleBuyeraddress extends RB_Plugin
{
	function __construct(& $subject, $config = array())
	{
    	parent::__construct($subject, $config);

		$helper = PaycartFactory::getHelper('group');
		$helper->push('PaycartGroupruleBuyeraddress',
						array(
							'filepath' 	=> dirname(__FILE__).'/rules/buyeraddress/buyeraddress.php',
							'type'		=> paycart::GROUPRULE_TYPE_BUYER,
							'title' 	=> Rb_Text::_('PLG_PAYCART_GROUPRULEBUYERADDRESS_TITLE'),
							'description'=> Rb_Text::_('PLG_PAYCART_GROUPRULEBUYERADDRESS_DESC'),
							'icon' 		=> ''
                      ));
	}
}
