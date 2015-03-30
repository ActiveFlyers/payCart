<?php
/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Paycart
* @subpackage	Frontend
* @contact 		support+paycart@readybytes.in
* @author		rimjhim jain
*/
if(defined('_JEXEC')===false) die('Restricted access' );

class paycartHtmlRange
{	
	static function filter($name, $view, Array $filters = array(), $type="date", $prefix='', $attr=array())
	{
		$elementName   = $prefix.'_'.$view.'_'.$name;
		$elementValue0 = @array_shift($filters[$name]);
		$elementValue1 = @array_shift($filters[$name]);
		$style		   = isset($attr['style'])?$attr['style']:'';
		
		$from  = '<div class="muted pull-left pc-filter-minwidth-50"><label>'.JText::_('COM_PAYCART_ADMIN_FILTERS_FROM').'</label></div>';
		$to    = '<div class="muted pull-left pc-filter-minwidth-50"><label>'.JText::_('COM_PAYCART_ADMIN_FILTERS_TO').'</label></div>';
			
			
		if(strtolower($type)=="date"){
			$from .= '<div>'. JHtml::_('calendar', $elementValue0, $elementName.'[0]', $elementName.'_0', '%Y-%m-%d',$style).'</div>';
			$to   .= '<div>'.JHtml::_('calendar', $elementValue1, $elementName.'[1]', $elementName.'_1', '%Y-%m-%d',$style).'</div>';
		}
		elseif(strtolower($type)=="text"){
			
			$from .= '<div>'
						.'<input id="'.$elementName.'_0" ' 
						.'name="'.$elementName.'[0]" ' 
						.'value="'.$elementValue0.'" '
						.'size="20" class="input-small"/>'
						.'</div>';
			$to   .= '<div>'
						.'<input id="'.$elementName.'_1" ' 
						.'name="'.$elementName.'[1]" ' 
						.'value="'.$elementValue1.'" '
						.'size="20" class="input-small"/>'
						.'</div>';
		}	  
		
		return '<div>'.$from.'</div><div>'.$to.'</div>';
	}	
}