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
  
class PaycartHelperlog 
{
	static $_is_logger_added 	= false;
	
	protected  $_log_category	= 'com_paycart';
	protected  $_log_filename	= 'com_paycart.logs.php';
	
	/**
	 * Add logger file
	 * 
	 * 
	 * @return  void
	 * 
	 * @since	1.0
	 * @author	Manish Trivedi
	 */
	public function addLogger()
	{
		if (self::$_is_logger_added) {
			return true;
		}
		
		JLog::addLogger(
	       array(
	            // Sets file name
	            'text_file' => $this->_log_filename,
	       		// Sets the format of each line
	            'text_entry_format' => '{DATETIME} {PRIORITY}  {CLIENTIP}  {MESSAGE}'
	       ),
	       // Sets messages of all log levels to be sent to the file
	       JLog::ALL,
	       // The log category/categories which should be recorded in this file
	       // In this case, it's just the one category from our extension, still
	       // we need to put it inside an array
	       array($this->_log_category)
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
	public function getString($message) 
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
	public function add($entry, $priority = JLog::INFO, $category = '' , $date = 'now')
	{
		if (!$category) {
			$category	=	$this->_log_category;
		}
		
		$this->addLogger();
		
		// @PCTODO :: use PCDEBUG instead of JDEBUG
		if (defined('JDEBUG') && JDEBUG) {
			JLog::add( $this->getString( $responseData), $priority, $category, $date);
		}
	}
	
}
