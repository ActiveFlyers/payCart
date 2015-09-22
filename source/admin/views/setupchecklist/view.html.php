<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Setup Checklist Html View
* @author Neelam Soni
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminHtmlViewSetupchecklist extends PaycartAdminBaseViewSetupchecklist
{	
	protected function _adminGridToolbar()
	{
		// no need any toolbar button
	}
	
	function display($tpl=null)
	{
		// Get the setup rules with their status and help message
		$helper	= PaycartFactory::getInstance('setupchecklist', 'helper');
		$rules  = $helper->getSetupRules();
		
		$success_rules = $info_rules = $error_rules = array();
		foreach ($rules as $rule) {
			if(array_key_exists('type', $rule) && isset($rule['type'])){
				$info_rules[]		= $rule;
			}
			else if($rule['setupStatus']) {
				$success_rules[]	= $rule;
			}
			else{
				$error_rules[]		= $rule;
			}
		}
		// Assign the setup-rules, status and help message to the from
		$this->assign('info_rules' , $info_rules);
		$this->assign('error_rules' , $error_rules);
		$this->assign('success_rules' , $success_rules);
		
		// Store or update the show_set_up_checklist_warning parameter in config table
		PaycartHelperCron::checkSetUpChecklist();
		
		// Set the template if its not default display
		return true;
	}
	
	public function _basicFormSetup($task)
	{
		//setup the action URL
		$url 	= 'index.php?option='.$this->_component->getNameCom().'&view='.$this->getName();
		$task	= Rb_Factory::getApplication()->input->get('task', $task);
		if($task){
			$url .= '&task='.$task;
		}
		
		$this->assign('uri', Rb_Route::_($url));
	}
}
