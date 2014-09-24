<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('number');
class PaycartFormFieldAmount extends JFormFieldNumber
{	
	public function getInput()
	{
		//Currency get from global config
		$currency = PaycartFactory::getConfig()->get('currency', '$');
		$html = parent::getInput();
		return 
				"<div class='input-prepend'>
					<span class='add-on'>{$currency}</span>
					$html
				</div>
				";					
	}	
}