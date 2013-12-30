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
 * @since       3.0
 * @author 		manish
 *
 */
class PlgPayCartPromotion extends Rb_Plugin
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
		
		//Rb_HelperLoader::addAutoLoadFile($fileName, $className);
		$fileLocation = _DIR_.'/processor/promotion.php';
		
		$processor = PaycartFactory::getHelper('processor');
		
		$processor->push( 	Paycart::PROCESSOR_TYPE_DISCOUNTRULE,
							'PaycartDiscountProcessorPromotion',
							Array( 'location'	 => $fileLocation,
								   'title'		 => Rb_Text::_('PLG_PAYCART_PROMOTION_DISCOUNTRULE_PROMOTION_NAME'),
								   'icon'		 => '',
								   'tooltip'	 =>	'',
								   'description' => Rb_Text::_('PLG_PAYCART_PROMOTION_DESC')
								));
	}
	
}
