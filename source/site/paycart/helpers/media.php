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
	
	/**
	 * calculate upload limit using the php configuration
	 */
	function getUploadLimit()
	{
		$max_upload   = $this->_changeToBytes(ini_get('upload_max_filesize'));
		$max_post     = $this->_changeToBytes(ini_get('post_max_size'));
		$memory_limit = $this->_changeToBytes(ini_get('memory_limit'));
		return min($max_upload, $max_post, $memory_limit);
	
	}
	
	/**
	 * change the given php ini value to bytes
     */
	private function _changeToBytes($size_str)
	{
		switch (substr ($size_str, -1))
	    {
	        case 'M': case 'm': return (int)$size_str * 1048576;
	        case 'K': case 'k': return (int)$size_str * 1024;
	        case 'G': case 'g': return (int)$size_str * 1073741824;
	        default: return $size_str;
	    }
	}
	
    /**
     * stream download of any media
     */
	function download(PaycartMedia $media, $main_file = true)
	{
		$app			= JFactory::getApplication();
		$params 		= $app->getParams();
		
		$basepath 		= PAYCART_PATH_MEDIA_DIGITAL_TEASER;
		if($main_file){
			$basepath   = PAYCART_PATH_MEDIA_DIGITAL_MAIN;
		}
		
		$fullFilePath = $basepath.$media->getFilename();
		if(!JFile::exists($fullFilePath)){
			JError::raiseError(404,"File doesn't exist");
		}
		
		$fileSize        = filesize($fullFilePath);
		$fileWithoutPath = basename($fullFilePath);
		
		if($fileSize == 0 ) {
			die(JText::_('COM_PAYCART_FILE_SIZE_EMPTY'));
			exit;
		}
		
		ob_end_clean();
		
		// test for protocol and set the appropriate headers
	    jimport( 'joomla.environment.uri' );
	    $_tmp_uri 		= JURI::getInstance( JURI::current() );
	    $_tmp_protocol 	= $_tmp_uri->getScheme();
		if ($_tmp_protocol == "https") {
			// SSL Support
			header('Cache-Control: private, max-age=0, must-revalidate, no-store');
	    } else {
			header("Cache-Control: public, must-revalidate");
			header('Cache-Control: pre-check=0, post-check=0, max-age=0');
			header("Pragma: no-cache");
			header("Expires: 0");
		} /* end if protocol https */
		header("Content-Description: File Transfer");
		header("Expires: Sat, 30 Dec 1990 07:07:07 GMT");
		header("Accept-Ranges: bytes");
		
		// Modified by Rene
		// HTTP Range - see RFC2616 for more informations (http://www.ietf.org/rfc/rfc2616.txt)
		$httpRange   = 0;
		$newFileSize = $fileSize - 1;
		// Default values! Will be overridden if a valid range header field was detected!
		$resultLength = (string)$fileSize;
		$resultRange  = "0-".$newFileSize;
		// We support requests for a single range only.
		// So we check if we have a range field. If yes ensure that it is a valid one.
		// If it is not valid we ignore it and sending the whole file.
		if(isset($_SERVER['HTTP_RANGE']) && preg_match('%^bytes=\d*\-\d*$%', $_SERVER['HTTP_RANGE'])) {
			// Let's take the right side
			list($a, $httpRange) = explode('=', $_SERVER['HTTP_RANGE']);
			// and get the two values (as strings!)
			$httpRange = explode('-', $httpRange);
			// Check if we have values! If not we have nothing to do!
			if(!empty($httpRange[0]) || !empty($httpRange[1])) {
				// We need the new content length ...
				$resultLength	= $fileSize - $httpRange[0] - $httpRange[1];
				// ... and we can add the 206 Status.
				header("HTTP/1.1 206 Partial Content");
				// Now we need the content-range, so we have to build it depending on the given range!
				// ex.: -500 -> the last 500 bytes
				if(empty($httpRange[0]))
					$resultRange = $resultLength.'-'.$newFileSize;
				// ex.: 500- -> from 500 bytes to filesize
				elseif(empty($httpRange[1]))
					$resultRange = $httpRange[0].'-'.$newFileSize;
				// ex.: 500-1000 -> from 500 to 1000 bytes
				else
					$resultRange = $httpRange[0] . '-' . $httpRange[1];
				//header("Content-Range: bytes ".$httpRange . $newFileSize .'/'. $fileSize);
			} 
		}
		header("Content-Length: ". $resultLength);
		header("Content-Range: bytes " . $resultRange . '/' . $fileSize);
		header("Content-Type: " . (string)$matchedAllowedMimeType);
		header('Content-Disposition: attachment; filename="'.$fileWithoutPath.'"');
		header("Content-Transfer-Encoding: binary\n");
		
		// TEST TEMP SOLUTION - makes problems on somve server, @ added to prevent from warning
		@ob_end_clean();
					
		// Try to deliver in chunks
		@set_time_limit(0);
		$fp = @fopen($fullFilePath, 'rb');
		if ($fp !== false) {
			while (!feof($fp)) {
				echo fread($fp, 8192);
			}
			fclose($fp);
		} else {
			@readfile($fullFilePath);
		}
		flush();
		exit;
	}
   	
	public static function getMimeType($extension, $params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$returnMime = '';
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$temp	= $matches[0][$i][0];
			preg_match($regex_one,$temp,$parts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $parts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}

			// Return mime if extension call it
			if ($extension == $values[0]) {
				$returnMime = $values[1];
			}
		}

		if ($returnMime != '') {
			return $returnMime;
		} else {
			return false;
		}
	}
	
	public static function getMimeTypeString($params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$extString 	= '';
		$mimeString	= '';
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$temp	= $matches[0][$i][0];
			preg_match($regex_one,$temp,$parts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $parts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}
				
			// Create strings
			$extString .= $values[0];
			$mimeString .= $values[1];
			
			$j = $i + 1;
			if ($j < $count_matches) {
				$extString .=',';
				$mimeString .=',';
			}
		}
		
		$string 		= array();
		$string['mime']	= $mimeString;
		$string['ext']	= $extString;
		
		return $string;
	}
}