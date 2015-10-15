<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Paycart
* @subpackage	cartabandonment
* @contact 		support+paycart@readybytes.in
* @author	rimjhim
*/
if(defined('_JEXEC')===false) die();

class PaycartTablecartabandonment extends Rb_Table
{
	public function __construct($tblFullName=null, $tblPrimaryKey='cartabandonment_id', $db=null)
	{
		static $isTableExist = false;
		if(!$isTableExist && Rb_HelperUtils::isTableExist('#__paycart_cartabandonment')==false){
			$this->execute_sql(dirname(__FILE__).'/prepopulate.sql');
		}
	
		return parent::__construct($tblFullName, 'cartabandonment_id', $db);
	}
	
	function execute_sql($sqlFile)
	{
		$db 	= PaycartFactory::getDBO();
		$buffer = file_get_contents($sqlFile);
		// Graceful exit and rollback if read not successful
		if ($buffer === false)
		{
			JLog::add(JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER'), JLog::WARNING, 'jerror');
			return false;
		}
		
		// Create an array of queries from the sql file
		$queries = JDatabase::splitSql($buffer);
		if (count($queries) == 0)
		{
			// No queries to process
			return 0;
		}
		// Process each query in the $queries array (split out of sql file).
		foreach ($queries as $query)
		{
			$query = trim($query);
			if ($query != '' && $query{0} != '#')
			{
				$db->setQuery($query);
				if (!$db->execute())
				{
					JLog::add(JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)), JLog::WARNING, 'jerror');
					return false;
				}
			}
		}
		return true;
	}
 }
 
 class PaycartTablecartabandonmentlang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_cartabandonment_lang', $tblPrimaryKey='cartabandonment_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}
