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
        protected $title                        = '';
	protected $description                  = '';
	protected $subject		   	= '';
	protected $body			   	= '';
        
        //Var for future aspect
//        protected $isHtml		   	= '';
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
            $this->title                    = '';
            $this->description              = '';
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
         * @PCTODO:: move to helper
		 * Invoke to get PaycartNotification Instances for specific event 
         * @param type $event_name
         * @return Array  
         */
        public static function getInstanceByEventname($event_name)
        {
            $records = $this->getModel()->loadRecords(Array('event_name' => "$event_name"));
            
            $instance = Array();
            foreach ($records as $record_id => $data) 
            {
                $instance[$record_id] = self::getInstance($record_id, $data); 
            }
            
            return $instance;
        }

        /**
         *
         * @return title
         */
		public function getTitle() 
		{	
           return $this->title;
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
            $mailer                  = PaycartFactory::getMailer();
            
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
                $this->_mailer->addCC($cc);
            }
            
            // Add carbon copy recipients to the email
            if ( !empty($this->bcc ) ) {
                $bcc   =   $token_helper->replaceTokens($this->bcc, $tokens);
                $bcc         =   explode(',', $bcc);
                $this->_mailer->addBCC($bcc);
            }
            
            // Add subject
            $this->_mailer->setSubject($this->subject);
            
            //@PCTODO :: invoke here layout for email template
            // Add Body
            $this->_mailer->setBody($this->body);
            
            
            if ( !$this->_mailer->Send() ) {
                //@PCTODO :: Notify to admin
                return false;
            }
            
            return true;
        }
        
        
        /**
         * Invoke to send notification when event fire on cart
         * @param PaycartCart $cart 
         */
        public function sendNotificationOnCart(PaycartCart $cart) 
        {
            /* @var $token_helper PaycartHelperToken  */
            $token_helper = PaycartFactory::getHelper('token');
            
            // get all relative objects
            $tokens = $token_helper->buildCartTokens($cart);
            
            // send notification
            $this->_sendEmail($tokens);
        }
}