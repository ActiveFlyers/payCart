<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Model for Group
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartModelGroup extends PaycartModel 
{	
	public $filterMatchOpeartor = array(
									'title' 	=> array('LIKE'),
									'published' => array('LIKE'),
									'type'		=> array('='),
								);
}


/**
 * Modelform for Group
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartModelformGroup extends PaycartModelform 
{}


/**
 * Table for Group
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartTableGroup extends PaycartTable 
{}