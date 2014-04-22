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
	protected $recordId ;
	public $filterMatchOpeartor = array(
										'username' 	=> array('LIKE'),
										'usertype'	=> array('=')
										);


	/**
     * Builds FROM tables list for the query
     */
    protected function _buildQueryFrom(Rb_Query &$query)
    {
    	$db			=	PayCartFactory::getDBO();
    	$table		=	$this->getTable();
    	$tname		=	$table->getTableName();

    	$queryString = '';
    	$query->_q1  = new Rb_Query();
    	$query->_q2  = new Rb_Query();
    	
    	$join = ' `#__paycart_buyer` AS t  ON ( t.`buyer_id` = joomlausertbl.`id` ) ';
    	
    	$query->_q1->select(' joomlausertbl.`id` AS buyer_id ')
    			   ->select(' joomlausertbl.`name` AS realname ')
    			   ->select(' joomlausertbl.`username` AS username ')
    			   ->select(' joomlausertbl.`email` AS email ')
    			   ->select(' joomlausertbl.`registerDate` AS register_date ')
    			   ->select(' joomlausertbl.`lastvisitDate` AS lastvisit_date ')
    			   ->select(' t.`is_registered_by_guestcheckout` AS is_registered_by_guestcheckout  ') 	 
    			   ->select(' t.`billing_address_id` AS billing_address_id  ')
    			   ->select(' t.`shipping_address_id` AS shipping_address_id  ')
    			   ->leftJoin($join);
    			   
    			   
    	$query->_q2->select(' * ')
    			   ->from(' `#__users` ')
    			   ->where(' `id` = '.$this->recordId.' ');

    	//if working on single record then append the subquery in FROM
    	if(is_numeric($this->recordId)){
    		$queryString = $query->_q1->from('( '.$query->_q2->__toString().' ) AS joomlausertbl ')->__toString();		    		
    	}
    	else {
    		$queryString = $query->_q1->from(' `#__users` AS joomlausertbl ')->__toString();
    	}
    	
	    $query->from('( '.$queryString.') AS tbl ');

    }

	//added filter for user so it is necessary to override _buildQueryFilter function here 
	//so that proper query can be build corresponding to applied filter
	protected function _buildQueryFilter(Rb_Query &$query, $key, $value)
    {
    	// Only add filter if we are working on bulk records
		if($this->getId()){
			return $this;
		}

    	JLog::add(isset($this->filterMatchOpeartor[$key]), "OPERATOR FOR $key IS NOT AVAILABLE FOR FILTER");
    	JLog::add(is_array($value), Rb_Text::_('COM_PAYINVOICE_VALUE_FOR_FILTERS_MUST_BE_AN_ARRAY'));

    	$cloneOP    = $this->filterMatchOpeartor[$key];
    	$cloneValue = $value;
    	
    	while(!empty($cloneValue) && !empty($cloneOP)){
    		$op  = array_shift($cloneOP);
    		$val = array_shift($cloneValue);

			// discard empty values
    		if(!isset($val) || '' == JString::trim($val))
    			continue;


    		if(JString::strtoupper($op) != 'LIKE'){
				if(($key == 'usertype')){
					//this subquery will fetch all the users with the desired usertype 
					$query->where("  buyer_id IN( SELECT map.`user_id` 
											     FROM `#__usergroups` as groups, `#__user_usergroup_map` as map 
											     WHERE ( map.group_id = groups.id AND groups.title = '$val'))	");
						continue;
					}
				$query->where("`$key` $op '$val'");
				continue;
			}

			// filter according to username, name and email
   			if($key == 'username'){
    	  		$query->where("( `$key` $op '%{$val}%' || `name` $op '%{$val}%' || `email` $op '%{$val}%' )");
    	  	}
	    	else {
	    	  	$query->where("`$key` $op '%{$val}%'");			
	    	}
		}
    }

	function save($data, $pk=null, $new=false)
    {
		$new = $this->getTable()->load($pk)? false : true;
		return parent::save($data, $pk, $new);
    }

   	public function loadRecords(Array $queryFilters=array(), Array $queryClean = array(), $emptyRecord=false, $indexedby = null) 
	{	
		//it is required to decide which query to execute in FROM
		// for single record or multiple record
		if(!empty($queryFilters)){
			foreach($queryFilters as $key =>$value){
				$key = ($key==='id')? $this->getTable()->getKeyName() : $key;
				if($key === 'buyer_id'){
					$this->recordId = $value;
				}
			}
		}

		$query = $this->getQuery();

		//there might be no table and no query at all
		if($query === null )
			return null;

		//Support Query Filters, and query cleanup
		$tmpQuery = clone ($query);

		foreach($queryClean as $clean){
			$tmpQuery->clear(JString::strtolower($clean));
		}

		foreach($queryFilters as $key=>$value){
			//support id too, replace with actual name of key
			$key = ($key==='id')? $this->getTable()->getKeyName() : $key;

			// only one condition for this key
			if(is_array($value)==false){
				$tmpQuery->where("`tbl`.`$key` =".$this->_db->Quote($value));
				continue;
			}

			// multiple keys are there
			foreach($value as $condition){

				// not properly formatted
				if(is_array($condition)==false){
					continue;
				}

				// first value is condition, second one is value
				list($operator, $val)= $condition;
				$tmpQuery->where("`tbl`.`$key` $operator ".$val);
			}

		}

		//we want returned record indexed by columns
		$this->_recordlist = $tmpQuery->dbLoadQuery()
		 							  ->loadObjectList($this->getTable()->getKeyName());

		//handle if some one required empty records, only if query records were null
		if($emptyRecord && empty($this->_recordlist)){
			$this->_recordlist = $this->getEmptyRecord();
		}

		$data = $this->_recordlist;

		//get usertype of the user and append it with the data
		$this->getUsertype($data);

		return $data;
	}

	public function getQuery()
	{
		//create a new query
		$this->_query = new Rb_Query();

		// Query builder will ensure the query building process
		// can be overridden by child class
		if($this->_buildQuery($this->_query))
			return $this->_query;

		//in case of errors return null
		//XITODO : Generate a 500 Error Here
		return null;
	}

	protected function getUsertype(&$buyers)
	{
		$buyer_ids = array_keys($buyers);

		//when there is nothing in users
		if(empty($buyer_ids)){
			return $buyers;
		}

		$query = new Rb_Query();

		//if only single record exists 
		if(count($buyers) == 1){
			$query->where(' usergroupmap.user_id = '.array_shift($buyer_ids));
		}

		else { 
				//in case of multiple users, user_usergroup_map table 
				//contains multiple records for a single user thats why 
				//group by with user_id is required
				$query->where(' usergroupmap.user_id IN ('.implode(',', $buyer_ids).') ')
			  		  ->group(' usergroupmap.user_id ');
		}

		$query->select('group_concat(groups.`title`) as usertype, usergroupmap.`user_id` as user_id')
			  ->from('`#__user_usergroup_map` as usergroupmap , `#__usergroups` as groups')
			  ->where(' usergroupmap.group_id = groups.id ');

		$userGroups[] = $query->dbLoadQuery()
							  ->loadObjectList('user_id');

		$groups = array_shift($userGroups);
		foreach ($buyers as $user){
			$user->usertype = $groups[$user->buyer_id]->usertype;
		}

	}
}

class PaycartModelformBuyer extends PaycartModelform { }
