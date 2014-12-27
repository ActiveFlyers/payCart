<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/**
 * 
 * Installer script for menu
 * @author manish
 *
 */
class PaycartInstallScriptMenu 
{
	
	/**
	 * @var Array of all availble Paycart menu items
	 * 			$_menus	=>	Array ( 'VIEW_TASK' => Array( table fields) )
	 * 			Table fields :
	 * 					access = 1 (public), 2 (registered)
	 * 					published =1
	 */
	 protected static $_menus = 
			 			Array(
			 			// default view 'Store', It will display root category
			 			'productcategory_display' =>
			 				Array (	'title' 	=> 'Store', 'alias'=>'store', 'access'	=>	1, 'level'		=>  1,
			 						'link' 		=> 'index.php?option=com_paycart&view=productcategory&task=display',
			 						'children'	=>  
			 							//listed all children menu
			 							Array(
			 								// Product View
			 								'product_display' =>
								 				Array (	'title' => 'Product', 'alias'=>'product', 'access'	=>	1, 'level' =>  2,
								 						'link' => 'index.php?option=com_paycart&view=product&task=display'
								 					  ),
								 			//## Start Cart Menu
								 			// Display Current cart
								 			'cart_display' =>
								 				Array (	'title' 	=> 'Cart', 'alias'=>'cart',	'access' =>	1, 'level' =>  2,
								 						'link' 		=> 'index.php?option=com_paycart&view=cart&task=display',
								 						'children'	=>
								 							Array(	
								 								// Checkout menu
								 								'cart_checkout' =>
												 					Array (	'title' => 'Checkout', 'alias'=>'checkout',	'access' =>	1, 'level' =>  3,
												 							'link'  => 'index.php?option=com_paycart&view=cart&task=checkout'
												 					  ),
												 				// Thanks page
													 			'cart_thanks' =>
													 				Array (	'title' => 'Thanks Page', 'alias'=>'thanks', 'access' => 1, 'level' =>  3,
													 						'link' => 'index.php?option=com_paycart&view=cart&task=complete'
													 					  ),
													 			// Buy Now task
													 			'cart_buy' =>
													 				Array (	'title' => 'Buy Now', 'alias'=>'buy', 'access' => 1, 'level' =>  3,
													 						'link' => 'index.php?option=com_paycart&view=cart&task=buy'
													 					  )
								 							)
								 					  )
								 			//## End cart Menu
			 							)
			 					  ),
							'my_account' => array(
			 					  				'title' 	=> 'My Account',
			 					  				'alias' 	=> 'my-account',
			 					  				'access' 	=> 1,
			 					  				'level'		=> 1,
			 					  				'link'		=> 'index.php?option=com_paycart&view=account&task=display',
			 					  				'children'	=> array(
			 					  								'my_orders' =>	array(
							 					  									'title' 	=> 'My Orders',
												 					  				'alias' 	=> 'my-orders',
												 					  				'access' 	=> 1,
												 					  				'level'		=> 2,
												 					  				'link'		=> 'index.php?option=com_paycart&view=account&task=order'
							 					  									),
							 					  				'manage_address' =>	array(
							 					  									'title' 	=> 'Manage Address',
												 					  				'alias' 	=> 'manage-address',
												 					  				'access' 	=> 1,
												 					  				'level'		=> 2,
												 					  				'link'		=> 'index.php?option=com_paycart&view=account&task=address'
							 					  									),
							 					  				'account_settings' =>	array(
							 					  									'title' 	=> 'Settings',
												 					  				'alias' 	=> 'settings',
												 					  				'access' 	=> 1,
												 					  				'level'		=> 2,
												 					  				'link'		=> 'index.php?option=com_paycart&view=account&task=setting'
							 					  									),
																'account_login' =>	array(
							 					  									'title' 	=> 'Login',
												 					  				'alias' 	=> 'login',
												 					  				'access' 	=> 1,
												 					  				'level'		=> 2,
												 					  				'link'		=> 'index.php?option=com_paycart&view=account&task=login'
							 					  									)
			 					  								)
			 					  )		 					  
							);
							
	const 	MENUTYPE 					= 	'paycart';
	const  	JOOMLA_MENU_DEFAULT_PARENT_ROOT		= 	1;
	const 	JOOMLA_MENU_DEFAULT_PARENT_LEVEL	=	1;
	
	/**
	 * Create Paycart Menu-Item
	 * 
	 * @return void
	 * 	 
	 * @since 1.0
	 * @author mManishTrivedi
	 */
	public static function createMenus() 
	{
		//1# Create Menu Type
		if(!self::isMenutypeExist()){
			self::addDefaultMenuType();
		}

		//2# Create Menu
		if (self::isMenuExist()){
			self::updateMenuItems();
		} else {
			// If menu already created then need to update it 
			self::addMenuItems();
		}

		return true;
	}
	
	/**
	 * Check if JomSocial Menu type exits
	 * @return [boolean] [return true/false]
	 * 
	 * @since 1.0
	 * @author mManishTrivedi
	 */
    protected static function isMenuTypeExist()
    {
        $db		= JFactory::getDBO();

        $query	= '	SELECT COUNT(*) 
        			FROM ' .  $db->quoteName( '#__menu_types' ) . ' 
            		WHERE ' . $db->quoteName( 'menutype' ) . ' = ' .  $db->Quote( self::MENUTYPE);
	
        $db->setQuery( $query );

        return  ( $db->loadResult() >= 1 ) ? true : false;
    }
    
