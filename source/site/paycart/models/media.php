<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Media Model
 * @author rimjhim
 *
 */
class PaycartModelMedia extends PaycartModel
{
//	/**
//	  * returns single record
//	  */
//	function loadRecord($languageCode, $mediaId)
//	{
//		$query = new Rb_Query();
//		
//		return $query->select('*')
//		 		     ->from('#__paycart_media as m')
//		 		     ->join('INNER', '#__paycart_media_lang as ml ON m.media_id = ml.media_id')
//		 		     ->where('ml.lang_code = "'.$languageCode.'"')
//		 		     ->where('ml.media_id = '.$mediaId)
//		 		     ->dbLoadQuery()
//		 		     ->loadAssocList();
//	}
//	
//	function save($data, $pk=null, $new=false)
//	{
//		$new = $this->getTable()->load($pk)?false:true;
//		$id  = parent::save($data, $pk,$new);
//		
//		if(!$id){
//			return false;
//		}
//		
//		$data['media_id'] = $id;
//		PaycartFactory::getModelLang($this->getName())->save($data);		
//		return $id;
//	}
//	
//	function deleteRecords($filters)
//	{
//		$query = new Rb_Query();
//		$query->multiDelete($this->getTable()->getTableName()." as tbl ",'tbl');
//		$this->_buildWhereClause($query, $filters);
//		return $query->dbLoadQuery()->execute();
//	}
}

/**
 * 
 * Media language Model
 * @author rimjhim
 *
 */
class PaycartModelLangMedia extends PaycartModel{}
