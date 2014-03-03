<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Base Helper
 * @author Team Readybytes
 */
class PaycartHelper extends Rb_Helper
{

	/**
	 * Sluggifies the input string.
	 *
	 * @param string $string 		input string
	 * @param bool   $force_safe 	Do we have to enforce ASCII instead of UTF8 (default: false). 
	 * 
	 * @return string sluggified string
	 * PCTODO:: $forceSafe should be handle by configuration setting
	 * $forceSafe => {yes, no, global}
	 */
	public function sluggify($string, $forceSafe = false)
	{
		$string = JString::strtolower($string);
        $string = JString::str_ireplace(array('$',','), '', $string);

		if ($forceSafe) {
			$string = JFilterOutput::stringURLSafe($string);
		} else {
			// Handle by Joomla global configuration varible 'unicodeslugs'
			$string = JApplication::stringURLSafe($string);
		}

		return JString::trim($string);
	}
	
	/**
	 * 
	 * Provides a secure hash based on a seed
	 * 
	 * @param   string  $seed  Seed string.
	 *
	 * @return  string  A secure hash
	 */
	public function getHash($seed = '', $limit = 16) 
	{
		return JString::substr( JApplication::getHash( $seed . time() ), 0, $limit);
	}
	
	public static function formatAmount($amount)
	{
		// PCTODO: Ask decimals in configuration
		return number_format($amount, 2);
	}
}
