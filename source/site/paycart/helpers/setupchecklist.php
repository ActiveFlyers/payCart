<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/** 
 * SetUp Checklist Helper
 * @since 1.0.10
 * @author Neelam Soni
 */
class PaycartHelperSetupchecklist extends PaycartHelper
{	
	/**
	 * getSetupRules
	 * @description Get the setup rules with their status and help message
	 * @param void
	 * @return $rules
	 */
	public function getSetupRules()
	{		
		// $rules should contains all the applied rules as 
		// associative arrays containing 'desc', 'setupStatus', 'helpMsg'
		$rules			= array();
		$plugin_rules	= array();
		
		// Get the list of core setup checklist rules
		$this->getCoreChecklistRules($rules);
		
		// Trigger for other plugins to bind their rules
		$event_helper = PaycartFactory::getHelper('event');
		$plugin_rules = $event_helper->onPaycartGetPluginChecklist($rules);
		
		// Return the rules
		return array_merge($rules, $plugin_rules);
	}
	
	/**
	 * getCoreChecklistRules
	 * @description Get the list of core setup checklist rules
	 * @param &$rules
	 * @return void
	 */
	public function getCoreChecklistRules(&$rules)
	{		
		// Create rule for shipping rule existence
		$rules[] = $this->checkShippingRule();
		
		// Create rule for payment gateway existence
		$rules[] = $this->checkPaymentGateway();
		
		// Create rule for country existence
		$rules[] = $this->checkCountry();
		
		// Create rule to notify to set Invoice Serial Prefix
		$rules[] = $this->notifyToSetPrefix();
		
		// Create rule to check Paycart menu items
		$rules[] = $this->checkPaycartHiddenMenuItems();
		
		// Create rule to notify to configure cron settings
		$rules[] = $this->notifyToConfigureCron();
	}
	
	/**
	 * checkShippingRule
	 * @description Check if at least one shipping rule exists or not (For tangible products)
	 * 				and prepare rule with desc, setupStatus, helpMsg
	 * @param void
	 * @return $rule
	 */
	public function checkShippingRule()
	{
		// If user has only digital products, no need to check for the existence of shipping rule
		// Check only for shippable products
		
		// Checking if any shippable product has been created or not and product is published or not
		$product_type	= Paycart::PRODUCT_TYPE_PHYSICAL;
		
		$query = new Rb_Query();
		$query->select('COUNT(*)')
					   ->from('#__paycart_product')
					   ->where('`type` = "'.$product_type.'"')
					   ->where('`published` = 1');
				
		$count = $query->dbLoadQuery()->loadResult();
		
		if(empty($count)){
			return;
		}
		
		$query->clear();
		$query->select('COUNT(*)')
					   ->from('#__paycart_shippingrule')
					   ->where('`published` = 1');
				
		$count = $query->dbLoadQuery()->loadResult();
		
		$setupStatus	= ($count) ? true : false;
		$fix 			= (!$setupStatus) ? JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_FIX_IT_NOW") : JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_QUICK_LINK");
		
