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

class PaycartFormFieldAmount extends JFormField
{	
	public function getInput()
	{
		//Currency get from global config
		$currency = PaycartFactory::getConfig()->get('currency', '$');
		$value	  = $this->value;
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		return 
				"<div class='row-fluid'>
					<div class='input-prepend'>
					<span class='add-on'>{$currency}</span>
					<input name='{$this->name}' $class  type='text' value='{$value}'>
					</div>
				</div>";					
	}	
}