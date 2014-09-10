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
            
            $this->_mailer = PaycartFactory::getMailer();
            
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
	 * @return Product name 
	 */
	public function getTitle() 
	{	
            return $this->title;
	}
	
	/**
	 * @return media id set as product Cover Media 
	 */
	public function geMedia($requireMediaArray = true) 
	{
            return $this->media;
	}
        
   /**
    * Add given buyer email to TO
    * @param type $buyer_email
    * @return boolean 
    */
	public function addTo($buyer_email)
	{
            if(empty($buyer_email)) {
                PaycartFactory::getHelper('log')->addLog('Recipient is not exixt here');
                return false;
            }

            $this->_mailer->addRecipient($buyer_email);
            
            if(!empty($this->to)) {
                $this->_mailer->addRecipient($this->to);
            }
            
           return true;   
	}

        
   /**
    * Add carbon copy recipients to the email
    * 
    * @return boolean 
    */
	public function addCC()
	{
            if(empty($this->cc)) {
                return false;
            }

            $emails = explode(',', $this->cc);
            
            foreach($emails as $email){
                $this->_mailer->addCC($email);
            }
            
            return true;
	}

        /**
         * Add blind carbon copy recipients to the email
         * 
         * @return boolean 
         */
	public function addBCC()
	{
            if(empty($this->cc)) {
                return false;
            }

            $emails = explode(',', $this->cc);
            
            foreach($emails as $email){
                $this->_mailer->addBCC($email);
            }
            
            return true;
        }
        

        /**
         * Invoke on specific event firing
         * @param type $entity_object 
         */
        protected function _sendNotification($buyer_email, $relative_object)
        {
            // build token with their values
            //@PCTODO :: move token proper location, We can use on email preview. 
            $tokens = Array();
            
             /* @var $token_helper PaycartHelperToken  */
            $token_helper = PaycartFactory::getHelper('token');
            
            $tokens = array_merge($tokens, $token_helper->getCartToken($relative_object->cart));
            $tokens = array_merge($tokens, $token_helper->getConfigToken($relative_object->config));
            $tokens = array_merge($tokens, $token_helper->getBuyerToken($relative_object->buyer));
            $tokens = array_merge($tokens, $token_helper->getProductToken($relative_object->product_particular_list));
            $tokens = array_merge($tokens, $token_helper->getBillingToken($relative_object->billing_address));
            $tokens = array_merge($tokens, $token_helper->getShippingToken($relative_object->shipping_address));
            
            $this->subject  =    $token_helper->replaceTokens($this->subject, $tokens);
            $this->body     =    $token_helper->replaceTokens($this->body, $tokens);
            
            
            // Add email recipient
            $this->addTo($buyer_email);
            $this->addCC();
            $this->addBCC();
            
            // Add subject
            $this->_mailer->setSubject($this->subject);
            
            //@PCTODO :: invoke here layout for email template
            // Add Body
            $this->_mailer->setBody($this->body);
            
            /* @var $helper PaycartHelperlog */
            $helper = PaycartFactory::getHelper('log');
   
            if ( $this->_mailer->Send() ) {
                $helper->add("Email Successfully send. {$this}");  
            } else {
                //@PCTODO :: Notify to admin
                $helper->add("Email sending fail on {$this}");  
            }
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
            $relative_object = $token_helper->getCartRelativeObjects($cart);
            
            $buyer_email = $cart->getBuyer(true)->getEmail();
            
            // send notification
            $this->_sendNotification($buyer_email, $relative_object);
        }
        
        
}