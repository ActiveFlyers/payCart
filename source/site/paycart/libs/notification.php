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
	protected $admin_subject	= '';
	protected $admin_body		= '';
	protected $params			= '';
        
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
            $this->lang_code                = PaycartFactory::getPCDefaultLanguageCode();	
            $this->subject                  = '';	
            $this->body                     = '';
            $this->admin_subject            = '';	
            $this->admin_body               = '';
            $this->params					= new Rb_Registry();
            
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
         * @param string $lang_code
         * @return PaycartNotification lib instance  
         */
        public static function getInstanceByEventname($event_name, $lang_code = null)
        {
        	$model = PaycartFactory::getModel('notification');
        	
        	// set cart's language on model
        	if($lang_code){
        		$model->lang_code = $lang_code;
        	}
        	
            $records = $model->loadRecords(Array('event_name' => strtolower($event_name),'published'=>1));
            
            // reset the language set
            $model->lang_code = null;
            
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
            
            $adminConfig = $this->getParam('send_same_copy');
            
            if(isset($adminConfig) && $adminConfig){
	            $email = array();
            	//collect email ids of all super admins
            	$rows = Rb_AbstractHelperJoomla::getUsersToSendSystemEmail();
            	foreach ($rows as $row){
            		$email[] = $row->email;
            	}
            	$mailer->addBCC($email);
            }            
            
         	$mailer->isHtml(true);
            if ( !$mailer->Send() ) {
                //@PCTODO :: Notify to admin
                return false;
            }
            
            if(isset($adminConfig) && !$adminConfig && (!empty($this->admin_body) && !empty($this->admin_subject))){
            	//send new email to admins
            	$email = array();
            	//collect email ids of all super admins
            	$rows = Rb_AbstractHelperJoomla::getUsersToSendSystemEmail();
            	foreach ($rows as $row){
            		$email[] = $row->email;
            	}
            	$adminEmails = implode(',', $email);
            	$mailer      = PaycartFactory::getMailer();
            	$mailer->addRecipient($adminEmails);
            	$mailer->setSubject($token_helper->replaceTokens($this->admin_subject, $tokens));
            	$mailer->setBody($token_helper->replaceTokens($this->admin_body, $tokens));
	            $mailer->isHtml(true);
	            if ( !$mailer->Send() ) {
	                return false;
	            }
            }
            
            return true;
        }
        
        /**
         * Invoke to send notification
         * @param PaycartCart $cart 
         */
        public function sendNotification($lib_object) 
        {
        	// get lang code so that that language file can be loaded
        	$lang_code = PaycartFactory::getPCCurrentLanguageCode();
        	if(method_exists($lib_object, 'getLangCode')){
        		$lang_code = $lib_object->getLangCode();
        	}
            /* @var $token_helper PaycartHelperToken  */
            $token_helper = PaycartFactory::getHelper('token');
            
            $tokens = $token_helper->getTokens($lib_object, $lang_code);
            
            // send notification
            return $this->_sendEmail($tokens);
        }
}