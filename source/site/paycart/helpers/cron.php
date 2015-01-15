<?php
/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		support+paycart@readybytes.in
 * @author 		Rimjhim Jain
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * PaycartHelper Cron
 * @author rimjhim jain
 *
 */
class PaycartHelperCron
{
	/**
	 * get cron url
	 */
	public static function getURL()
	{
		// Give public URL
		return JURI::root().'index.php?option=com_paycart&view=cron&task=trigger';
	}
	
	/**
	 * check whether cron is required to execute or not
	 */
	public static function checkRequired()
	{
		$config     = PaycartFactory::getConfig();		
		$frequency  = $config->get('cron_frequency');
		$accessTime = $config->get('cron_access_time');
		$now 		= new Rb_Date();
		$now 		= $now->toUnix();
		
		// if diff of $now and $cron_access_time is greater than $frequency then return true
		if(($now - $accessTime) > $frequency){
			return true;
		}
		
		return false;
	}
}