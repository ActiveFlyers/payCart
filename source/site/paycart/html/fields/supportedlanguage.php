<?php

/**
* @copyright	Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		Paycart
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JFormHelper::loadFieldClass('list');
class PaycartFormFieldSupportedLanguage extends JFormFieldList
{
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();		

		$supported_language = PaycartFactory::getPCSupportedLanguageCode();
		$languages = Rb_HelperJoomla::getLanguages();
		
		foreach ($supported_language as $language){
			$options[] = PaycartHtml::_('select.option', $language, $languages[$language]->name);
		}
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
