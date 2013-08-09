<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


JFormHelper::loadFieldClass('list');

class PaycartFormFieldAttributeType extends JFormFieldList
{	
	/**
	 * (non-PHPdoc)
	 * @see libraries/joomla/form/fields/JFormFieldList::getOptions()
	 */
	public function getOptions()
	{
		$attributeType	= Array ('checkbox', 'list',  'radio', 'text' );
		return PaycartHtml::buildOptions($attributeType);		
	}
	
}