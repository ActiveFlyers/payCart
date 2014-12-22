<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 */

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

if(RB_REQUEST_DOCUMENT_FORMAT === 'ajax'){
	class PaycartViewbase extends Rb_ViewAjax{}
}elseif(RB_REQUEST_DOCUMENT_FORMAT === 'json'){
	class PaycartViewbase extends Rb_ViewJson{}
}else{
	class PaycartViewbase extends Rb_ViewHtml{}
}


/** 
 * Base View
* @author Team Readybytes
 */
class PaycartView extends PaycartViewbase 
{
	public $_component = PAYCART_COMPONENT_NAME;
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		// intialize input
		$this->input = PaycartFactory::getApplication()->input;
		$this->assign('formatter', PaycartFactory::getHelper('format'));
		return $this;
	}
	
	protected function _adminGridToolbar()
	{
		Rb_HelperToolbar::addNew('new');
		Rb_HelperToolbar::editList();
		Rb_HelperToolbar::divider();
		Rb_HelperToolbar::publish();
		Rb_HelperToolbar::unpublish();
		Rb_HelperToolbar::divider();
		Rb_HelperToolbar::deleteList();
	}
	
	protected function _adminEditToolbar()
	{
		Rb_HelperToolbar::apply();
		Rb_HelperToolbar::save();
		Rb_HelperToolbar::save2new('savenew');
		Rb_HelperToolbar::divider();
		Rb_HelperToolbar::cancel();
	}
	
	public function getHelper($name = '')
	{
		if(empty($name)){
			$name = $this->getName();
		}
		
		return PaycartFactory::getHelper($name);
	}

	/**
	 * Overriding it to run cron on paycart pages only
	 * @see plugins/system/rbsl/rb/rb/view/Rb_ViewAjax::render()
	 */
	public function render($output, $options)
	{
		/*
		 * cron will be triggered only on paycart pages
		 */	
		//only add if required, then add call back
		if(PaycartFactory::getConfig()->get('cron_run_automatic') == 1 && 
		   PaycartFactory::getDocument()->getType() == 'html' && 
		   PaycartHelperCron::checkRequired()== true){
			// Add a cron call back	
			$output .= '<img src="'.PaycartHelperCron::getURL().'" style="display:none"/>';
		}
		
		parent::render($output, $options);
	}
}
