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
	const CART_STATUS_NONE 		= 	0;
	const CART_STATUS_CHECKOUT	=	5000;
	const CART_STATUS_PAID		=	5001;
	const CART_STATUS_SHIPPED	=	5002;
	const CART_STATUS_DELIVERED	=	5003;
	const CART_STATUS_CANCEL	=	5004;
	const CART_STATUS_REFUND	=	5005;
	const CART_STATUS_REVERSAL	=	5006;
	const CART_STATUS_COMPLETE	=	5007;
	
	//Processor type constants
	const PROCESSOR_TYPE_TAX    = 'tax';
	
	//Message type constants
	const MESSAGE_TYPE_MESSAGE  = "message";
}
