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
		return PaycartHtml::buildOptions(self::getAttributeList());		
	}
	
	//@PCTODO:: Should be moved to helper if attribute inject from outside
	private function getAttributeList()
	{
		$files	=	JFolder::files(PAYCART_PATH_CUSTOM_ATTRIBUTES,".php$");
		$fileList = Array();
		// load all attributes configuration
		foreach ($files as $file ) {
			$name = JFile::stripExt($file);
			$fileList[$name] = JString::ucwords($name);
		}
		
		return $fileList;
	}
	
}