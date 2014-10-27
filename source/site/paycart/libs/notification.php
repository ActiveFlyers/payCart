<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage           Front-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Notification Lib
 */
class PaycartNotification extends PaycartLib
{
	protected $notification_id	 	= 0; 
	protected $event_name			= '';
	protected $published			= 1;
	protected $to			 	= '';
	protected $cc		 		= '';
	protected $bcc		 		= '';
	protected $media                        = '';	
	
	//language specific data
	protected $notification_lang_id	   	= 0;
	protected $lang_code 		   	= '';
	protected $subject		   	= '';
	protected $body			   	= '';
        
        //Var for future aspect
//       protected $isHtml		   	= '';
//        protected $reply_to                	= '';
        
        /**
         * Lib specific var
         * @var JMail 
         */
        protected $_mailer;

        
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::reset()
	 */
	public function reset() 
	{
            $this->notification_lang_id     = 0;
            $this->notification_id          = 0;
            $this->published                = 1;
            $this->event_name               = '';
            $this->to                       = '';
            $this->cc                       = '';
            $this->bcc                      = '';	
            $this->media                    = new Rb_Registry(); 
            $this->lang_code                = PaycartFactory::getCurrentLanguageCode();	
            $this->subject                  = '';	
            $this->body                     = '';
            
            return $this;
	}
	
	/**
	 * 
	 * PaycartNotification Instance
	 * @param  $id, existing Product id
	 * @param  $data, required data to bind on return instance	
	 * @param  $dummy1, Just follow code-standards
	 * @param  $dummy2, Just follow code-standards
	 * 
	 * @return PaycartNotification lib instance
	 */
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
            return parent::getInstance('notification', $id, $data);
	}
        
        
        /**
         * Invoke to get PaycartNotification Instances for specific event 
         * @param type $event_name
         * @return PaycartNotification lib instance  
         */
        public static function getInstanceByEventname($event_name)
        {
            $records = PaycartFactory::getModel('notification')->loadRecords(Array('event_name' => strtolower($event_name),'published'=>1));
            
            if (empty($records)) {
                return false;
            }
                    
            $records = array_shift($records);
            
            return self::getInstance($records->notification_id, $records);
        }
	
        
        /**
         * Invoke to get current object event name
         * @return type 
         */
        public function getEventName()
        {
            return $this->event_name;
        }

        /**
         * Invoke to send email.
         * This assume that "to" have properly configured
         * 
         * @param Array $tokens
         * 
         * @return void
         */
        protected function _sendEmail(Array $tokens)
        {
            $mailer     = PaycartFactory::getMailer();
            
            /* @var $token_helper PaycartHelperToken  */
            $token_helper = PaycartFactory::getHelper('token');
            
            // Add email recipient
            $to  =    $token_helper->replaceTokens($this->to, $tokens);
            $to = explode(',', $to);
            $mailer->addRecipient($to);
            
            //Add carbon copy recipients to the email
            if ( !empty($this->cc ) ) {
                $cc   =   $token_helper->replaceTokens($this->cc, $tokens);
                $cc   =   explode(',', $cc);
                $mailer->addCC($cc);
            }
            
            // Add carbon copy recipients to the email
            if ( !empty($this->bcc ) ) {
                $bcc   =   $token_helper->replaceTokens($this->bcc, $tokens);
                $bcc         =   explode(',', $bcc);
                $mailer->addBCC($bcc);
            }
            
            // Add subject
            $mailer->setSubject($token_helper->replaceTokens($this->subject, $tokens));
            
            //@PCTODO :: invoke here layout for email template
            // Add Body
            $mailer->setBody($token_helper->replaceTokens($this->body, $tokens));
            
            $mailer->isHtml(true);
            if ( !$mailer->Send() ) {
                //@PCTODO :: Notify to admin
                return false;
            }
            
            return true;
        }
        
        /**
         * Invoke to send notification
         * @param PaycartCart $cart 
         */
        public function sendNotification($lib_object) 
        {
            /* @var $token_helper PaycartHelperToken  */
            $token_helper = PaycartFactory::getHelper('token');
            
            $tokens = $token_helper->getTokens($lib_object);
            
            // send notification
            return $this->_sendEmail($tokens);
        }
}