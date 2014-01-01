<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Taxrule Model
 * @author rimjhim
 */

class PaycartModelTaxrule extends PaycartModel
{
	/**
	 * Filter applicable rules from applicable rules 
	 * @param array $ruleIds
	 * @param string $applyOn
	 * @param string $orderBy
	 */
	function filterApplicableRules($ruleIds, $applyOn, $orderBy)
	{
	 	$condition = '`taxrule_id IN('.array_values($ruleIds).')` AND '.
	 				 '`published` = 1 AND '.
                     '`apply_on` LIKE '."'$applyOn'" ;
	 	
	 	$query     = new Rb_Query();
	 	
	 	return $query->select('*')
		 		     ->from('#__paycart_taxrule')
		 		     ->where($conditions)
		 		     ->order($orderBy.' DESC ')
		 		     ->dbLoadQuery()
		 		     ->loadObjectList($this->getTable()->getKeyName()); 	
	 	
	}
}

/**
 * 
 * Model form for taxrule
 * @author rimjhim
 *
 */
class PaycartModelformTaxrule extends PaycartModelform{ }

/**
 * 
 * Taxrule language model
 * @author rimjhim
 *
 */
class PaycartModelTaxruleLang extends PaycartModel{ }

/**
 * 
 * Taxrule-group mapping model
 * @author rimjhim
 *
 */
class PaycartModelTaxruleXGroup extends PaycartModel{ }
