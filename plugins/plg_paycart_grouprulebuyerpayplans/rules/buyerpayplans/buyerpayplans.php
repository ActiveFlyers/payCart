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
include_once JPATH_SITE.'/components/com_payplans/includes/api.php';
/**
 * Buyer Address Group Rule
 *
 * @author
 */
class PaycartGroupruleBuyerpayplans extends PaycartGrouprule
{	
	public function isApplicable($buyer_id)
	{
		$config_plan_assignment = $this->config->get('plan_assignment', 'any');
			
		if($buyer_id){
			
			$activeSubscription = PayplansApi::getSubscriptions(array('user_id' => $buyer_id,'status' => 1601));
			$activeSubscription = array_keys($activeSubscription);
		}
		
		else {
			return false;
		}
				
		$config_plans = $this->config->get('plan', array());		
		$common_plans = array_intersect($activeSubscription, $config_plans);
		
		if('aboveAll' == $config_plan_assignment){
			$fullyExists = (count($config_plans) == count($common_plans));
			if($fullyExists){
				return true;
			}
			else 
				return false;
			}
		
		if('selected' == $config_plan_assignment){
			if(count($common_plans) > 0){
				return true;
			}
			
			return false;
		}
		
		if('except' == $config_plan_assignment){
			if(count($common_plans) > 0 || count($activeSubscription) == 0){
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
	public function getConfigHtml($namePrefix = '')
	{
		$idPrefix = str_replace(array('[', ']'), '', $namePrefix);
		
		$userPlans 		= PayplansApi::getPlans();
		$config 	= $this->config->toArray();
		
		ob_start();
		include dirname(__FILE__).'/tmpl/config.php';
		$contents = ob_get_contents();
		ob_end_clean();
		
		$scripts 	= array();
		static $scriptAdded = false;
		if(!$scriptAdded){			
			$scripts[] 	= 'paycart.jQuery("select.paycart-grouprule-buyerplans").chosen({disable_search_threshold : 10, allow_single_deselect : true });';
			$scriptAdded = true;
		}
		
		return array($contents, $scripts);
	}
}