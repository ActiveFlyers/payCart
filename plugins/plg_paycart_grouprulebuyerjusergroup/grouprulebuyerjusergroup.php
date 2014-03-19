<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Buyer
* @subpackage       BuyerJusergroup
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @author Gaurav Jain
*/
class PlgPaycartGroupruleBuyerjusergroup extends RB_Plugin
{
	function __construct(& $subject, $config = array())
	{
    	parent::__construct($subject, $config);

		$helper = PaycartFactory::getHelper('group');		                      
         $helper->push(Paycart::GROUPRULE_TYPE_BUYER, 'PaycartGroupruleBuyerJusergroup',
						array(
							'filepath' 	=> dirname(__FILE__).'/rules/buyerjusergroup/buyerjusergroup.php',
							'type'		=> paycart::GROUPRULE_TYPE_BUYER,
							'title' 	=> Rb_Text::_('PLG_PAYCART_GROUPRULEBUYERJUSERGROUP_TITLE'),
							'description'=> Rb_Text::_('PLG_PAYCART_GROUPRULEBUYERJUSERGROUP_DESC'),
							'icon' 		=> ''
                      ));
	}
}
