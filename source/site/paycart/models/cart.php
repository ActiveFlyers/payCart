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
									'paid_date' => array('>=','<='),
									'cart_id'	=> array('='),
									'price'		=>array('>=','<='),
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
			$value = trim(array_shift($filters['buyer_id']));
    		if(!empty($value)){
    			$operator  = array_shift($this->filterMatchOpeartor['buyer_id']); 
    			$condition = "( `username` $operator '%{$value}%' || `name` $operator '%{$value}%' || `email` $operator '%{$value}%' )";
    			$query->innerJoin('`#__users` as usr on tbl.`buyer_id` = usr.id and '.$condition);
    		}
		}
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
		$data = array('price');
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
	
	/**
     * (non-PHPdoc)
     * @see components/com_paycart/paycart/base/PaycartModelLang::_buildQueryFilter()
     * 
     * Overridden it to handle filter other than the main table
     */
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
    	
    	//in case of price
    	if($key == 'price'){
    		$condition = array();
    		
    		while(!empty($cloneValue) && !empty($cloneOP)){
	    		$op  = array_shift($cloneOP);
	    		$val = trim(array_shift($cloneValue));
	    		
	    		// discard empty values
    			if(!isset($val) || '' == $val)
    				continue;
   			    		
    			$condition[] =  "SUM(cp.`total`) {$op} {$val} ";
    		}
    		
    		if(!empty($condition)){
    			$query->where(" `tbl`.`cart_id` IN( SELECT cp.cart_id FROM `#__paycart_cartparticular` AS cp GROUP BY cp.cart_id 
	    											HAVING ".implode(' AND ', $condition).")");
    		}
    		return;
    	}
    	
    	while(!empty($cloneValue) && !empty($cloneOP)){
    		$op  = array_shift($cloneOP);
    		$val = trim(array_shift($cloneValue));

			// discard empty values
    		if(!isset($val) || '' == $val)
    			continue;
    			
    		//trim value before adding it to condition
    		$val = trim($val);
    		
    		if(strtoupper($op) == 'LIKE'){
	    	  	$query->where("$tblAlias`$key` $op '%{$val}%'");
				continue;
	    	}

    		$query->where("$tblAlias`$key` $op '$val'");
    	}
    }
    
    function loadLastPaidCart($userId)
    {
    	$query  = new Rb_Query();
    	
    	return $query->select('*')
    		            ->from($this->getTable()->get('_tbl'))
    		            ->where('status = "'.Paycart::STATUS_CART_PAID.'"')
    		            ->where('buyer_id = '.$userId)
    		            ->order('cart_id DESC')
    		            ->limit('1')
    		            ->dbLoadQuery()
    		            ->loadObject();    
    }
    
	function loadLastUnpaidCart($userId)
    {
    	$query  = new Rb_Query();
    	
    	return $query->select('*')
    		            ->from($this->getTable()->get('_tbl'))
    		            ->where('status = "'.Paycart::STATUS_CART_DRAFTED.'"')
    		            ->where('is_locked = 0')
    		            ->where('is_approved = 0')
    		            ->where('buyer_id = '.$userId)
    		            ->order('cart_id DESC')
    		            ->limit('1')
    		            ->dbLoadQuery()
    		            ->loadObject();    	   
    }
}

class PaycartModelformCart extends PaycartModelform { }

/** 
 * Cart Table
 */
class PaycartTableCart extends PaycartTable {}
