<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	paycartHelper
 * @contact		team@readybytes.in
 * @author 		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Image Helper
 */
class PaycartHelperImage extends PaycartHelper
{
	static protected $erroMessage = '';	
	/**
	 * 
	 * Method check image is valid or not
	 * @param string $file, Posted Image file
	 * 
	 * @return True if image is valid
	 */
	static public function isValid( $file )
	{
		$config			= PaycartFactory::getConfig();
		
		$uploadLimit	= (double) $config->get('maxuploadsize', Paycart::IMAGE_FILE_DEFAULT_MAXIMUM_SIZE);	// size unit is MB
		$uploadLimit	= ( $uploadLimit * 1024 * 1024 );				// Convert to byte
		
		// @rule1: Limit image size based on the maximum upload allowed.
		if( $file['size'] > $uploadLimit && $uploadLimit != 0 ) {
			self::$erroMessage  =  Rb_Text::_('COM_PAYCART_IMAGE_FILE_SIZE_EXCEEDED');
			return false;
		}
						
		// @rule2:Image type validation		
        $type 		= JString::strtolower( $file['type']);
        $validType 	= array('image/png', 'image/x-png', 'image/gif', 'image/jpeg', 'image/pjpeg');

        if(!in_array($type, $validType )) {
        	self::$erroMessage  =  Rb_Text::_('COM_PAYCART_IMAGE_FILE_NOT_SUPPORTED');
			return false;
        }
        
        return true;
	}	
	
	/**
	 * 
	 * @return Last error messge 
	 */
	static public function getError() {
		return self::$erroMessage;
	}
	
	/**
	 * 
	 * Method to find out image extension type
	 * @param String $imageFile, Either image path or Image file name
	 */
	Private static function _getExtensionType( $imageFile )
	{
		$type = self::getConfigExtension($imageFile);
	
		switch($type){
			case '.gif'	:
				return IMAGETYPE_GIF;
			case '.jpeg'	:
			case '.jpeg'	:
				return IMAGETYPE_JPEG;
			case '.png' 	:
			default:	// We default to use png
				return IMAGETYPE_PNG;  
		}

	}
	
	/**
	 * Method to create thumbnail from the current image and save to disk. It allows creation by resizing
	 * the original image. (croppping  is not allowed)
	 *
	 * @param  	string 	 $imagePath		  Image file path
	 * @param 	string	 $thumbFolder	  Destination thumbs folder.
	 * @param 	integer	 $thumbWidth	  Thumb width
	 * @param   integer  $thumbHeight     Thumb height
	 * @param   integer  $creationMethod  1-3 resize $scaleMethod | 4 create croppping
	 * @see JImage Class Constant
	 *
	 * @return JImage Object
	 *
	 * @throws  InvalidArgumentException
	 *
	 * @since Jooomla 3.0
	 */
	static public function createThumb($imagePath, $thumbFolder = null,  $thumbWidth=null, $thumbHeight=null, $creationMethod = JImage::SCALE_FILL)
	{
		// Image File must be valid 
		if (!$imagePath || !JFile::exists($imagePath)) {
			// @codeCoverageIgnoreStart
			//PCTODO :: Us language string for Error msg 
			throw new InvalidArgumentException('Invalid argument {$imagePath} in thumb creation : Might be empty or image does not exist');
			// @codeCoverageIgnoreEnd
		}

		$config = PaycartFactory::getConfig();
		
		if (!$thumbWidth) {
			$thumbWidth 	= $config->get('thumb_width',  Paycart::THUMB_IMAGE_WIDTH);
		}
		
		if (!$thumbHeight) {
			$thumbHeight 	= $config->get('thumb_height', Paycart::THUMB_IMAGE_HEIGHT); 
		}
		
		$imagePathInfo = pathinfo($imagePath);
		// Generat thumb path
		if (!$thumbFolder) {
			$thumbFolder = $imagePathInfo['dirname']; 
		}
		// Generate thumb name
		$thumbFileName 	= Paycart::THUMB_IMAGE_PREFIX . $imagePathInfo['filename'] .self::getConfigExtension($imagePath) ;
		
		return self::resize($imagePath, $thumbFolder, $thumbWidth, $thumbHeight, $thumbFileName);
	}
	
