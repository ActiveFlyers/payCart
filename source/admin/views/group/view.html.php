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
 * Admin Html View for Group
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewGroup extends PaycartAdminBaseViewGroup
{	
	public function edit($tpl=null) 
	{	
		$itemId	=  $this->getModel()->getId();
		$item	=  PaycartGroup::getInstance($itemId);
		
		$this->assign('form',  $item->getModelform()->getForm($item));		
		return parent::edit($tpl);
	}
}