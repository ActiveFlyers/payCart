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
 * Buyer Model
 */
class PaycartModelBuyer extends PaycartModel
{

	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_Model::_buildQueryFrom()
	 */
    protected function _buildQueryFrom(Rb_Query &$query)
    {
    	$tname		=	$this->getTable()->getTableName();

    	// Join paycart buyer table
    	$join1 = " `$tname` AS t  ON ( t.`buyer_id` = joomlausertbl.`id` ) ";
    	// Join usergroup-map table (get user-type id)
    	$join2 = " `#__user_usergroup_map` AS g  ON ( g.`user_id` = joomlausertbl.`id` ) ";
    	
    	$sql  = new Rb_Query();
    	
    	$sql->select(' joomlausertbl.`id` AS buyer_id ')
    		->select(' joomlausertbl.`name` AS realname ')
    	    ->select(' joomlausertbl.`username` AS username ')
    	    ->select(' joomlausertbl.`email` AS email ')
    	    ->select(' joomlausertbl.`registerDate` AS register_date ')
    	    ->select(' joomlausertbl.`lastvisitDate` AS lastvisit_date ')
    	    ->select(' t.`is_registered_by_guestcheckout` AS is_registered_by_guestcheckout  ') 	 
    	    ->select(' t.`billing_address_id` AS billing_address_id  ')
    	    ->select(' t.`shipping_address_id` AS shipping_address_id  ')
    	    ->select(' GROUP_CONCAT( g.`group_id` ) AS user_type ')
    	    ->from(' `#__users` AS joomlausertbl ')
    	    ->leftJoin($join1)
    	    ->leftJoin($join2)
    	    ->group("  g.`user_id` ");
    	
	    $query->from('( '.$sql->__toString().') AS tbl ');   
    }

    /**
     * (non-PHPdoc)
     * @see /plugins/system/rbsl/rb/rb/Rb_Model::save()
     */
	function save($data, $pk=null, $new=false)
    {
		$new = $this->getTable()->load($pk)? false : true;
		return parent::save($data, $pk, $new);
    }
}

class PaycartModelformBuyer extends PaycartModelform { }
