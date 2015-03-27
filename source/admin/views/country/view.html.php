<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Country Html View
 * @author mManishTrivedi
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminHtmlViewCountry extends PaycartAdminBaseViewCountry
{	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::edit()
	 */
	public function edit($tpl=null) 
	{
		$country_id	=  $this->getModel()->getId();
		$filter = Array('country_id' => $country_id);

		$country_data = PaycartFactory::getModel('country')->loadRecords($filter);
		
		if(isset($country_data[$country_id])) {
			$country_data = (array) $country_data[$country_id];
		}
		
		//set default language if creating a new record
		if(!$country_id){
			$country_data['lang_code'] = PaycartFactory::getPCDefaultLanguageCode();
		}
	
		$this->assign('form',  PaycartFactory::getModelForm('country')->getForm($country_data, false));
		
		//get state data for specific country
		
		$filter 	=	Array('country_id' => $country_id );
		$states 	=	PaycartFactory::getModel('state')->loadRecords($filter);
		
		$this->assign('states', $states);
		
		return parent::edit($tpl);
	}
	
	public function _adminGridToolbar()
	{
		parent::_adminGridToolbar();
		
		Rb_HelperToolbar::divider();
		Rb_HelperToolbar::openPopup('initimport','import','',JText::_('COM_PAYCART_ADMIN_COUNTRY_TOOLBAR_IMPORT'));		
	}
}
