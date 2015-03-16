<?php
/**
 * @package     Paycart.Site
 * @subpackage  mod_paycart_cart
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * @author mMnaishTrivedi
 */

defined('_JEXEC') or die;

$file_path = JPATH_ROOT.'/components/com_paycart/paycart/api.php';

if ( !JFile::exists($file_path)) {
	echo JText::_("MOD_PAYCART_MENU_PAYCART_MISSING");
	return ;
}

include_once $file_path;

$categories		= PaycartAPI::getCategories();
$loggin_user 	= PaycartFactory::getUser();  
$isMobile = PaycartFactory::getApplication()->client->mobile;

$return_link = isset($displayData->return_link) ? $displayData->return_link : 'index.php';
$return_link	= 	base64_encode($return_link);

// get layout name
$layout           = $params->get('layout', 'default');
$itemsPerColumn	  = $params->get('itemsPerColumn',10);
$class_sfx		  = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_paycart_menu', $layout);
