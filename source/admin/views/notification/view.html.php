<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Notification Html View
 * @author mMAnishTrivedi 
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminHtmlViewNotification extends PaycartAdminBaseViewNotification
{	
	function display($tpl=null)
	{
		// Enqueue warning message if set up screen is not clean
		PaycartHelperSetupchecklist::setWarningMessage();
		
		return parent::display($tpl);
	}
}
