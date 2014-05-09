<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Product Base View
 * @author mManishTrivedi
 */
class PaycartAdminBaseViewProductAttribute extends PaycartView 
{ 
	protected function _assignTemplateVars()
	{
		$attributeId	=  $this->getModel()->getId();
		$attribute		=  PaycartProductAttribute::getInstance($attributeId);
		
		$form 		= $attribute->getModelform()->getForm();
	    $language   = array('language'=> $attribute->getLanguage());
	    $form->bind($language);
		
	    // get html of the specific 
	    $type = $this->input->get('type', $attribute->getType());
	    
	    $html = PaycartAttribute::getInstance($type)->getConfigHtml($attribute);
	    
	    $this->assign('attributeHtml',$html);
		$this->assign('form', $form);
	}
}
