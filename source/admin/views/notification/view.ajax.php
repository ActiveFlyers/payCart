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
class PaycartAdminViewNotification extends PaycartAdminBaseViewNotification
{

    public function edit($tpl = null)
    {
            $itemid	= $this->getModel()->getId();

            $notification = PaycartNotification::getInstance($itemid);

            $form =  $notification->getModelform()->getForm();

            $this->assign('form', $form );
            $this->assign('available_token_list', PaycartFactory::getHelper('token')->getTokenList() );

            return parent::edit($tpl);
    }
	
}
