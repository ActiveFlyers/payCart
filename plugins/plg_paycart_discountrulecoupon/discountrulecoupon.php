<?php
/**
 * @package     Paycart
 * @subpackage  Paycart.plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      mManishTrivedi 
 */

defined('_JEXEC') or die;

/**
 * PayCart! Promotion Plugin
 *
 * @package     Paycart
 * @subpackage  Paycart.plugin
 * @author 		manish
 *
 */
class PlgPaycartDiscountrulecoupon extends Rb_Plugin
{
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $subject
	 * @param unknown_type $config
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
		
		// processor file path
		$fileLocation 	= __FILE__.'/processor/coupon.php';
		
		$processor 		= PaycartFactory::getHelper('processor');
		// push into processor list
		$processor->push( Paycart::PROCESSOR_TYPE_DISCOUNTRULE,
						  'PaycartDiscountruleProcessorCoupon',
						  Array( 'filepath'	 	=> $fileLocation,
								 'title'		=> Rb_Text::_('PLG_PAYCART_PROCESSOR_DISCOUNTRULE_FLAT_NAME'),
								 'icon'		 	=> '',
								 'tooltip'	 	=>	'',
								 'description'	=> Rb_Text::_('PLG_PAYCART_PROCESSOR_DISCOUNTRULE_FLAT_DESC')
								));
	}

	//@PCTODO:: get html for checkout screen
	
}