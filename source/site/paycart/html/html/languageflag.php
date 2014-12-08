<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		mManishTrivedi 
*/

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

class PaycartHtmlLanguageflag 
{	
	public static function getFlag($lang_code, $withLabel = false, $client = 'site')
	{
		$languages = Rb_HelperJoomla::getLanguages($client); 
		$html = JHtml::_('image', 'mod_languages/' . strtolower(str_ireplace('-', '_', $lang_code)) . '.gif',$languages[$lang_code]->name, array('title' => $languages[$lang_code]->name), true);

		if($withLabel){
			return $html.' '.$languages[$lang_code]->name;
		}
		
		return $html;
	}	
}