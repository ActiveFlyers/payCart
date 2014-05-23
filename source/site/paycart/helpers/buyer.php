<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		support+paycart@readybytes.in
 * @author 		Puneet Singhal, mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * buyer Helper
 * @author manish
 *
 */
class PaycartHelperBuyer extends PaycartHelper
{	

	/**
	 * Return username on bases email 
	 * @param $email string
	 *
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return string value 'username' if username exist otherwise false
	 */
	public function getUsername($email)
	{
		$db = PaycartFactory::getDbo();
		
		$query = new Rb_Query();
		$query->select('username')
			  ->from('#__users')
			  ->where('`email` = '.$db->quote($email), 'OR');
//			  ->where('`username` = '.$db->quote($email));
			  
		return $query->dbLoadQuery()->loadResult();
	}
	
	/**
	 * 
	 * Create Account on email id bases 
	 * @param $email string
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return INT user_id if successfully created account otherwise false
	 */
	public function createAccount($email)
	{
		$password 	= JUserHelper::genRandomPassword();
		$timestamp 	= PaycartFactory::getDate()->format('U');
		

		$temp = Array(	
						'username'	=>	$email,		'name'		=>	$email,	
						'email1'	=>	$email,		'email'		=>	$email,
						'password1'	=>	$password,	'password2'	=>	$password,
						'password'=>$password,		'block'=>0
					);
		
		require_once JPATH_ROOT.'/components/com_users/models/registration.php';
		
		// Initialise the table with JUser.
		$user 	= new JUser;
		$model 	= new UsersModelRegistration();
		$data 	= (array)$model->getData();
		
		// Merge in the registration data.
		foreach ($temp as $k => $v) {
			$data[$k] = $v;
		}
		
		// Bind the data.
		if (!$user->bind($data)) {
			return false;
		}
		
		// Load the users plugin group.
		//JPluginHelper::importPlugin('user');
		
		// Store the data.
		if (!$user->save()) {
			return false;
		}
		
		return $user->id;
	}
}