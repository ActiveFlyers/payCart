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
		return $this;
	}
}
