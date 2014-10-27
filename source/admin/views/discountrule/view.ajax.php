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
 * Admin Ajax View for Discount Rules
 * 
 * @since 1.0.0
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewDiscountrule extends PaycartAdminBaseViewDiscountrule
{
	public function getProcessorConfig()
	{
		$discountrule_id = $this->getModel()->getId();
		$data 			= array();
		$data['processor_classname']	=  $this->input->get('processor_classname', '');
		
		$discountrule 		= PaycartDiscountrule::getInstance($discountrule_id)->bind($data);		
		$html = $discountrule->getProcessorConfigHtml();
		$this->assign('processor_config_html', $html);
		$this->setTpl('processor_config');
		$this->_renderOptions = array('domObject'=>'pc-discountrule-processorconfig','domProperty'=>'innerHTML');
		return true;
	}
}