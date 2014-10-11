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

// load bootsrap, font-awesome
Rb_HelperTemplate::loadMedia(array('jquery', 'bootstrap', 'rb', 'font-awesome'));
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/js/paycart.js');

// get layout name
$layout           = $params->get('layout', 'default');

require JModuleHelper::getLayoutPath('mod_paycart_menu', $layout);
