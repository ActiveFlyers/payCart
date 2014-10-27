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
 * 
 * Processor for flat tax
 * @author rimjhim
 *
 */
class PlgPaycartTaxruleFlat extends RB_Plugin
{
	function __construct(& $subject, $config = array())
	{
		parent::__construct($subject, $config);
		
		$helper = PaycartFactory::getHelper('processor');
		
		// push processor of flatRate
		$helper->push(paycart::PROCESSOR_TYPE_TAXRULE,
					 'PaycartTaxruleProcessorFlatRate',		  
                      array(
                            'filepath'   => dirname(__FILE__).'/processors/flatrate/flatrate.php',
                            'title'      => Rb_Text::_('PLG_PAYCART_TAXRULE_FLAT_RATE_TITLE'),
                            'description'=> Rb_Text::_('PLG_PAYCART_TAXRULE_FLAT_RATE_DESC'),
                      		'icon'		 => ''
                      ));
        
        // push processor of flatAmount
        $helper->push(paycart::PROCESSOR_TYPE_TAXRULE,
					 'PaycartTaxruleProcessorFlatAmount',		  
                      array(
                            'filepath'   => dirname(__FILE__).'/processors/flatamount/flatamount.php',
                            'title'      => Rb_Text::_('PLG_PAYCART_TAXRULE_FLAT_AMOUNT_TITLE'),
                            'description'=> Rb_Text::_('PLG_PAYCART_TAXRULE_FLAT_AMOUNT_DESC'),
                      		'icon'		 => ''
                      ));
	}
}
