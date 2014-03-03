<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartShippngrule.Processor
* @subpackage       FlatRate
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
}