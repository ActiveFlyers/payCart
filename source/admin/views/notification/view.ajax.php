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
 * Notification Ajax View
 * @author mMAnishTrivedi 
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminAjaxViewNotification extends PaycartAdminBaseViewNotification
{

   function getTemplate()
   {
   	    $notificationId = $this->input->get('notification_id',0,'INT');
   	    $notification	= PaycartNotification::getInstance($notificationId);
   		$ajax           = PaycartFactory::getAjaxResponse();
   		$response       = new stdClass();
   		$response->html     = Rb_HelperTemplate::renderLayout($notification->getEventName().'_notification', $displayData, PAYCART_LAYOUTS_PATH);
   		$response->iframeId = $this->input->get('iframeId','','STRING');
   		$ajax->addScriptCall('paycart.admin.notification.fetchTemplateSuccess',$response);
   		$ajax->sendResponse();
   }
	
}
