<?php

/**
* @copyright        Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PAYCART
* @subpackage       Back-end
* @contact          support+paycart@readybytes.in 
*/
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Notification Admin Controller for Group
 * 
 * @since 1.0.0
 *  
 * @author mManishTrivedi
 */
class PaycartAdminControllerNotification extends PaycartController 
{
    /**
     * Ajax task invoke on update template 
     */
    public function save()
    {
		// call parent save
        parent::save() ;
        
		// disable redirection 
        $this->setRedirect( '');  
        
        return false;
    }
	
}