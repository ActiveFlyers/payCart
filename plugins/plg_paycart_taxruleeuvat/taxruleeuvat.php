<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		Joomla.Plugin
* @subpackage	Paycart
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @author rimjhim
 */

class PlgPaycartTaxruleEuvat extends RB_Plugin
{
	function __construct(& $subject, $config = array())
	{
		parent::__construct($subject, $config);

		$helper = PaycartFactory::getHelper('processor');
		$helper->push(Paycart::PROCESSOR_TYPE_TAXRULE,
					 'PaycartTaxruleProcessorEuvat',
                          array(
                            'filepath'   => dirname(__FILE__).'/processors/euvat.php',
                            'title'      => Rb_Text::_('PLG_PAYCART_TAXRULE_EUVAT_TITLE'),
                            'description'=> Rb_Text::_('PLG_PAYCART_TAXRULE_EUVAT_DESC'),
                            'icon'		 => ''
                      ));
	}
}
