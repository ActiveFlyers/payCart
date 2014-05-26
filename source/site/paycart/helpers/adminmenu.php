<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	paycartHelper
 * @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Admin Menu Helper
 * @author Gaurav Jain
 */

class PaycartHelperAdminmenu extends PaycartHelper
{
	protected $_menus = array();
	public function __construct()
	{
		$adminMenus = array();

		$adminMenus['dashboard'] = Array(
										'title' => JText::_('COM_PAYCART_DASHBOARD'),
										'url' => 'index.php?option=com_paycart&view=dashboard',
										'class' => 'fa fa-home'
									);
					
		$adminMenus['catalogue'] =	Array(
										'title' => JText::_('COM_PAYCART_CATALOGUE'),
										'url' => '#',
										'class' => '',
										'children' => Array(
											Array(
												'title' => JText::_('COM_PAYCART_PRODUCTS'),
												'url' => 'index.php?option=com_paycart&view=product',
												'class' => ''
											),
											Array(
												'title' => JText::_('COM_PAYCART_CATEGORIES'),
												'url' => 'index.php?option=com_paycart&view=category',
												'class' => ''
											),
											Array(
												'title' => JText::_('COM_PAYCART_ATTRIBUTES'),
												'url' => 'index.php?option=com_paycart&view=attribute',
												'class' => ''
											)
										)
									);

		$adminMenus['buyer'] = Array(
									'title' => JText::_('COM_PAYCART_BUYER'),
									'url' => 'index.php?option=com_paycart&view=buyer',
									'class' => ''
								);		

		$adminMenus['sales'] = Array(
									'title' => JText::_('COM_PAYCART_SALES'),
									'url' => '#',
									'class' => '',
									'children' => Array(
										Array(
											'title' => JText::_('COM_PAYCART_CARTS'),
											'url' => 'index.php?option=com_paycart&view=cart',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_INVOICES'),
											'url' => 'index.php?option=com_paycart&view=invoice',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_PAYMENT_TRANSATIONS'),
											'url' => 'index.php?option=com_paycart&view=transaction',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_SHIPMENTS'),
											'url' => 'index.php?option=com_paycart&view=shipments',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_REPORT'),
											'url' => 'index.php?option=com_paycart&view=report',
											'class' => ''
										)
									)
								);	
					
		$adminMenus['settings'] = Array(
									'title' => JText::_('COM_PAYCART_SETTINGS'),
									'url' => '#',
									'class' => '',
									'children' => Array(
										Array(
											'title' => JText::_('COM_PAYCART_CONFIGURATION'),
											'url' => 'index.php?option=com_paycart&view=config',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_LOCALIZATION'),
											'url' => 'index.php?option=com_paycart&view=localization',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_GROUP_RULES'),
											'url' => 'index.php?option=com_paycart&view=group',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_TAX_RULES'),
											'url' => 'index.php?option=com_paycart&view=taxrule',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_DISCOUNT_RULES'),
											'url' => 'index.php?option=com_paycart&view=discountrule',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_SHIPPING_RULES'),
											'url' => 'index.php?option=com_paycart&view=shippingrule',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_PAYMENT_GATEWAYS'),
											'url' => 'index.php?option=com_paycart&view=gateways',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_COUNTRY'),
											'url' => 'index.php?option=com_paycart&view=country',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_STATE'),
											'url' => 'index.php?option=com_paycart&view=state',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_INTEGRATION_APPS'),
											'url' => 'index.php?option=com_paycart&view=integration',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_APPSTORE'),
											'url' => 'index.php?option=com_paycart&view=appstore',
											'class' => ''
										)
									)
								);
					
		$adminMenus['history'] = Array(
									'title' => JText::_('COM_PAYCART_HISTORY'),
									'url' => '#',
									'class' => '',
									'children' => Array(
										Array(
											'title' => JText::_('COM_PAYCART_LOGS'),
											'url' => 'index.php?option=com_paycart&view=log',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_ALERTS'),
											'url' => 'index.php?option=com_paycart&view=alert',
											'class' => ''
										),
										Array(
											'title' => JText::_('COM_PAYCART_CUSTOMER_NOTIFICATIONS'),
											'url' => 'index.php?option=com_paycart&view=notification',
											'class' => ''
										)
									)
								);
					
		$this->_menus = $adminMenus;		
	}
	
	public function getMenus()
	{
		return $this->_menus;
	}
	
	public function addMenu($menu, $parent = '')
	{
		if(empty($parent)){
			$this->_menus = array_merge($this->_menus, $menu);
			return true; 
		}
		
		if(!isset($this->_menus[$parent])){
			throw new Exception('COM_PAYCART_ADMIN_INVALID_MENU_PARENT');
		}
		
		$this->_menus[$parent]['children'][] = $menu;
		return true;
	}
	
	public function render($currentUrl, $path = PAYCART_PATH_ADMIN_LAYOUTS)
	{
		$displayData = new stdClass();
		$displayData->menus = $this->getMenus();
		$displayData->currentUrl = $currentUrl;
		
		return JLayoutHelper::render('paycart_admin_menu', $displayData, $path);		 
	}
}