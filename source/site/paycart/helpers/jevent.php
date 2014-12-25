<?php
/**
 *@copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 *@license	GNU/GPL, see LICENSE.php
 *@package	PayCart
 *@subpackage	Pacart Form
 *@author 	mManishTrivedi 
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/**
 * 
 * Handle all Joomla events
 * @author Manish Trivedi
 *
 */
class PaycartHelperJevent extends JEvent
{
	/**
	 * This method should handle any login logic and report back to the subject
	 * 	- Attache buyer on cart
	 *
	 * @param   array  $user     Holds the user data
	 * @param   array  $options  Array holding options (remember, autoregister, group)
	 *
	 * @return  boolean  True on success
	 *
	 * @author mManishTrivedi
	 * @since   1.0
	 */
	public function onUserLogin($user, $options = array())
	{
		self::loadPaycart();
		
		//cross check user not blocked
		$juser_instance = JUser::getInstance();
		$id = (int) JUserHelper::getUserId($user['username']);

		if (!$id) {
			return  true;	
		}
		
		$juser_instance->load($id);
		
		// If the user is blocked, handle by joomla
		if ($juser_instance->get('block') == 1)
		{
			return true;
		}

		// Authorise the user based on the group information
		if (!isset($options['group']))
		{
			$options['group'] = 'USERS';
		}

		// Check the user can login.
		if (!$juser_instance->authorise($options['action']))
		{
			return true;
		}
		
		/*  @var $cart_helper PaycartHelpercart */
		$cart_helper = PaycartFactory::getHelper('cart');
		
		$cart = $cart_helper->getCurrentCart();
		
		// if cart is not exist
		if (!$cart) {
			return true;
		}
		
		//Attache buyer id on cart
		$cart->setBuyer($id);
		$cart->calculate()->save();
				
		return true;
	}
	
	/**
	 * 
	 * Load Paycart System
	 */
	private static function loadPaycart() 
	{
		$fileName = JPATH_SITE. '/components/com_paycart/paycart/includes.php';
		
		// include paycart
		require_once $fileName;		
	}
}