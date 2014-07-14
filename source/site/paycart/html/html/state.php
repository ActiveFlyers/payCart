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



class PaycartHtmlState 
{	
	/**
	 * 
	 * Invoke to get Paycart State html
	 * @param $country_selector		:	State field depends on this country field
	 * @param $name					:	Field name
	 * @param $value				:	Field Value
	 * @param $attr					:	Field attribute
	 */
	public static function getList($name, $value, $idtag = false, $attr = Array(), $country_id = '')
	{
		$options = Array();
		
		if ($country_id) {
			$options = PaycartFactory::getModel('state')->loadRecords(Array('country_id'=> $country_id));			
		}
		
		$html	=	PaycartHtml::_('select.genericlist', $options, $name, $attr, 'state_id', 'title', $value, $idtag);
		
		return $html;
	}
	
	
}