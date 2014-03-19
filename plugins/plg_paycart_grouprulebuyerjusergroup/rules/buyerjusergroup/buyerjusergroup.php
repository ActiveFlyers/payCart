<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Buyer
* @subpackage       BuyerJusergroup
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Buyer Address Group Rule
 *
 * @author Gaurav Jain
 */
class PaycartGroupruleBuyerjusergroup extends PaycartGrouprule
{	
	public function isApplicable($entity_id)
	{
		// Entity id will be buyer_id
		$buyer_id = $entity_id;		
			
		$params_jusergroup_assignment = $this->params->get('jusergroup_assignment', 'any');
		
		if('any' == $params_jusergroup_assignment){
			return true;
		}
				
		if($buyer_id){
			$buyer 		= JFactory::getUser($buyer_id);
			$user_group = $buyer->groups;
		}
		else{
			$user_group = array(JComponentHelper::getParams('com_users')->get('guest_usergroup', 1));
		}				
		
		$params_jusergroup = $this->params->get('jusergroups', array());		
		$common_usergroups = array_intersect($user_group, $params_jusergroup);
		
		if('selected' == $params_jusergroup_assignment){
			if(count($common_usergroups) > 0){
				return true;
			}
			
			return false;
		}
		
		if('except' == $params_jusergroup_assignment){
			if(count($common_usergroups) > 0){
				return false;
			}
			
			return true;
		}

		return false;		
	}
	
	/**
	 * Gets the html and js script call of parameteres 
	 * @return array() Array of Html and JavaScript functions to be called
	 */
	public function getParamsHtml($namePrefix = '')
	{
		// @TODO : Use paycart helper
		$usergroups = Rb_HelperJoomla::getJoomlaGroups();
		$params 	= $this->params->toArray();
		
		ob_start();
		include dirname(__FILE__).'/tmpl/params.php';
		$contents = ob_get_contents();
		ob_end_clean();
		
		$scripts 	= array();
		static $scriptAdded = false;
		if(!$scriptAdded){			
			$scripts[] 	= 'paycart.jQuery("select.paycart-grouprule-buyerjusergroup-groups").chosen({disable_search_threshold : 10, allow_single_deselect : true });';
			$scriptAdded = true;
		}
		
		return array($contents, $scripts);
	}
}