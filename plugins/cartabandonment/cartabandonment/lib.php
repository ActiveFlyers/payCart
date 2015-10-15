<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		Paycart
* @subpackage	cartabandonment
* @contact 		support@readybytes.in
* website		http://www.readybytes.net
*/
if(defined('_JEXEC')===false) die();

class PaycartCartabandonment extends PaycartLib
{
	protected $cartabandonment_id 	= 0; 
	protected $published			= 1;
	protected $title		    = '';
	protected $to			 	= '';
	protected $cc		 		= '';
	protected $bcc		 		= '';
	protected $when_to_email	= '';
	//language specific data
	protected $cartabandonment_lang_id 	= 0;
	protected $lang_code 		   	= '';
	protected $subject		   	= '';
	protected $body			   	= '';
        
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::reset()
	 */
	public function reset() 
	{
            $this->cartabandonment_lang_id  = 0;
            $this->cartabandonment_id       = 0;
            $this->title					= '';
            $this->published                = 1;
            $this->event_name               = '';
            $this->cc                       = '';
            $this->bcc                      = '';
            $this->when_to_email			= '';	
            $this->lang_code                = PaycartFactory::getPCDefaultLanguageCode();	
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
            return parent::getInstance('cartabandonment', $id, $data);
	}
}
