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
	const MEDIA_PATH_ROOT					= PAYCART_PATH_CORE_IMAGES;
	
	const MEDIA_THUMB_FOLDER_NAME			= 'thumbs';
	const MEDIA_OPTIMIZED_FOLDER_NAME		= 'optimized';
	const MEDIA_SQUARED_FOLDER_NAME			= 'squared';

	const PRODUCT_FILTER_FIELD_PREFIX	= 'attr_';

	// Cart Entity Status
	const STATUS_CART_DRAFTED	=	'drafted';		// none, Just created cart
	const STATUS_CART_PAID 		=	'paid';
	const STATUS_CART_CANCELLED	=	'cancelled';
	
	//shipment status
	const STATUS_SHIPMENT_PENDING		= 'pending';
	const STATUS_SHIPMENT_DISPATCHED	= 'dispatched';
	const STATUS_SHIPMENT_DELIVERED		= 'delivered';
	const STATUS_SHIPMENT_FAILED		= 'failed';
	
	// JOOMLA System Message Type
	const MESSAGE_TYPE_MESSAGE	= 'message'; 
	const MESSAGE_TYPE_WARNING	= 'warning';
	const MESSAGE_TYPE_NOTICE	= 'notice';
	const MESSAGE_TYPE_ERROR	= 'error';
	
	//Processor type
	const PROCESSOR_TYPE_DISCOUNTRULE = 'discountrule';
	const PROCESSOR_TYPE_TAXRULE   	  = 'taxrule';
	const PROCESSOR_TYPE_SHIPPINGRULE = 'shippingrule';
		

	// shipping rule
	const SHIPPINGRULE_LIST_ORDER_BY_PRICE 		= 'price';
	const SHIPPINGRULE_LIST_ORDER_BY_ORDERING 	= 'ordering';
	const SHIPPINGRULE_LIST_ORDER_IN_ASC 		= 'ASC';
	const SHIPPINGRULE_LIST_ORDER_IN_DESC		= 'DESC';
	const SHIPPING_BEST_IN_PRICE				= 'price';
	const SHIPPING_BEST_IN_TIME					= 'time';


	// Cart Particulars Type
	const CART_PARTICULAR_TYPE_PRODUCT 			= 'product';
	const CART_PARTICULAR_TYPE_PROMOTION	 	= 'promotion';
	const CART_PARTICULAR_TYPE_DUTIES 			= 'duties';
	const CART_PARTICULAR_TYPE_SHIPPING			= 'shipping';
	const CART_PARTICULAR_TYPE_SHIPPING_PROMOTION	= 'shippingpromotion';
	const CART_PARTICULAR_TYPE_ADJUSTMENT			= 'adjustment';

	const CART_PARTICULAR_QUANTITY_MINIMUM = 1;
	
	const GROUPRULE_TYPE_PRODUCT = 'product';
	const GROUPRULE_TYPE_BUYER 	 = 'buyer';
	const GROUPRULE_TYPE_CART 	 = 'cart';

	const ATTRIBUTE_PATH_MEDIA	= '/media/com_paycart/media/';
	
	const RULE_APPLY_ON_PRODUCT 	= 'product';
	const RULE_APPLY_ON_CART 		= 'cart';
	const RULE_APPLY_ON_SHIPPING	= 'shipping';

	const PRODUCTCATEGORY_ROOT_ID		 = 1;
	
	const CHECKOUT_STEP_LOGIN		=	'login';
	const CHECKOUT_STEP_ADDRESS		=	'address';
	const CHECKOUT_STEP_CONFIRM		=	'confirm';
	const CHECKOUT_STEP_PAYMENT		=	'payment';
	
	const WEIGHT_UNIT_KILOGRAM		= 'kg';
	const WEIGHT_UNIT_GRAM			= 'gm';
	const WEIGHT_UNIT_PONUD			= 'lb';
	const WEIGHT_UNIT_OUNCE			= 'oz';	
	const DEFAULT_WEIGHT_UNIT 		= 'gm';	
	
	const DIMENSION_UNIT_CENTIMETER	= 'cm';
	const DIMENSION_UNIT_METER		= 'm';
	const DIMENSION_UNIT_INCH		= 'in';
	const DEFAULT_DIMENSION_UNIT	= 'cm';
	
	const OPERATOR_AND				= "AND";
	const OPERATOR_OR				= "OR";
	
	const SORTING_OPTION_HITS         = 'hits';
	const SORTING_OPTION_CREATED_DATE = 'created_date';
	const SORTING_OPTION_PRICE_LOW    = 'price_low';
	const SORTING_OPTION_PRICE_HIGH   = 'price_high';
	
	const LIMIT_COUNTRY_IMPORT	= 5;
	
	const LIMIT_IMPORT_EXPORT	= 20;
	/**
	 * 
	 * Return All available shipment status
	 * 
	 * @return Array()
	 */
	public static function getShipmentStatus()
	{
		return 
			Array( 
					array('title'=>JText::_('COM_PAYCART_SHIPMENT_STATUS_PENDING'), 'value' => Paycart::STATUS_SHIPMENT_PENDING),
					array('title'=>JText::_('COM_PAYCART_SHIPMENT_STATUS_DISPATCHED'), 'value' => Paycart::STATUS_SHIPMENT_DISPATCHED),
					array('title'=>JText::_('COM_PAYCART_SHIPMENT_STATUS_DELIVERED'), 'value' => Paycart::STATUS_SHIPMENT_DELIVERED),
					array('title'=>JText::_('COM_PAYCART_SHIPMENT_STATUS_FAILED'), 'value' => Paycart::STATUS_SHIPMENT_FAILED)
			     );
	}
	
	/**
	 * 
	 * Return All available cart status
	 * 
	 * @return Array()
	 */
	public static function getCartStatus()
	{
		return 
			Array(
                Paycart::STATUS_CART_DRAFTED   => 'COM_PAYCART_CART_STATUS_DRAFTED',
				Paycart::STATUS_CART_PAID      => 'COM_PAYCART_CART_STATUS_PAID',
				Paycart::STATUS_CART_CANCELLED => 'COM_PAYCART_CART_STATUS_CANCELLED',
			 );
	}
	
	/**
	 * Return All available Sorting Options
	 */
	public static function getSortingOptions()
	{
		return Array( 	Paycart::SORTING_OPTION_HITS         => JText::_('COM_PAYCART_SORT_POPULARITY'),
						Paycart::SORTING_OPTION_CREATED_DATE => JText::_('COM_PAYCART_SORT_NEW'),
 						Paycart::SORTING_OPTION_PRICE_LOW    => JText::_('COM_PAYCART_SORT_PRICE_LOW'),
						Paycart::SORTING_OPTION_PRICE_HIGH   => JText::_('COM_PAYCART_SORT_PRICE_HIGH'),
						);
	}
	
	/**
	 * 
	 * Return All available group rule types
	 * 
	 * @return Array()
	 */
	public static function getGroupruleType()
	{
		return 
			Array(
                Paycart::GROUPRULE_TYPE_PRODUCT   => JText::_('COM_PAYCART_ADMIN_GROUPRULE_TYPE_PRODUCT'),
				Paycart::GROUPRULE_TYPE_CART      => JText::_('COM_PAYCART_ADMIN_GROUPRULE_TYPE_CART'),
				Paycart::GROUPRULE_TYPE_BUYER     => JText::_('COM_PAYCART_ADMIN_GROUPRULE_TYPE_BUYER'),
			 );
	}
	
	/**
     * set a constant that will be used to stop execution
     */
	public static function markExit($msg='NO_MESSAGE')
	{
		// if not already set
		if(defined('PAYCART_EXIT')==false){
			define('PAYCART_EXIT',$msg);
			return true;
		}

		//already set
		return false;
	}
}
