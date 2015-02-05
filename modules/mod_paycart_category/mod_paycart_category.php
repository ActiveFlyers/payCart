<?php
/**
 * @package     Paycart.Site
 * @subpackage  mod_paycart_cart
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * @author support+paycart@readybytes.in
 */

defined('_JEXEC') or die;

$file_path = JPATH_ROOT.'/components/com_paycart/paycart/api.php';

if ( !JFile::exists($file_path)) {
	echo JText::_("MOD_PAYCART_CATEGORY_PAYCART_MISSING");
	return ;
}

include_once $file_path;

// load bootsrap, font-awesome
Rb_HelperTemplate::loadMedia(array('jquery', 'bootstrap', 'rb', 'font-awesome'));
Rb_HelperTemplate::loadSetupEnv();
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/paycart.js');
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/owl.carousel.js');
Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/owl.carousel.css');

// get layout name
$layout           = $params->get('layout', 'default');

$selected_categories = $params->get('selected_categories', '');

if(empty($selected_categories)){
	$startLevel = $params->get('start_level', 1);
	$endLevel 	= $params->get('end_level', 0); // default is all
	$parent_cat = $params->get('parent_category', 1);
	$parent_cat = PaycartAPI::getCategory($parent_cat, false); // no need to get instance
	
	$filter = array();
	$filter['lft'] = array(array('>', $parent_cat['lft']));
	$filter['rgt'] = array(array('<', $parent_cat['rgt']));
	$filter['level'][] = array('>=', $startLevel);
	if(!empty($endLevel)){
		$filter['level'][] = array('<=', $endLevel);
	}
	
	$order_by = $params->get('order_by', 'ordering');
	if($order_by == 'oldest'){
		$order_by = 'created_date';
		$order_in = 'ASC';
	}
	elseif($order_by == 'latest'){
		$order_by = 'created_date';
		$order_in = 'DESC';
	}
	else{
		$order_by = 'lft';
		$order_in = 'ASC';
	}
	
	$limit = intval($params->get('limit', 0));	
}
else{
	$filter = array();
	$filter['productcategory_id'] = array(array('IN', '('.$selected_categories.')'));
	$order_by = null;
	$order_in = null;
	$limit = null;
}

//die(var_dump($filter));
$categories		= PaycartAPI::getCategories($filter, null, $order_by, $order_in, $limit);

require JModuleHelper::getLayoutPath('mod_paycart_category', $layout);