	/**
	 * 
	 * Method call to Resizing 
	 * @param string $sourceImage 			A file path for a source image.
	 * @param string $destinationFolder		A folder name where created image will be store
	 * @param string $width					Width for new created image
	 * @param string $height				Height for new created image
	 * @param string $destinationFile		New created image name
	 * @param Integer $creationMethod		1-3 resize $scaleMethod | 4 create croppping (not supported)
	 * @see JImage Class Constant
	 *
	 * @return (bool) True if image successfully created

	 */
	public static function resize($sourceImage, $destinationFolder, $width, $height, $destinationFile=null, $imageExtension = null, $scaleMethod = JImage::SCALE_FILL) 
	{
		$image = new JImage($sourceImage);
		$image = $image->resize($width,$height, true, $scaleMethod);
	 	
	 	// Parent image properties
		$imgProperties = $image->getImageFileProperties($sourceImage);

		$imagePathInfo = pathinfo($sourceImage);
		
		// Generat path
		if (!$destinationFolder) {
			$destinationFolder = $imagePathInfo['dirname']; 
		}
		if (!$destinationFile) {
			$destinationFile = $imagePathInfo['filename'];
		}
		
		// Generate image name name
		$config = PaycartFactory::getConfig();
		$fileName 	= $destinationFolder.'/'. $destinationFile;
		// Save thumb file to disk
		//@IMP :: File conversion decided here. is it png/jpg/gif 
		if($imageExtension) {
			$type = self::_getExtensionType($fileName);
		}
		if (!$image->toFile($fileName, $type)) {
			return false;	
		}
		return true;
	}
	
	/**
	 * 
	 * Method to get image thumb name 
	 * @param String $imageTitle image name
	 * 
	 * @return string thumb image name
	 */
	public static function getThumbName($imageTitle)
	{
		return Paycart::THUMB_IMAGE_PREFIX.$imageTitle;
	} 
	
	/**
	 * 
	 * Method to get original image name 
	 * @param String $imageTitle image name
	 * 
	 * @return string original image name
	 */
	public static function getOriginalName($imageTitle)
	{
		return Paycart::ORIGINAL_IMAGE_PREFIX.$imageTitle;
	}
	
	/**
	 * 
	 * Method to get original image name 
	 * @param String $imageTitle image name
	 * 
	 * @return string original image name
	 */
	public static function getOptimizeName($imageTitle)
	{
		return PaycartHelper::getHash($imageTitle).self::getConfigExtension($imageTitle);
	}
	
	/**
	 * 
	 * Method to get original image name 
	 * @param String $imageFile Either image name or path
	 * 
	 * @return string original image name
	 */
	public static function getConfigExtension($imageFile)
	{
		//PCTODO :: Use 'auto' in image configuration nd remove hard code
		$extension = PaycartFactory::getConfig()->get('image_extension', 'auto');
		// auto means use upaload image's extension
		if ($imageFile && strtolower($extension) == 'auto') {
			$extension = '.'.JFile::getExt($imageFile);
		}
		// if extension is not exist then use default 
		return (strtolower($extension) == 'auto') ? Paycart::IMAGE_FILE_DEFAULT_EXTENSION : $extension;
	}
	
	/**
	 * 
	 * Method for delete image  
	 * @param  String $path image path
	 * 
	 * @return (bool) True if image successfully delete
	 */
	public static function delete($path)
	{
		if ( !JFile::delete($path) ) {
			Rb_Factory::getApplication()->enqueueMessage(Rb_Text::sprintf('COM_PAYCART_IMAGE_DELETE_FAILED', $path),'warning');
			return false;
		}
		return true;
	}
}
