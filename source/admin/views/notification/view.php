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
 * Notification Base View
 * @author mManishTrivedi
 */
class PaycartAdminBaseViewNotification extends PaycartView
{
    protected function _adminGridToolbar()
    {
        Rb_HelperToolbar::publish();
		Rb_HelperToolbar::unpublish();
    }
	
}