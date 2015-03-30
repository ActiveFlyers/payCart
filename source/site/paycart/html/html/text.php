<?php
/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		paycart
* @subpackage	Frontend
* @contact 		paycart@readybytes.in
* @author		rimjhim jain
*/
if(defined('_JEXEC')===false) die('Restricted access' );

class PaycartHtmlText
{
	static function filter($name, $view, Array $filters = array(), $prefix='',$attr=array())
	{
		$elementName  = $prefix.'_'.$view.'_'.$name;
		$elementValue = @array_shift($filters[$name]);
		
		$class = isset($attr['class'])?$attr['class']:'';		
		$placeHolder = isset($attr['placeHolder'])?$attr['placeHolder']:'';
		
		$html  = '<input id="'.$elementName.'" ' 
						.'name="'.$elementName.'[]" ' 
						.'value="'.$elementValue.'" '
						.'size="25" class="'.$class.'" placeHolder="'.$placeHolder.'"/>';
						
		return $html;
	}
}
