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

class PaycartHtmlState 
{	
	/**
	 * 
	 * Invoke to get Paycart State html
	 * @param $country_selector		:	State field depends on this country field
	 * @param $name					:	Field name
	 * @param $value				:	Field Value
	 * @param $attr					:	Field attribute
	 */
	public static function getList($name, $value, $idtag = false, $attr = Array(), $country_id = '')
	{
		$options = Array();
		
		if ($country_id) {	
			if(!is_array($country_id)){
				$filter = Array('country_id'=> $country_id);
			}
			else{
				$filter['country_id'] = array('IN', '('.implode(',', $country_id));
			}
		}
		
		// if only for single coumtry, nedd not to group states
		if(!is_array($country_id)){				
			$options = PaycartFactory::getModel('state')->loadRecords(Array('country_id'=> $country_id));
			$html	=	PaycartHtml::_('select.genericlist', $options, $name, $attr, 'state_id', 'title', $value, $idtag);		
			return $html; 
		}
			
		// if there are multiple countries then group states according to country
		$filter['country_id'] = array('IN', '('.implode(',', $country_id));
		$states = PaycartFactory::getModel('state')->loadRecords($filter, Array('limit'));
		
		foreach ($states as $state_id => $state_detail) {
			if(!isset($country_states[$state_detail->country_id])){
				$country_states[$state_detail->country_id] = '';
			}
			
			$selected = '';
			if (in_array($state_id, $value)){
				$selected = 'selected="selected"';
			}
			$country_states[$state_detail->country_id] .= "<option value='{$state_detail->state_id}'  $selected > {$state_detail->title} </option>";
		}
		
		$options = array();
		$formatter = PaycartFactory::getHelper('format');
		$html = '';
		foreach($country_states as $country_id => $stateshtml){
			$html .= '<optgroup label="'.$formatter->country($country_id).'">'.$stateshtml.'</optgroup>';
		}			

		// buid id, if not set in attr
		$id = isset($attr['id']) ? $attr['id'] : $name;
		$id = str_replace(array('[', ']'), '', $id);
		
		// find attributes
		$attribs = ' ';	
		if (is_array($attr)){
			$attribs = JArrayHelper::toString($attr);
		}				
	
		$html = '<select' . ($id !== '' ? ' id="' . $id . '"' : '') . ' name="' . $name . '"' . $attribs . '>' . $html . '</select>';
		return  $html;
	}
}