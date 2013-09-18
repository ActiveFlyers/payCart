<?php
/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/

/** 
 * Null DataSet
 * It Should be loaded after PHPUnit
 * @author mManishTrivedi
 */


class PHPUnit_Extensions_Database_DataSet_NullDataSet extends PHPUnit_Extensions_Database_DataSet_AbstractDataSet 
{
    /**
     * @var array
     */
    protected $tables = array();
 
    
    protected function createIterator($reverse = FALSE)
    {
        return $this->tables;
    }

}
