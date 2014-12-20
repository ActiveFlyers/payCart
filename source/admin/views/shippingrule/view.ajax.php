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
 * Admin Ajax View for Shipping Rules
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminAjaxViewShippingRule extends PaycartAdminBaseViewShippingRule
{	
	public function getProcessorConfig()
	{
		$rule_id        = $this->input->get('shippingrule_id',0);
		$data 			= array();
		$data['processor_classname']	=  $this->input->get('processor_classname', '');
		
		$rule = PaycartShippingrule::getInstance($rule_id)->bind($data);
		$html = $rule->getProcessorConfigHtml();
		$this->assign('processor_config_html', $html);
		$this->setTpl('processor_config');
		$this->_renderOptions = array('domObject'=>'pc-shippingrule-processorconfig','domProperty'=>'innerHTML');
		return true;
	}
}