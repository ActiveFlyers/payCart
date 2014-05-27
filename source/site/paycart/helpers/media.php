<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	paycartHelper
 * @contact		support+paycart@readybytes.in
 * @author 		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Image Helper
 * @author rimjhim
 */
class PaycartHelperMedia extends PaycartHelper
{
	/**
	 * upload file 
	 * @param $file : array containing file information
	 * @return array containing file path and extension of file
	 */
	function upload($file = array())
	{
		$details  = array();
		$filename = $file['name'];
					
		$info  	   = pathinfo($filename);
		$extension = isset($info['extension']) ? $info['extension'] : null; 
		$mediaFile = PAYCART_ATTRIBUTE_PATH_MEDIA . PaycartHelper::getHash($filename) . '.' . $extension;
		
		if(!JFile::upload($file['tmp_name'],$mediaFile)) {
			throw new RuntimeException(Rb_Text::sprintf('COM_PAYCART_FILE_COPY_FAILED', $mediaFile));
		}

		$details['path'] = $mediaFile;
		$details['type'] = $extension;
		return $details;
	}
	
	/**
	 * Delete media records
	 * @param $mediaIds
	 * @param $path : path where files exist
	 */
	function deleteFiles(Array $mediaIds = array(), $path = PAYCART_ATTRIBUTE_PATH_MEDIA)
	{
		//if empty, do nothing
		if(empty($mediaIds)){
			return true;
		}
		
		$condition    = array('media_id' => array(array('IN',"(".implode(',',$mediaIds).")")));
		$model 		  = PaycartFactory::getModel('media'); 
		$mediaRecords = $model->loadRecords($condition);
		$files		  = array();
		
		//delete records from db
		if($model->deleteRecords($condition)){
			foreach ($mediaRecords as $record){
				$filePath = $path.$record->path;
				if(JFile::exists($filePath)){
					$files[] = $filePath;
				}	
			}
		}
		
		//Now delete files from actual path 
		return JFile::delete($files);
	}
}