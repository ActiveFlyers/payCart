<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
* @author		Manish Trivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );
/**
 * 
 * Define all Paycart global stuff like Constant, variables etc.
 * How to use this class :
 * About Constant : 
 *		# Define as class variable
 *		# define like ENTITY_SUBENTITY_SPECIFICATION
 *		# Language String should be COM_PAYCART_ENTITY_SUBENTITY_SPECIFICATION
 *
 * @author Manish,Puneet
 *
 */
class Paycart 
{
	########	Define Here Product Constant	#########
	const PRODUCT_TYPE_PHYSICAL	=	10;		// langusage String "COM_PAYCART_PRODUCT_TYPE_PHYSICAL"
	const PRODUCT_TYPE_DIGITAL	=	20;		
	
	// Image constant	
	const IMAGES_ROOT_PATH					= '/media/com_paycart/images/';
	const IMAGE_ORIGINAL_PREFIX 			= 'original_';
	const IMAGE_ORIGINAL_SUFIX 				= '.orig';
	const IMAGE_OPTIMIZE_WIDTH 				= 200;
	const IMAGE_OPTIMIZE_HEIGHT				= 200;
	const IMAGE_THUMB_PREFIX 				= 'thumb_';
	const IMAGE_THUMB_WIDTH 				= 133;
	const IMAGE_THUMB_HEIGHT				= 100;
	const IMAGE_FILE_DEFAULT_MAXIMUM_SIZE 	= 2; 	// 2MB
	const IMAGE_FILE_DEFAULT_EXTENSION 		= '.png';

	const PRODUCT_FILTER_FIELD_PREFIX	= 'attr_';

	// Cart Entity Status
	const STATUS_CART_DRAFT		= 	'draft';		// none
	const STATUS_CART_CHECKOUT	=	'checkout';
	const STATUS_CART_PAID 		=	'paid';
	const STATUS_CART_CANCEL 	=	'cancel';
	const STATUS_CART_COMPLETE 	=	'complete';
	
	
	// JOOMLA System Message Type
	const MESSAGE_TYPE_MESSAGE	= 'message'; 
	const MESSAGE_TYPE_WARNING	= 'warning';
	const MESSAGE_TYPE_NOTICE	= 'notice';
	const MESSAGE_TYPE_ERROR	= 'error';
	
	//Processor type
	const PROCESSOR_TYPE_DISCOUNTRULE = 'discountrule';
	const PROCESSOR_TYPE_TAXRULE   	  = 'taxrule';
	const PROCESSOR_TYPE_SHIPPINGRULE = 'shippingrule';
	
	//Paycart Status
	const STATUS_PUBLISHED 		= 'published';		// Content publish for end-user
	const STATUS_TRASHED 		= 'trashed';		// Trashed content 
	const STATUS_UNPUBLISHED 	= 'unpublished';	// Unpublish content. Visible only backend
	const STATUS_INVISIBLE		= 'invisible';		// Not listed in front-end, access by url
	//@PCTODO :: admin or owner
	const STATUS_ADMIN	 		= 'admin';			// visible only for admin-user (at front-end)
		

	// shipping rule
	const SHIPPINGRULE_LIST_ORDER_BY_PRICE 		= 'price';
	const SHIPPINGRULE_LIST_ORDER_BY_ORDERING 	= 'ordering';
	const SHIPPINGRULE_LIST_ORDER_IN_ASC 		= 'ASC';
	const SHIPPINGRULE_LIST_ORDER_IN_DESC		= 'DESC';


	// Cart Particulars Type
	const CART_PARTICULAR_TYPE_PRODUCT 			= 'product';
	const CART_PARTICULAR_TYPE_PROMOTION	 	= 'promotion';
	const CART_PARTICULAR_TYPE_DUTIES 			= 'duties';
	const CART_PARTICULAR_TYPE_SHIPPING			= 'shipping';
	// @Future Purpose
//	const CART_PARTICULAR_TYPE_SHIPPING_PROMOTION	= 'shippingpromotion';
	const CART_PARTICULAR_TYPE_ADJUSTMENT			= 'adjustment';

	const CART_PARTICULAR_QUANTITY_MINIMUM = 1;

	const CHECKOUT_SEQUENCE_OPTION_VALUE_TAX_DISCOUNT = 'td';
	const CHECKOUT_SEQUENCE_OPTION_VALUE_DISCOUNT_TAX = 'dt';
	
	const GROUPRULE_TYPE_PRODUCT = 'product';
	const GROUPRULE_TYPE_BUYER 	 = 'buyer';

	const PRODUCTCATEGORY_ROOT_ID		 = 1;
}
