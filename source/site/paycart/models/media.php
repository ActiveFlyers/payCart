<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Media Model
 * @author rimjhim
 *
 */
class PaycartModelMedia extends PaycartModel
{
	function getConfig($languageCode, $mediaId)
	{
		$query = new Rb_Query();
		
		return $query->select('*')
		 		     ->from('#__paycart_media as m')
		 		     ->join('INNER', '#__paycart_media_lang as ml ON m.media_id = ml.media_id')
		 		     ->where('ml.lang_code = '.$languageCode)
		 		     ->where('ml.media_id = '.$mediaId)
		 		     ->dbLoadQuery()
		 		     ->loadRow();
	}
}

/**
 * 
 * Media language Model
 * @author rimjhim
 *
 */
class PaycartModelMediaLang extends PaycartModel{}
