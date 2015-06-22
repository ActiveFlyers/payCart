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
class PlgPaycartShippingruleFedex extends RB_Plugin
{
	function __construct(& $subject, $config = array())
	{
    	parent::__construct($subject, $config);

		$helper = PaycartFactory::getHelper('processor');
		$helper->push(paycart::PROCESSOR_TYPE_SHIPPINGRULE,
						'PaycartShippingruleProcessorFedex',
						array(
							'filepath' => dirname(__FILE__).'/processors/fedex/fedex.php',
							'title' => Rb_Text::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_TITLE'),
							'description'=> Rb_Text::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_DESC'),
							'icon' => ''
                      ));
        }
}
