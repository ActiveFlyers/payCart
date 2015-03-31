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

	public $filterMatchOpeartor = array(
										'username' 	=> array('LIKE'),
										'country_id'=> array('LIKE'),
										'usertype'	=> array('LIKE'),
									);
	
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
    	    ->select(' t.`default_phone` AS default_phone  ')
    	    ->select(' t.`default_address_id` AS default_address_id  ')
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
     
   /**
    * (non-PHPdoc)
    * @see plugins/system/rbsl/rb/rb/Rb_AbstractModel::_populateGenericFilters()
    * 
    * Overridden it to add only specific filter directly , do it only for country_id
    */
    public function _populateGenericFilters(Array &$filters=array())
	{
		parent::_populateGenericFilters($filters);

		$app  = Rb_Factory::getApplication();
				
		//now add other filters
		$data = array('country_id','username','usertype');
		foreach ($data as $key){
			$context = $this->getContext();
			$filterName  = "filter_{$context}_{$key}";
			$oldValue    = $app->getUserState($filterName);
			$value       = $app->getUserStateFromRequest($filterName ,$filterName);
		
			//offset is set to 0 in case previous value is not equals to current value
			//otherwise it will filter according to the pagination offset
			if(!empty($oldValue) && $oldValue != $value){
				$filters['limitstart']=0;
			}
			$filters[$context][$key] = $value;
		}

		return;		
	}
	
    //override it becuase buyer filters are dependent on joomla user table 
	//so that proper query can be build corresponding to applied filter
    protected function _buildQueryFilter(Rb_Query &$query, $key, $value, $tblAlias='`tbl`.')
    {
    	// Only add filter if we are working on bulk reocrds
		if($this->getId()){
			return $this;
		}
		
    	Rb_Error::assert(isset($this->filterMatchOpeartor[$key]), "OPERATOR FOR $key IS NOT AVAILABLE FOR FILTER");
    	Rb_Error::assert(is_array($value), JText::_('PLG_SYSTEM_RBSL_VALUE_FOR_FILTERS_MUST_BE_AN_ARRAY'));
    	
    	$cloneOP    = $this->filterMatchOpeartor[$key];
    	$cloneValue = $value;
    	
    	while(!empty($cloneValue) && !empty($cloneOP)){
    		$op  = array_shift($cloneOP);
    		$val = array_shift($cloneValue);

			// discard empty values
    		if(!isset($val) || '' == trim($val))
    			continue;

    		//trim value before adding it to condition
    		$val = trim($val);
    			
    		if($key == 'country_id'){
    			$query->where(" `tbl`.`buyer_id` IN( SELECT addr.`buyer_id` FROM `#__paycart_buyeraddress` AS addr 
    								                 WHERE addr.`country_id` = '{$val}' )");
    			continue;
    		}
    			
    		if($key == 'username'){
    			$query->where("( `$key` $op '%{$val}%' || `realname` $op '%{$val}%' || `email` $op '%{$val}%' )");
    			continue;
    		}
    			
    		if(strtoupper($op) == 'LIKE'){
    			if($key == 'usertype'){
					//this subquery will fetch all the users with the desired usertype 
					$query->where(" `tbl`.`buyer_id` IN( SELECT map.`user_id` 
								    FROM `#__usergroups` as groups, `#__user_usergroup_map` as map 
								    WHERE ( map.group_id = groups.id AND groups.title = '$val'))	");
					continue;
				}
	    	  	$query->where("$tblAlias`$key` $op '%{$val}%'");
				continue;
	    	}

    		$query->where("$tblAlias`$key` $op '$val'");
	    		
    	}
    }
}

class PaycartModelformBuyer extends PaycartModelform { }

/** 
 * User Table
 */
class PaycartTableBuyer extends PaycartTable {}