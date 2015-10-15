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
 * cartabandonment
 *
 */
class plgPaycartcartabandonment extends Rb_Plugin
{
	public function onRbControllerCreation(&$option, &$view, &$controller, &$task, &$format)
	{
		//autoload model and table
		
        $dir = dirname(__FILE__).'/cartabandonment';
		Rb_HelperLoader::addAutoLoadFile($dir.'/model.php', 'PaycartModelCartabandonment');
		Rb_HelperLoader::addAutoLoadFile($dir.'/table.php', 'PaycartTableCartabandonment');
		Rb_HelperLoader::addAutoLoadFile($dir.'/view/view.html.php', 'PaycartAdminHtmlViewCartabandonment');
		
		Rb_HelperLoader::addAutoLoadFile($dir.'/controller.php', 'PaycartadminControllerCartabandonment');
        return true;
	}
		
	public function onPaycartViewBeforeRender(Rb_View $view, $task)
	{
		$adminMenu =	Array(
										'title' => JText::_('COM_PAYCART_ADMIN_ABANDONMENT'),
										'url' => 'index.php?option=com_paycart&view=cartabandonment',
										'class' => 'fa-retweet'
									);
							
		$menu = PaycartFactory::getHelper('adminmenu');
		$menu->addMenu($adminMenu, 'apps');
	}
		
	public function onPaycartCron()
	{
		$model     = PaycartFactory::getModel('cartabandonment');
		$records   = $model->loadRecords(array('published' => 1));		
		$cartIds   = array();
		$lang_code = PaycartFactory::getPCCurrentLanguageCode();
		$logData   = array();
		
		foreach ($records as $record){
			$cartIds    = $this->_getUnprocessedUnpaidCarts($record->when_to_email, $record->cartabandonment_id);
					
			foreach ($cartIds as $cartId => $data) {
				$cart = PaycartCart::getInstance($cartId, $data);
	        	
	            /* @var $token_helper PaycartHelperToken  */
	            $token_helper = PaycartFactory::getHelper('token');            
	            $tokens       = $token_helper->getTokens($cart, $lang_code);
	            
	            // send notification
	            $this->_sendEmail($tokens, $record);
	            $logData[] = array('cartabandonment_id' => $record->cartabandonment_id, 'cart_id' => $cartId); 
			}
		}
		//add to log table
		if(!empty($logData)){
			$this->_addLogEntry($logData);
		}
	}
	
	public function _addLogEntry($logData)
	{
		$query = new Rb_Query();
		$db    = PaycartFactory::getDbo();
		
		$query->insert('#__paycart_cartabandonment_logs')
				  ->columns(
						array(
							$db->quoteName('cartabandonment_id'), $db->quoteName('cart_id')
						     )
				           );
				  
		foreach ($logData as $data){
			$query->values("
							{$data['cartabandonment_id']}, {$data['cart_id']}
					      ");
		}
			
		$db->setQuery($query);
		try	{
			$db->execute();
		}
		catch (RuntimeException $e) {
			//@PCTODO::proper message propagates
			Rb_Error::raiseError(500, $e->getMessage());
		}
	}
	
	public function _sendEmail($tokens, $record)
	{
		$mailer     = PaycartFactory::getMailer();
            
       /* @var $token_helper PaycartHelperToken  */
       $token_helper = PaycartFactory::getHelper('token');
            
       // Add email recipient
       $to  =    $token_helper->replaceTokens($record->to, $tokens);
       $to  = explode(',', $to);
       $mailer->addRecipient($to);
       
        //Add carbon copy recipients to the email
        if ( !empty($record->cc ) ) {
             $cc   =   $token_helper->replaceTokens($record->cc, $tokens);
             $cc   =   explode(',', $cc);
             $mailer->addCC($cc);
        }
            
        // Add carbon copy recipients to the email
        if ( !empty($this->bcc ) ) {
             $bcc   =   $token_helper->replaceTokens($record->bcc, $tokens);
             $bcc         =   explode(',', $bcc);
             $mailer->addBCC($bcc);
        }
            
        // Add subject
        $mailer->setSubject($token_helper->replaceTokens($record->subject, $tokens));
            
        // Add Body
        $mailer->setBody($token_helper->replaceTokens($record->body, $tokens));
        
		$mailer->isHtml(true);
        if ( !$mailer->Send() ) {
           //@PCTODO :: Notify to admin
           return false;
        }
        
        return true;
	}
	
	public function _getUnprocessedUnpaidCarts($time, $cartabandonment_id)
	{
		$e1 = new Rb_Date(PaycartFactory::getConfig()->get('cron_access_time'));
		$e2	= new Rb_Date('now');
	
		$e1->alter($time,'sub');
		$e2->alter($time,'sub');
		
		$db    = PaycartFactory::getDbo();
		
		$query = "  SELECT c.* FROM `#__paycart_cart` as c
					INNER JOIN ( SELECT MAX(`cart_id`) AS maxid FROM `#__paycart_cart` 
						 WHERE 
						 `created_date` > '".$e1->toSql()."' 
						      AND
						 `created_date` < '".$e2->toSql()."' 
						      AND
				         `status` = '".Paycart::STATUS_CART_DRAFTED."' 
				              AND 
				         `is_locked` = 0 
				               AND 
				         `buyer_id` <> 0
				               AND
				         `params` <> '{}'
				         	   AND
				         `cart_id` NOT IN (SELECT `cart_id` FROM `#__paycart_cartabandonment_logs` where `cartabandonment_id` = ".$cartabandonment_id.")
				    	 GROUP BY `buyer_id`
				    ) as tmp on
		        	c.`cart_id` = tmp.maxid";
		
		$records = $db->setQuery($query)->loadAssocList('cart_id');
		
		return $records;		
	}
}
