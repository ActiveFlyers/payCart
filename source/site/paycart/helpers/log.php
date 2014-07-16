<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		support+paycart@readybytes.in
 * @author 		mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Log Helper
 * @author 	mManishTrivedi
 */
define('PAYCART_LOG_CATEGORY', 'com_paycart' );
define('PAYCART_LOG_FILE', 'com_paycart.logs.php' );
  
class PaycartHelperlog 
{
	static $_is_logger_added = false;
	
	/**
	 * Add logger file
	 * 
	 * 
	 * @return  void
	 * 
	 * @since	1.0
	 * @author	Manish Trivedi
	 */
	public static function addLogger()
	{
		if (self::$_is_logger_added) {
			return true;
		}
		
		JLog::addLogger(
	       array(
	            // Sets file name
	            'text_file' => PAYCART_LOG_FILE,
	       		// Sets the format of each line
	            'text_entry_format' => '{DATETIME} {PRIORITY}  {CLIENTIP}  {MESSAGE}'
	       ),
	       // Sets messages of all log levels to be sent to the file
	       JLog::ALL,
	       // The log category/categories which should be recorded in this file
	       // In this case, it's just the one category from our extension, still
	       // we need to put it inside an array
	       array(PAYCART_LOG_CATEGORY)
   		);
   		
   		self::$_is_logger_added = true;
	} 	
	
	
	/**
	 * 
	 * Invoke to convert any data-type to string 
	 * @param $message
	 * 
	 * @return string
	 * 
	 * @since	1.0
	 * @author	Manish Trivedi
	 */
	static function getString($message) 
	{
		ob_start();
		var_export($message);
		
		$content = ob_get_contents(); 
		ob_end_clean();
		return $content;
	}
	
	/**
	 * Method to add an entry to the log.
	 *
	 * @param   mixed    $entry     The JLogEntry object to add to the log or the message for a new JLogEntry object.
	 * @param   integer  $priority  Message priority.
	 * @param   string   $category  Type of entry
	 * @param   string   $date      Date of entry (defaults to now if not specified or blank)
	 *
	 * @return  void
	 *
	 * @since   1.1
	 */
	public static function add($entry, $priority = JLog::INFO, $category = PAYCART_LOG_CATEGORY , $date = null)
	{
		self::addLogger();
		// @PCTODO :: use PCDEBUG instead of JDEBUG
		if (defined('JDEBUG') && JDEBUG) {
			JLog::add( self::getString( $responseData), $priority, $category);
		}
		
	}

	
	
}
