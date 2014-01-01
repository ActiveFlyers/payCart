<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Taxrule Helper
 * @author rimjhim
 */
class PaycartHelperTaxrule
{
	/**
	 * Process tax rules
	 * @param $ruleIds
	 * @param $entity
	 * @param $applyOn
	 */
	public function processRules($ruleIds, $entity, $applyOn)
	{   
		   if(empty($ruleIds)){
		   		return true;
		   }
		
           $records = PaycartFactory::getModel('taxrule')->filterApplicableRules($ruleIds, $applyOn,'ordering');

           //do nothing if record doesn't exist
           if (!$records) {
                return true;
           }
                
          //PCTODO: Should we process response here or in lib
          foreach ($records as $id=>$record) {
              $taxRule  = PaycartTaxrule::getInstance($id, $record);
              $response = $taxRule->process($entity);
          }
                
          return true;
	}
}