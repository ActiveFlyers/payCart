<?php
/**
 * @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
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
 * PaycartSiteController Cron
 * @author rimjhim jain
 *
 */

class PaycartsiteControllerCron extends paycartController
{
	protected 	$_defaultTask = 'trigger';

	// No Model
	public function getModel($name = '', $prefix = '', $config = array())
	{
		return null;
	}

	/**
	 * The function called which will trigger the cron action
	 */
	public function trigger($event=null,$args=null)
	{
		if (!headers_sent()) {
			header("Content-type: image/png");
		}

	    echo file_get_contents(PAYCART_PATH_CORE_MEDIA.'/images/cron.png');
	
 		// check if we need to trigger, dont trigger too frequently
		if(PaycartHelperCron::checkRequired()==false){
			Paycart::markExit(JText::_('COM_PAYCART_CRON_NOT_REQUIRED'));
			return false;
		}
			
		// If simultaneous requests are coming then allow only one and reject the other request
		// PCTODO: We can increase timeOut instead of 0, 
		// if we want to execute the other request to wait for some given timeout
		$lock =  PaycartLock::getInstance('paycartCron');
		
	
		if($lock->getLockResult()){
			// trigger plugin and actions
			/*  @var $event_helper PaycartHelperEvent   */
	        $eventHelper = PaycartFactory::getHelper('event');
	        $date = new Rb_Date();
			$now  = $date->toUnix();
			
	        $eventHelper->onPaycartCron();
	        
			// Mark exit
			Paycart::markExit(JText::_('COM_PAYCART_CRON_EXECUTED'));
			
			PaycartFactory::saveConfig(array('cron_access_time'=>$now));
			return true;
		}
		
		// Mark exit
		Paycart::markExit(JText::_('COM_PAYCART_CRON_NOT_REQUIRED'));
		return false;
	}
}

