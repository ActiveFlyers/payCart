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

// get layout name
$layout           = $params->get('layout', 'default');

$selected_products = $params->get('selected_products', '');

if(empty($selected_products)){
	$parent_cat = $params->get('parent_categories', array());	
	
	$filter = array();
	if(!empty($parent_cat)){
		$filter['productcategory_id'] = array(array('IN', '('.implode(',', $parent_cat).')'));
	}
	
	$filter['visible'] = true;

	$order_by = $params->get('order_by', 'ordering');
	if($order_by == 'oldest'){
		$order_by = 'created_date';
		$order_in = 'ASC';
	}
	elseif($order_by == 'hits'){
		$order_by = 'hits';
		$order_in = 'DESC';
	}
	elseif($order_by == 'latest'){
		$order_by = 'created_date';
		$order_in = 'DESC';
	}
	else{
		$order_by = 'ordering';
		$order_in = 'ASC';
	}
	
	$limit = intval($params->get('limit', 0));	
}
else{
	$filter = array();
	$filter['product_id'] = array(array('IN', '('.$selected_products.')'));
	$order_by = null;
	$order_in = null;
	$limit = null;
}

//die(var_dump($filter));
$products		= PaycartAPI::getProducts($filter, null, $order_by, $order_in, $limit);

require JModuleHelper::getLayoutPath('mod_paycart_product', $layout);