		$rule			= array('desc' 		  => JText::_('COM_PAYCART_ADMIN_SETUP_CHECKLIST_SHIPPING_DESC'),
								'setupStatus' => $setupStatus,
								'helpMsg'     => JText::sprintf('COM_PAYCART_ADMIN_SETUP_CHECKLIST_SHIPPING_HELPMSG' , '<a href="index.php?option=com_paycart&view=shippingrule">'.$fix.' <i class="fa fa-share-square text-info"></i></a>')
							   );
		return $rule;
	}
	
	/**
	 * checkPaymentGateway
	 * @description Check if at least one payment gateway has been instantiated or 
	 *     			not	and prepare rule with desc, setupStatus, helpMsg
	 * @param void
	 * @return $rule
	 */
	public function checkPaymentGateway()
	{
		$query = new Rb_Query();
		$query->select('COUNT(*)')
					   ->from('#__paycart_paymentgateway')
					   ->where('`published` = 1');
				
		$count = $query->dbLoadQuery()->loadResult();
				
		$setupStatus	= ($count) ? true : false;
		$fix 			= (!$setupStatus) ? JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_FIX_IT_NOW") : JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_QUICK_LINK");
		
		$rule			= array('desc' 		  => JText::_('COM_PAYCART_ADMIN_SETUP_CHECKLIST_PAYMENT_GATEWAY_DESC'),
								'setupStatus' => $setupStatus,
								'helpMsg'     => JText::sprintf('COM_PAYCART_ADMIN_SETUP_CHECKLIST_PAYMENT_GATEWAY_HELPMSG' , '<a href="index.php?option=com_paycart&view=paymentgateway">'.$fix.' <i class="fa fa-share-square text-info"></i></a>')
							   );
		return $rule;
	}
	
	/**
	 * checkCountry
	 * @description Check if at least one country exists or not
	 * 				and prepare rule with desc, setupStatus, helpMsg
	 * @param void
	 * @return $rule
	 */
	public function checkCountry()
	{
		$query = new Rb_Query();
		$query->select('COUNT(*)')
					   ->from('#__paycart_country')
					   ->where('`published` = 1');
				
		$count = $query->dbLoadQuery()->loadResult();
		
		$setupStatus	= ($count) ? true : false;
		$fix 			= (!$setupStatus) ? JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_FIX_IT_NOW") : JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_QUICK_LINK");
		
		$rule			= array('desc' 		  => JText::_('COM_PAYCART_ADMIN_SETUP_CHECKLIST_COUNTRY_DESC'),
								'setupStatus' => $setupStatus,
								'helpMsg'     => JText::sprintf('COM_PAYCART_ADMIN_SETUP_CHECKLIST_COUNTRY_HELPMSG' , '<a href="index.php?option=com_paycart&view=country">'.$fix.' <i class="fa fa-share-square text-info"></i></a>')
							   );
		return $rule;
	}
	
	/**
	 * notifyToSetPrefix
	 * @description Notify to set the Invoice Serial Prefix
	 * 				and prepare rule with type, desc, setupStatus, helpMsg
	 * @param void
	 * @return $rule
	 */
	public function notifyToSetPrefix()
	{
		$serialFormat	 = PaycartFactory::getConfig()->get('invoice_serial_number_format');
		
		if(!empty($serialFormat)){
			$setupStatus = true;
			$desc 		 = JText::sprintf('COM_PAYCART_ADMIN_SETUP_CHECKLIST_INVOICE_SERIAL_DESC1', $serialFormat);
		}else{
			$setupStatus = false;
			$desc 		 = JText::_('COM_PAYCART_ADMIN_SETUP_CHECKLIST_INVOICE_SERIAL_DESC2');
		}
		
		$fix 			= (!$setupStatus) ? JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_FIX_IT_NOW") : JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_QUICK_LINK");
		$rule			= array('type'		  => 'info', 
								'desc' 		  => $desc,
								'setupStatus' => $setupStatus,
								'helpMsg'     => JText::sprintf('COM_PAYCART_ADMIN_SETUP_CHECKLIST_INVOICE_SERIAL_HELPMSG' , '<a href="index.php?option=com_paycart&view=config">'.$fix.' <i class="fa fa-share-square text-info"></i></a>')
							   );
		return $rule;
		
	}
	
	/**
	 * checkPaycartMenuItems
	 * @description Check whether the required menu items exists but not published
	 * 				and prepare rule with type, desc, setupStatus, helpMsg
	 * @param void
	 * @return $rule
	 */
	public function checkPaycartHiddenMenuItems()
	{
		$setupStatus 	= $this->checkMenuItems();
		$type			= ($setupStatus) ? 'info' : null;
		
		$fix 			= (!$setupStatus) ? JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_FIX_IT_NOW") : JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_QUICK_LINK");
		
		$rule			= array('type'		  => $type,
								'desc' 		  => JText::_('COM_PAYCART_ADMIN_SETUP_CHECKLIST_HIDDEN_MENUS_DESC'),
								'setupStatus' => $setupStatus,
								'helpMsg'     => JText::sprintf('COM_PAYCART_ADMIN_SETUP_CHECKLIST_HIDDEN_MENUS_HELPMSG' , '<a href="index.php?option=com_menus&view=items&menutype=paycart-hidden">'.$fix.' <i class="fa fa-share-square text-info"></i></a>')
							   );
		return $rule;
		
	}
	
	/**
	 * notifyToConfigureCron
	 * @description Notify to configure the cron settings
	 * 				and prepare rule with desc, setupStatus, helpMsg
	 * @param void
	 * @return $rule
	 */
	public function notifyToConfigureCron()
	{
		// Get the cron_access_time
		$config     	= PaycartFactory::getConfig();
		
		$cron_run_auto	= $config->get('cron_run_automatic');		
		$info			= ($cron_run_auto) ? null : JText::_('COM_PAYCART_ADMIN_SETUP_CHECKLIST_CRON_INFO');
		
		$dateFormat		= $config->get('localization_date_format');
		$date 			= new DateTime();
		$date->setTimestamp($config->get('cron_access_time'));
		$accessTime		= $date->format("{$dateFormat} H:i:s");
		
		// Calculate the status of cron run if it is configured at server
		$warning	= "";
		if((!$cron_run_auto))
		{
			if(strtotime($accessTime) >= (time() - 86400)){
				$status		= true;
				$warning	= "";
			}else{
				$status		= false;
				$warning	= JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_CRON_WARNING");
			}
		}	
				
		// Check whether cron is executing properly on time
		$setupStatus	= ($cron_run_auto) ? !(PaycartHelperCron::checkRequired()) : $status;
		
		$fix 			= (!$setupStatus) ? JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_FIX_IT_NOW") : JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_QUICK_LINK");
		
		$rule			= array('desc' 		  => $warning.JText::sprintf('COM_PAYCART_ADMIN_SETUP_CHECKLIST_CRON_DESC', $accessTime),
								'setupStatus' => $setupStatus,
								'helpMsg'     => JText::sprintf('COM_PAYCART_ADMIN_SETUP_CHECKLIST_CRON_HELPMSG' , '<a href="index.php?option=com_paycart&view=config">'.$fix.' <i class="fa fa-share-square text-info"></i></a>'),
								'info'		  => $info
							   );
		return $rule;
	}
	
	/**
	 * checkMenuItems
	 * @description Check whether paycart's hidden menu items exists but not published
	 * 
	 * @param void
	 * @return bool
	 */
	public function checkMenuItems()
	{
		// Storing Paycart's hidden menu's url in an array
		$menu_urls 	= array('index.php?option=com_paycart&view=product&task=display',
						    'index.php?option=com_paycart&view=cart&task=complete');
		
		// Check whether menus of above urls exist or not; store menu_ids if exist, else return false
		$db			= JFactory::getDbo();				
		$query 		= "SELECT `id` AS 'menu_id', `menutype` FROM `#__menu` WHERE `link` IN ('{$menu_urls[0]}', '{$menu_urls[1]}')";
		$db->setQuery($query);
		
		$menus		= $db->loadAssocList();
		
		if(empty($menus)){
			return false;
		}
		
		$menu_ids	= array();
		$menutypes	= array();
		
		foreach ($menus as $menu){
			$menu_ids[] = $menu['menu_id'];
			$menutypes[] = $menu['menutype'];
		}
		
		// Select "id" and "params" from #__modules where module is "mod_menu" if module is published
		$query		= "SELECT `id` AS 'module_id', `params` FROM `#__modules` WHERE `module` LIKE 'mod_menu' AND `published` = 1";
		$db->setQuery($query);
		
		$modules	= $db->loadAssocList();
		$module_ids = array();
		
		foreach ($modules as $module){
			$params		  = json_decode($module['params']);
			if(isset($params->menutype) && in_array($params->menutype, $menutypes))
				$module_ids[] = $module['module_id'];
		}
		
		// Check and return false if mapping exists for menu_id and module_id
		if(empty($module_ids)){
			return true;
		}
		
		$query			  = "SELECT moduleid FROM `#__modules_menu` WHERE `moduleid` IN (".implode(',',$module_ids).") AND `menuid` IN (0, ".implode(',',$menu_ids).")";
		$db->setQuery($query);
		
		$mapped_modules	  = $db->loadColumn();

		foreach ($modules as $module){
			if(in_array($module['module_id'], $mapped_modules)){
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * setWarningMessage
	 * @description Check whether the SetUp Screen is clean or not,
	 * 				display warning if not clean
	 * 
	 * @param void
	 * @return void
	 */
	public function setWarningMessage()
	{
		$show_warning	= PaycartFactory::getConfig()->get('show_set_up_checklist_warning');
		if($show_warning)
		{
			JFactory::getApplication()->enqueueMessage(JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_WARNING") , 'error');	
		}		
	}
}