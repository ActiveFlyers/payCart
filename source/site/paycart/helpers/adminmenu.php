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

//		$adminMenus['dashboard'] = Array(
//										'title' => JText::_('COM_PAYCART_ADMIN_DASHBOARD'),
//										'url' => 'index.php?option=com_paycart&view=dashboard',
//										'class' => 'fa-home'
//									);
					
		$adminMenus['catalogue'] =	Array(
										'title' => JText::_('COM_PAYCART_ADMIN_CATALOGUE'),
										'url' => '#',
										'class' => 'fa-book',
										'children' => Array(
											Array(
												'title' => JText::_('COM_PAYCART_ADMIN_PRODUCTS'),
												'url' => 'index.php?option=com_paycart&view=product',
												'class' => 'fa-tags'
											),
											Array(
												'title' => JText::_('COM_PAYCART_ADMIN_CATEGORIES'),
												'url' => 'index.php?option=com_paycart&view=productcategory',
												'class' => 'fa-sitemap'
											)
//											Array(
//												'title' => JText::_('COM_PAYCART_ADMIN_ATTRIBUTES'),
//												'url' => 'index.php?option=com_paycart&view=productattribute',
//												'class' => 'fa-bars'
//											)
										)
									);

		$adminMenus['buyer'] = Array(
									'title' => JText::_('COM_PAYCART_ADMIN_BUYER'),
									'url' => 'index.php?option=com_paycart&view=buyer',
									'class' => 'fa-user'
								);		

		$adminMenus['sales'] = Array(
									'title' => JText::_('COM_PAYCART_ADMIN_SALES'),
									'url' => '#',
									'class' => 'fa-bank',
									'children' => Array(
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_CARTS'),
											'url' => 'index.php?option=com_paycart&view=cart',
											'class' => 'fa-shopping-cart'
										),
//										Array(
//											'title' => JText::_('COM_PAYCART_ADMIN_INVOICES'),
//											'url' => 'index.php?option=com_paycart&view=invoice',
//											'class' => 'fa-inbox'
//										),
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_TRANSATIONS'),
											'url' => 'index.php?option=com_paycart&view=transaction',
											'class' => 'fa-money'
										),
//										Array(
//											'title' => JText::_('COM_PAYCART_ADMIN_SHIPMENTS'),
//											'url' => 'index.php?option=com_paycart&view=shipments',
//											'class' => 'fa-plane'
//										),
//										Array(
//											'title' => JText::_('COM_PAYCART_ADMIN_REPORTS'),
//											'url' => 'index.php?option=com_paycart&view=report',
//											'class' => 'fa-tasks'
//										)
									)
								);	
					
		$adminMenus['settings'] = Array(
									'title' => JText::_('COM_PAYCART_ADMIN_SETTINGS'),
									'url' => '#',
									'class' => 'fa-wrench',
									'children' => Array(
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_SETUP_CHECKLIST'),
											'url' => 'index.php?option=com_paycart&view=setupchecklist',
											'class' => 'fa-check-square-o'
										),
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_CONFIGURATION'),
											'url' => 'index.php?option=com_paycart&view=config',
											'class' => 'fa-gear'
										),
//										Array(
//											'title' => JText::_('COM_PAYCART_ADMIN_LOCALIZATION'),
//											'url' => 'index.php?option=com_paycart&view=localization',
//											'class' => 'fa-globe'
//										),
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_GROUP_RULES'),
											'url' => 'index.php?option=com_paycart&view=group',
											'class' => 'fa-group'
										),
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_TAX_RULES'),
											'url' => 'index.php?option=com_paycart&view=taxrule',
											'class' => 'fa-tax'
										),
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_DISCOUNT_RULES'),
											'url' => 'index.php?option=com_paycart&view=discountrule',
											'class' => 'fa-cut'
										),
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_SHIPPING_RULES'),
											'url' => 'index.php?option=com_paycart&view=shippingrule',
											'class' => 'fa-plane'
										),
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_PAYMENT_GATEWAYS'),
											'url' => 'index.php?option=com_paycart&view=paymentgateway',
											'class' => 'fa-credit-card'
										),
										Array(
											'title' => JText::_('COM_PAYCART_ADMIN_COUNTRIES'),
											'url' => 'index.php?option=com_paycart&view=country',
											'class' => 'fa-flag'
										),
                                                                            
                                     	Array(
											'title' => JText::_('COM_PAYCART_ADMIN_CUSTOMER_NOTIFICATIONS'),
											'url' => 'index.php?option=com_paycart&view=notification',
											'class' => 'fa-volume-up'
                                                                                 ),
//										Array(
//											'title' => JText::_('COM_PAYCART_ADMIN_INTEGRATION_APPS'),
//											'url' => 'index.php?option=com_paycart&view=integration',
//											'class' => 'fa-cubes'
//										)
									)
								);

		$adminMenus['appstore'] = Array(
									'title' => JText::_('COM_PAYCART_ADMIN_APPSTORE'),
									'url' => 'index.php?option=com_paycart&view=appstore',
									'class' => 'fa-th'
								);
		
		$adminMenus['Apps'] =	Array(
									'title' => JText::_('COM_PAYCART_ADMIN_APPS'),
									'url' => '#',
									'class' => 'fa-newspaper-o'
								);
//		$adminMenus['history'] = Array(
//									'title' => JText::_('COM_PAYCART_ADMIN_HISTORY'),
//									'url' => '#',
//									'class' => 'fa-history',
//									'children' => Array(
//										Array(
//											'title' => JText::_('COM_PAYCART_ADMIN_LOGS'),
//											'url' => 'index.php?option=com_paycart&view=log',
//											'class' => 'fa-file-text'
//										),
//										Array(
//											'title' => JText::_('COM_PAYCART_ADMIN_ADMIN_ALERTS'),
//											'url' => 'index.php?option=com_paycart&view=alert',
//											'class' => 'fa-warning'
//										),
//										Array(
//											'title' => JText::_('COM_PAYCART_ADMIN_CUSTOMER_NOTIFICATIONS'),
//											'url' => 'index.php?option=com_paycart&view=notification',
//											'class' => 'fa-volume-up'
//										)
//									)
//								);
					
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
	
	public function render($currentUrl)
	{
		$displayData = new stdClass();
		$displayData->menus = $this->getMenus();
		$displayData->currentUrl = $currentUrl;
		
		return Rb_HelperTemplate::renderLayout('paycart_admin_menu', $displayData);		 
	}
}