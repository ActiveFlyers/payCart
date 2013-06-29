<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Category Model
 */
class PaycartModelCategory extends PaycartModel
{
	var $filterMatchOpeartor = Array(
									'alias' => array('LIKE')
									);
	/**
	 * Translate alias to id.
	 *
	 * @param string $alias The alias string
	 *
	 * @return numeric value The category id if found, or false/empty
	 */
	public function translateAliasToID($alias) 
	{
		$this->clearQuery(); 
		$query = $this->getQuery();
		
		$this->_buildQueryFilter( $query, 'alias', $alias);
		$result =  $this->loadRecords();
		
		return @($result->category_id);
	}
}

class PaycartModelformCategory extends PaycartModelform { }