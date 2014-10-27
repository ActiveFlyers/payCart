<?php

/**
* @copyright            Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage           Back-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Paycart Notification Model
 */
class PaycartModelNotification extends PaycartModelLang
{}

/** 
 * Paycart Notification Model
 */
class PaycartModelFormNotification extends PaycartModelform
{}

class PaycartTableNotification extends PaycartTable
{}

class PaycartTableNotificationlang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_notification_lang', $tblPrimaryKey='notification_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}
