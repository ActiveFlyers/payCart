<?php
/**
 * @package     Paycart
 * @subpackage  Paycart.plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      mManishTrivedi 
 */

defined('_JEXEC') or die;

/**
 * PayCart! Promotion Plugin
 *
 * @package     Paycart
 * @subpackage  Paycart.plugin
 * @author 		manish
 *
 */
class PaycartDiscountRuleProcessorFlat extends PaycartDiscountRuleProcessor 
{
	/**
	 * (non-PHPdoc)
	 * @see /components/com_paycart/paycart/discountrule/PaycartDiscountRuleProcessor::getConfigHtml()
	 */
	public function getConfigHtml(PaycartDiscountRuleRequest $request, PaycartDiscountRuleResponse $response)
	{
		$applyOn = $this->rule_config->apply_on;

		ob_start();
		
		include_once 'tmpl/config.php';
		
		$html = ob_get_contents();
		ob_end_clean();
		
		$response->configHtml =  $html;
					
		return $response;
	}
}

