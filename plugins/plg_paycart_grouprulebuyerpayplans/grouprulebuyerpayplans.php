<?php

/**
* @copyright        Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PayartGrouprule.Buyer
* @subpackage       BuyerPayplans
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
*
*/
class PlgPaycartGroupruleBuyerpayplans extends RB_Plugin
{
	function __construct(& $subject, $config = array())
	{
    	parent::__construct($subject, $config);

        //if payplans is not enabled/installed then do not load the plugin.
    	if(!Rb_HelperJoomla::getPluginStatus("payplans")){
    		return true;
    	}
    	
		 $helper = PaycartFactory::getHelper('group');		                      
         $helper->push(Paycart::GROUPRULE_TYPE_BUYER, 'PaycartGroupruleBuyerPayplans',
						array(
							'filepath' 	=> dirname(__FILE__).'/rules/buyerpayplans/buyerpayplans.php',
							'type'		=> paycart::GROUPRULE_TYPE_BUYER,
							'title' 	=> 'PLG_PAYCART_GROUPRULEBUYERPAYPLANS_TITLE',
							'description'=> 'PLG_PAYCART_GROUPRULEBUYERPAYPLANS_DESC',
							'icon' 		=> ''
                      ));
	}
}
