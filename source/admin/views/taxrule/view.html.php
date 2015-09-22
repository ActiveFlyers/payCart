<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Admin Html View for Tax Rules
 * 
 * @since 1.0.0
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminHtmlViewTaxrule extends PaycartAdminBaseViewTaxrule
{
	public function edit($tpl = null)
	{
		$itemid	  	= $this->getModel()->getId();
		$taxrule 	= PaycartTaxrule::getInstance($itemid);
		$form 	  	= $taxrule->getModelform()->getForm($taxrule);
		
		$this->assign('form', $form );
		
		return parent::edit($tpl);
	}
	
	function display($tpl=null)
	{
		// Enqueue warning message if set up screen is not clean
		PaycartHelperSetupchecklist::setWarningMessage();
		
		return parent::display($tpl);
	}
}