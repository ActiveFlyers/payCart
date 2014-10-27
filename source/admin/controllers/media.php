<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/
defined('_JEXEC') or die( 'Restricted access' );
/**
 * Admin Controller for Media
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartAdminControllerMedia extends PaycartController 
{
	
	public function save()
	{
		if($this->input->get('format', 'html') == 'json'){
			$data 	= $this->input->post->get($this->_component->getNameSmall().'_form', array(), 'array');		
			$itemId = $this->_getId();		
			$ret 	= parent::_save($data, $itemId);
			
			$view = $this->getView();
			if($ret){
				$view->assign('success', true);
			}
			else{
				$view->assign('success', true);
			}
		
			return true;
		}
		
		return parent::save();
	}
}