<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		Puneet Singhal 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Cart Model
 */
class PaycartModelCart extends PaycartModel
{
	var $filterMatchOpeartor = Array(
									'buyer_id' 	=> array('LIKE'),
									'status'	=> array('='),
									'is_approved' => array('='),
									'is_delivered' => array('='),
									);
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Model::_buildQueryJoins()
	 * 
	 * Add inner join of joomla users table, if buyer_id filter exists
	 */								
	protected function _buildQueryJoins(Rb_Query &$query)
	{
		$filters = $this->getFilters();
		
		if($filters && count($filters) && isset($filters['buyer_id'])){
			$value = array_shift($filters['buyer_id']);
    		if(!empty($value)){
    			$operator  = array_shift($this->filterMatchOpeartor['buyer_id']); 
    			$condition = "( `username` $operator '%{$value}%' || `name` $operator '%{$value}%' || `email` $operator '%{$value}%' )";
    			$query->innerJoin('`#__users` as usr on tbl.`buyer_id` = usr.id and '.$condition);
    		}
		}
	}									
}

class PaycartModelformCart extends PaycartModelform { }

/** 
 * Cart Table
 */
class PaycartTableCart extends PaycartTable {}