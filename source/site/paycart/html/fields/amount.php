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
		$currency = '$'; 
		$value	  = $this->value;
		return 
				"<div class='input-prepend'>
					<span class='add-on'>{$currency}</span>
					<input class='input-small' type='text' value='{$value}'>
				</div>";
					
	}
	
}