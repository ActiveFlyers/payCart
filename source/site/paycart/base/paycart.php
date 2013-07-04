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
 * @author Manish Trivedi
 *
 */
class Paycart 
{
	########	Define Here Product Constant	#########
	const PRODUCT_TYPE_PHYSICAL	=	10;		// langusage String "COM_PAYCART_PRODUCT_TYPE_PHYSICAL"
	const PRODUCT_TYPE_DIGITAL	=	20;		
	
	const PRODUCT_IMAGES_PATH		= '/media/com_paycart/images/products/';
	const CATEGORY_IMAGES_PATH		= '/media/com_paycart/images/categories/';
	
	// Image constant
	//const PRODUCT_IMAGE_FOLDER_PREFIX	= 'product_';
	//const CATEGORY_IMAGE_FOLDER_PREFIX	= 'category_';
	//const OPTIMIZE_IMAGE_PREFIX 	= 'optimize_';
	
	const ORIGINAL_IMAGE_PREFIX 	= 'original_';
	
	const THUMB_IMAGE_PREFIX 	= 'thumb_';
	const THUMB_IMAGE_WIDTH 	= 200;
	const THUMB_IMAGE_HEIGHT	= 200;
	
	const IMAGE_FILE_DEFAULT_MAXIMUM_SIZE = 2; 	// 2MB
	const IMAGE_FILE_DEFAULT_EXTENSION = '.png';
}
