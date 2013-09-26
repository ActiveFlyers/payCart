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
		
		$uploadLimit	= (double) $config->get('image_maximum_upload_limit', Paycart::IMAGE_FILE_DEFAULT_MAXIMUM_SIZE);	// size unit is MB
		$uploadLimit	= ( $uploadLimit * 1024 * 1024 );				// Convert to byte
		
		// @rule1: Limit image size based on the maximum upload allowed.
		if( $file['size'] > $uploadLimit && $uploadLimit != 0 ) {
			throw new RuntimeException( Rb_Text::_('COM_PAYCART_IMAGE_FILE_SIZE_EXCEEDED'));
		}
						
		// @rule2:Image type validation		
        $type 		= JString::strtolower( $file['type']);
        $validType 	= array('image/png', 'image/x-png', 'image/gif', 'image/jpeg', 'image/pjpeg');

        if(!in_array($type, $validType )) {
			throw new RuntimeException(Rb_Text::_('COM_PAYCART_IMAGE_FILE_NOT_SUPPORTED'));
        }
        
        return true;
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
			case '.jpg'	:
				return IMAGETYPE_JPEG;

			case '.bmp' 	:
				return IMAGETYPE_BMP;

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
			//PCTODO :: Use language string for Error msg 
			throw new InvalidArgumentException('Invalid argument {$imagePath} in thumb creation : Might be empty or image does not exist');
		}

		$config = PaycartFactory::getConfig();
		
		if (!$thumbWidth) {
			$thumbWidth 	= $config->get('image_thumb_width',  Paycart::IMAGE_THUMB_WIDTH);
		}
		
		if (!$thumbHeight) {
			$thumbHeight 	= $config->get('image_thumb_height', Paycart::IMAGE_THUMB_HEIGHT); 
		}
		
		$imagePathInfo = pathinfo($imagePath);
		// Generat thumb path
		if (!$thumbFolder) {
			$thumbFolder = $imagePathInfo['dirname']; 
		}
		// Generate thumb name
		$thumbFileName 	= $thumbFolder.'/'.Paycart::IMAGE_THUMB_PREFIX . $imagePathInfo['filename'] .self::getConfigExtension($imagePath) ;
		
		return self::resize($imagePath, $thumbFileName, $thumbWidth, $thumbHeight);
	}
	
	/**
	 * 
	 * Method call to Resizing 
	 * @param string $sourceImage 			Source Image File (with Absolute Path)
	 * @param string $destinationImage		A new created image file.( with Absolute Path)
	 * @param string $width					Width for new created image
	 * @param string $height				Height for new created image
	 * @param string $destinationFile		New created image name
	 * @param Integer $creationMethod		1-3 resize $scaleMethod | 4 create croppping (not supported)
	 * 
	 * @see JImage Class Constant
	 *
	 * @return (bool) True if image successfully created

	 */
	public static function resize($sourceImage, $destinationImage, $width, $height, $scaleMethod = JImage::SCALE_FILL) 
	{
		$image = new JImage($sourceImage);
		$image = $image->resize($width,$height, true, $scaleMethod);
	 	
	 	// Parent image properties
		$imgProperties = $image->getImageFileProperties($sourceImage);

//		$imagePathInfo = self::imageInfo($sourceImage);
//		
//		// Generat path
//		if (!$destinationFolder) {
//			$destinationFolder = $imagePathInfo['dirname']; 
//		}
//		if (!$destinationFile) {
//			$destinationFile = $imagePathInfo['filename'];
//		}
		
		// Generate image name name
		$config = PaycartFactory::getConfig();
		$fileName 	= $destinationImage;	//$destinationFolder.'/'. $destinationFile;
		// Save thumb file to disk
		//@IMP :: File conversion decided here. is it png/jpg/gif 
		$type = self::_getExtensionType($fileName);
		if (!$image->toFile($fileName, $type)) {
			return false;	
		}
		return true;
	}
	
	/**
	 * 
	 * Method to get image extension 
	 * @param String $imageFile Either image name or path
	 * 
	 * @return string image extension
	 * @PCTODO :: remove this method
	 */
	public static function getConfigExtension($imageFile = null)
	{
		$extension = PaycartFactory::getConfig()->get('image_extension', Paycart::IMAGE_FILE_DEFAULT_EXTENSION);		
		return $extension;
	}
	
	/**
	 * 
	 * Method call to Create image url
	 * @param $imagePath $image path
	 * 
	 * @return (string) image url
	 */
	public static function getURL($imagePath)
	{
		$config = PaycartFactory::getConfig();
		$root 	= $config->get('image_render_url', false);
		
		$path = '';
		if(!$root) {
			$path = PaycartFactory::getURI()->root().Paycart::IMAGES_ROOT_PATH;
		}
			
		return $path.'/'.$imagePath;
	}
	
	public static function getDirectory()
	{
		$config = PaycartFactory::getConfig();
		$root 	= $config->get('image_upload_directory', false);
		
		if(!$root) {
			$root = JPATH_ROOT.Paycart::IMAGES_ROOT_PATH;
		}
		
		return $root;
	}
	
	/**
	 * Returns information about a file path
	 * @link http://www.php.net/manual/en/function.pathinfo.php
	 * @param $imagePath string <p>
	 * The path being checked.
	 * </p>
	 * @param options int[optional] <p>
	 * You can specify which elements are returned with optional parameter
	 * options. It composes from
	 * PATHINFO_DIRNAME,
	 * PATHINFO_BASENAME,
	 * PATHINFO_EXTENSION and
	 * PATHINFO_FILENAME. It
	 * defaults to return all elements.
	 * </p>
	 * @return mixed The following associative array elements are returned:
	 * dirname, basename,
	 * extension (if any), and filename.
	 * </p>
	 * <p>
	 * If options is used, this function will return a 
	 * string if not all elements are requested.
	 */
	public static function imageInfo($imagePath) 
	{
		return pathinfo($imagePath);
	}
}
