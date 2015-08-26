<?php

/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Payplans
* @subpackage	AcyMailing
* @contact		shyam@joomlaxi.com
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Payplans AcyMailing Plugin
 *
 */
class plgPaycartAcymailing extends Rb_Plugin
{
	  public function onRbControllerCreation(&$option, &$view, &$controller, &$task, &$format)
		{
			//autoload model and table
			
	        $dir = dirname(__FILE__).'/acymailing';
			Rb_HelperLoader::addAutoLoadFile($dir.'/model.php', 'PaycartModelAcymailing');
			Rb_HelperLoader::addAutoLoadFile($dir.'/table.php', 'PaycartTableAcymailing');
			Rb_HelperLoader::addAutoLoadFile($dir.'/view/view.html.php', 'PaycartAdminHtmlViewAcymailing');
			Rb_HelperLoader::addAutoLoadFile($dir.'/view/view.json.php', 'PaycartAdminJsonViewAcymailing');
			
			Rb_HelperLoader::addAutoLoadFile($dir.'/controller.php', 'PaycartadminControllerAcymailing');
	        return true;
		}
		
		public function onPaycartViewBeforeRender(Rb_View $view, $task)
		{
			
			if(!JFolder::exists(JPATH_ADMINISTRATOR .'/components'.'/com_acymailing') || !Rb_Factory::getApplication()->isAdmin()){
							return JText::_('PLG_PAYCART_INSTALL_ACYMAILING_BEFORE_USING_THIS_APPLICATION');
						}
				
			$adminMenu =	Array(
												'title' => JText::_('COM_PAYCART_ADMIN_ACYMAILING'),
												'url' => 'index.php?option=com_paycart&view=acymailing',
												'class' => 'fa-tags'
											);
									
				$menu = PaycartFactory::getHelper('adminmenu');
				$menu->addMenu($adminMenu, 'Apps');
		}
		
		
		public function onPaycartCartAfterPaid($cart, $catId)
		{
			
				if(!require_once(rtrim(JPATH_ADMINISTRATOR,DS).'/components/com_acymailing/helpers/helper.php')){
							return JText::_('PLG_PAYCART_INSTALL_ACYMAILING_BEFORE_USING_THIS_APPLICATION');
				}
				$model    = PaycartFactory::getInstance('acymailing','model');
				$data     = $model->loadRecords(array('object_id'=>array(array('IN', '('.implode(",", $catId).')')),'type' =>'category'));	
				$groups   = array();
				foreach ($data as $value)
				{
					$group 		= json_decode($value->acymailing_groups);
					$groups		= array_merge($group, $groups);
				}
				$myUser 	   =  new stdClass();
				$buyer		   = $cart->getBuyer(true);
				
				$buyer_id      = $buyer->getId();
				$myUser->email =  $buyer->getEmail();
				
				$myUser->name = $buyer->getUsername();
				
				//get the instance of acymailer
				$subscriberClass = acymailing::get('class.subscriber');
				$subid = $subscriberClass->save($myUser);
				
				//we didn't find the user in the AcyMailing tables
				if(empty($subid)){
					return false;
				}
							
				$newSubscription = array();
				if(!empty($groups)){
					 foreach($groups as $listId){
				 		$newList = array();
				 		$newList['status'] = 1;
						 $newSubscription[$listId] = $newList;
					 }
				 }
				
				$subscriberClass->saveSubscription($subid,$newSubscription);
				$listClass = acymailing_get('class.list');
				$allLists = $listClass->getLists();
				return true;
		}
}
