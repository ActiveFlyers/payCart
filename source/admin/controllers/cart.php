<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		Puneet Singhal, rimjhim
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

class PaycartAdminControllerCart extends PaycartController 
{
	/**
	 * Json task : save new/existing shipment from the current cart
	 */
	public function saveShipment()
	{
		return true;
	}

	/**
	 * Json task : remove shipment from the current cart
	 */
	public function removeShipment()
	{
		return true;
	}
        
        public function approved() 
        {
            $post = $this->input->post->get('paycart_form', array(), 'array');
            
            $cart_id = $post['cart_id'];
            
            if (!$cart_id) {
                $this->setredirect(
                        'index.php?option=com_paycart&view=cart',
                        JText::_('COM_PAYCART_ADMIN_APPROVED_WARNING'), 
                        'warning'
                        );
                return false;
            }
            
            $cart = PaycartCart::getInstance($cart_id);
            
            $cart->markApproved()->save();
            
            $this->setredirect(
                        'index.php?option=com_paycart&view=cart&task=edit&id='.$cart_id,
                        JText::_('COM_PAYCART_ADMIN_APPROVED_SUCCESSFULLY')
                        );
            
           return false;
        }
        
        
        public function paid() 
        {
             $post = $this->input->post->get('paycart_form', array(), 'array');
            
            $cart_id = $post['cart_id'];
            
            if (!$cart_id) {
                $this->setredirect(
                        'index.php?option=com_paycart&view=cart',
                        JText::_('COM_PAYCART_ADMIN_PAID_WARNING'), 
                        'warning'
                        );
                return false;
            }
            
            $cart = PaycartCart::getInstance($cart_id);
            
            $cart->markPaid()->save();
            
            $this->setredirect(
                        'index.php?option=com_paycart&view=cart&task=edit&id='.$cart_id,
                        JText::_('COM_PAYCART_ADMIN_PAID_SUCCESSFULLY')
                        );
            
           return false;
        }
}