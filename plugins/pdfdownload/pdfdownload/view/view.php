<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Pdf Export Base View
 * @author Garima agal
 */
class PaycartAdminBaseViewPdfdownload extends PaycartView
{
	public $_helper = null;
	 
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($anme = '')
	{
		return null;
	} 
	
	function _getTemplatePath($layout = 'default')
		{
			return array_merge(parent::_getTemplatePath($layout),array(dirname(dirname(__FILE__)).'/tmpl'));
		}
}
