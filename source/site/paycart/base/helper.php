<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Base Helper
 * @author Team Readybytes
 */
class PaycartHelper extends Rb_Helper
{
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

}
