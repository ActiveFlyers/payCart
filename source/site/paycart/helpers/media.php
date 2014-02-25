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
		$details = array();
		
		if(!empty($file['name'])) {
			$path 	 = Paycart::ATTRIBUTE_PATH_MEDIA;
			
			if(empty($filename))continue;	
			

			//joomla pathinfo
			$info  = pathinfo($filename);			
			//$extension = self::getExtension($filename);
			$mediaFile = $path . PaycartHelper::getHash($filename) . $info['extension'];
			
			if(!JFile::upload($file['tmp_name'][$id],$mediaFile)) {
				throw new RuntimeException(Rb_Text::sprintf('COM_PAYCART_FILE_COPY_FAILED', $mediaFile));
			}

			$details['path'] = $mediaFile;
			$details['type'] = isset($info['extension']) ? $info['extension'] : null;
		}
		
		return $details;
	}
	
	/**
	 * rearrage media files according to indexes
	 * @param $media post data 
	 */
	function rearrangeFiles($media)
	{
		$result = array();
		foreach ($media as $key => $values){
			foreach ($value as $attrId => $data){
				$result[$attrId][$key] = array_values($data);
			}
		}
		return $result;
	}
}