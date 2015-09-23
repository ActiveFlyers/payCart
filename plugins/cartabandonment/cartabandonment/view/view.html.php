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
 * cartabandonment Html View
 * 
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminHtmlViewcartabandonment extends PaycartAdminBaseViewcartabandonment
{
	public function edit($tpl = null)
    {
            $itemid	= $this->getModel()->getId();

            $notification = PaycartCartabandonment::getInstance($itemid);
            
            //PCTODO: Handle this case properly - Ticket #764
            //There is an issue as we can add a new notification through sql file in english/default language only
            //Required to set current language code, if trying to edit any notification in other langauge 
            //and data is not available in that language
            $data = $notification->toArray();
            if($itemid && $data['lang_code'] != PaycartFactory::getPCCurrentLanguageCode()){
            	$data['lang_code'] = PaycartFactory::getPCCurrentLanguageCode();
            }

            Rb_Form::addFormPath(dirname(__FILE__));
			Rb_Form::addFieldPath(JPATH_COMPONENT . '/models/fields');
			
            $form =  $notification->getModelform()->getForm($data);

            $this->assign('form', $form );
            $this->assign('available_token_list', PaycartFactory::getHelper('token')->getTokenList() );

            return parent::edit($tpl);
    }
}
