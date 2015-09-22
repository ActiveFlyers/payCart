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
 * Admin Html View for Group
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminHtmlViewGroup extends PaycartAdminBaseViewGroup
{	
	public function display($tpl=null) 
	{
		// Enqueue warning message if set up screen is not clean
		PaycartHelperSetupchecklist::setWarningMessage();
		
		$availableGroupRules = $this->_helper->getList();	
		$this->assign('availableGroupRules', $availableGroupRules);
		
		return parent::display($tpl);
	}
	
	protected function _adminGridToolbar()
	{
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
		//Rb_HelperToolbar::save2new('savenew'); //not needed, because grouptype is required to be selected manually, while creating new
		Rb_HelperToolbar::divider();
		Rb_HelperToolbar::cancel();
	}
	
	public function edit($tpl=null) 
	{
		$itemId			=  $this->getModel()->getId();
		$item			=  PaycartGroup::getInstance($itemId);
		$rulehtml 		= '';
		$ruleScripts  	= array();
		$ruleCounter 	= 0;
		
		if(!$itemId){
			$type = $this->input->get('type', '');
			if(empty($type)){
				throw new Exception('Group type argument missing');			
			}

			$item->bind(array('type' => $type));
		}
		else{
			$type 		= $item->getType();			
			$params 	= $item->getParams();			
			  
			foreach($params as $rule){
				$namePrefix = $this->_component->getNameSmall().'_'.$this->getName().'_form[params]['.$ruleCounter.']';
				
				// get instance of rule
				$groupRule = PaycartFactory::getGrouprule($type, $rule->ruleClass, (array)$rule);
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
				$this->assign('ruleClass',  $rule->ruleClass);
				$this->assign('ruleType',   $type);	
				
				$rulehtml .= $this->loadTemplate('rule_config');
				$ruleScripts = array_merge($ruleScripts, $scripts);	
				$ruleCounter++;
			}
		}

		$this->assign('ruleHtml', $rulehtml);
		$this->assign('ruleScripts', $ruleScripts);
		$this->assign('ruleCounter', $ruleCounter);
		
		$availableGroupRules = $this->_helper->getList();
		if(!isset($availableGroupRules[$type])){
			throw new Exception(JText::_('COM_PAYCART_ERROR_GROUP_TYPE_ARGUMENT_MISSING'), 404);			
		}
		
		$groupRules = $availableGroupRules[$type];		
		
		$this->assign('form',  $item->getModelform()->getForm($item));
		$this->assign('group_rules', $groupRules); 
		return parent::edit($tpl);
	}
}