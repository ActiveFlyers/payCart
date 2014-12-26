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
 * Admin Ajax View for Group
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminAjaxViewGroup extends PaycartAdminBaseViewGroup
{	
	public function addRule()
	{
		$ruleType 	= $this->input->get('ruleType', '');
		$ruleClass 	= $this->input->get('ruleClass', '');
		$counter	= $this->input->get('counter', '');
		if(empty($ruleType) || empty($ruleClass)){
			throw new InvalidArgumentException(Rb_Text::_('COM_PAYCART_ERROR_INVALID_ARGUMENT'), 1);  //@PCTODO : Decide Error Code
		}
		
		$namePrefix = $this->_component->getNameSmall().'_'.$this->getName().'_form[params]['.$counter.']';
		
		// get instance of rule
		$groupRule = PaycartFactory::getGrouprule($ruleType, $ruleClass, array());
		$result = $groupRule->getConfigHtml($namePrefix);
		
		$configHtml = '';
		$scripts 	= '';
		if(!is_array($result)){
			$configHtml = $result;
		}
		else{
			$configHtml = array_shift($result);
			// if is is still array
			if(is_array($result))
			$scripts = array_shift($result);
		} 
		
		$this->assign('configHtml', $configHtml);
		$this->assign('namePrefix', $namePrefix);
		$this->assign('ruleClass',  $ruleClass);
		$this->assign('ruleType',   $ruleType);

		$html = $this->loadTemplate('rule_config');
		
		$ajaxResponse = PaycartFactory::getAjaxResponse();
		// first ste is to add html in the document, in targeted DOM
		$ajaxResponse->addScriptCall('paycart.jQuery("#paycart-grouprule-config").append', $html);
		$ajaxResponse->addScriptCall('paycart.admin.group.ruleCounter='.($counter+1).';');
		
		$scripts = is_array($scripts) ? $scripts : array($scripts);
		foreach($scripts as $script){
			if(!empty($script)){
				$ajaxResponse->addScriptCall($script);
			}
		}	
		
		$ajaxResponse->sendResponse();
	}
}