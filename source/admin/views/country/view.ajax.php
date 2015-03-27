<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Country Ajax View
 * @author mManishTrivedi
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminAjaxViewCountry extends PaycartAdminBaseViewCountry
{
	/**
	 * Initialize import and open popup
	 */
	public function initImport()
	{
		$countries = PaycartFactory::getHelper('country')->getCountryList(); 
		$existingCountries = PaycartFactory::getModel('country')->loadRecords(array(),array('limit','where'),false,'country_id');

		$this->assign('existingCountries',array_keys($existingCountries));
		$this->assign('countries',$countries);
		$html = $this->loadTemplate('import');
		
		$this->_setAjaxWinHeight('auto');
		$this->_setAjaxWinBody($html); 
		$this->_setAjaxWinTitle(JText::_('COM_PAYCART_ADMIN_COUNTRY_IMPORT_TITLE'));
		$this->_addAjaxWinAction(JText::_('COM_PAYCART_ADMIN_COUNTRY_TOOLBAR_IMPORT'), "paycart.admin.country.doImport()", 'btn btn-success');
		$this->_setAjaxWinAction();
		
		$ajax = Rb_Factory::getAjaxResponse();
		$ajax->sendResponse();
	}
}