	/**
	 * Add Default Menu types
	 */
	protected static function addDefaultMenuType()
	{
		$db		= JFactory::getDBO();
		
		$query	= 'INSERT INTO ' . $db->quoteName( '#__menu_types' ) . ' (' . $db->quoteName('menutype') .',' . $db->quoteName('title') .',' . $db->quoteName('description') .') 
					VALUES '
		    			. '( ' .  $db->Quote( self::MENUTYPE) . ',' . $db->Quote( 'Cart Menu' ) . ',' . $db->Quote( 'Menu items for your cart') . ')';
		$db->setQuery( $query );
		$db->execute();
		if ($db->getErrorNum())
		{
			return false;
		}
		return true;
	}
	
	/**
	 * Check if menu exist
	 * @return [boolean] [return true/false]
	 */
	protected static function isMenuExist()
	{
		$db		= JFactory::getDBO();

		$query	= 'SELECT COUNT(*) FROM ' . $db->quoteName( '#__menu' ) . ' '
				. 'WHERE ' . $db->quoteName( 'link' ) . ' LIKE ' .  $db->Quote( '%option=com_paycart%') . ' '
				. 'AND ' . $db->quoteName('menutype') . ' = ' . $db->Quote(self::MENUTYPE);

		$db->setQuery( $query );

		return  ( $db->loadResult() >= 1 ) ? true : false;
	}

	/**
	 * Update Menu Item
	 * @return [boolean] [return true/false]
	 */
	protected static function updateMenuItems()
	{
		// Get new component id.
		$component		= JComponentHelper::getComponent('com_paycart');
		$component_id	= 0;
		
		if (is_object($component) && isset($component->id)){
			$component_id 	= $component->id;
		}
		
		if (!$component_id) {
			return true;
		}
		
		// Update the existing menu items.
		$db 	= JFactory::getDBO();

		$query 	= 'UPDATE ' . $db->quoteName( '#__menu' ) . ' '
				. 'SET '.$db->quoteName('component_id').'=' . $db->Quote( $component_id ) . ' '
				. 'WHERE ' . $db->quoteName('link') .' LIKE ' . $db->Quote('%option=com_paycart%');

		$db->setQuery( $query );
		$db->query();

		if($db->getErrorNum())
		{
			return false;
		}

		return true;
	}
	
	/**
	 * Add Menu items
	 */
	protected static function addMenuItems()
	{
		// Get new component id.
		$component    = JComponentHelper::getComponent('com_paycart');
		$component_id = 0;

		if (is_object($component) && isset($component->id))
		{
			$component_id = $component->id;
		}
		
		foreach (self::$_menus as $view_task => $menu_item ) 
		{
			$menu_item['component_id']	= $component_id;
			self::_addMenuItem($menu_item);
		}


		return true;
	}
	
	/**
	 * 
	 * Create joomla Menu Item
	 * @param Array $menu, table data for menu 
	 * @param unknown_type $parent_id
	 */
	private static function _addMenuItem(Array $menu, $parent_id = self::JOOMLA_MENU_DEFAULT_PARENT_ROOT)
	{
		$data['title']			= $menu['title'];
		$data['alias']			= $menu['alias'];			// @PCTODO :: What to do when alias is duplicate 
		$data['link']			= $menu['link'];
		$data['access']			= isset($menu['access']) ? $menu['access'] : 1 ;	// default is public access
		$data['menutype']		= self::MENUTYPE;
		$data['type']			= 'component';
		$data['published']		= isset($menu['published']) ? $menu['published'] : 1 ;	// default is published menus item
		$data['parent_id']		= $parent_id; 
		$data['language']		= '*';				// for all language
		$data['parent_id']		= $parent_id;
		$data['level']			= isset ($menu['level'] ) ? $menu['level'] : self::JOOMLA_MENU_DEFAULT_PARENT_LEVEL;
		$data['component_id']	= $menu['component_id'];	// paycart id
		
		
		//$menu_table = JTable::getInstance('Menu', 'MenusTable');
		$menu_table = JTable::getInstance('Menu');
		
		// We have a new item, so it is not a change. (Copy from item-Modal backend) 
		$menu_table->setLocation($parent_id, 'last-child');
		
		// Bind the data, Check the data then Store the data.
		if (!$menu_table->bind($data) || !$menu_table->check() || !$menu_table->store() )
		{
			$this->setError($menu_table->getError());
			return false;
		}

		// Rebuild the tree path.
		if (!$menu_table->rebuildPath($menu_table->id))
		{
			$this->setError($menu_table->getError());
			return false;
		}
		
		// create child menu
		if (isset($menu['children'])) {
			foreach ($menu['children'] as $child_item) {
				$child_item['component_id']	= $menu['component_id'];
				//recursively invoke
				self::_addMenuItem( $child_item, $menu_table->id);
			}
		}
		;
	}
	
	
}