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
	//Source Image File (with Absolute Path)
	protected $source 	= null;

	// Mime-type of Source Image File 
	protected $type 	= null;

	//Size of Source Image File
	protected $size 	= null;
	
	
	function __construct($source = null)
	{
		// load source image
		if($source) {
			$this->loadFile($source);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * Load file on helper object. File should be have either absolute path or upload-file (request) data   
	 * @param $source, either string or array
	 * @throws RuntimeException if file is not exist
	 * 
	 * @return $this
	 */
	public function loadFile($source)
	{
		// absolute path of image
		if(is_string($source)) {
			// Check source image file exist
			if(!JFile::exists($source)) {
				throw new InvalidArgumentException(Rb_Text::sprintf('COM_PAYCART_IMAGE_NOT_EXIST', $source));
			} 
			
			$this->size		=	filesize($source);
			$this->source 	=	$source;
			$this->type 	=	JImage::getImageFileProperties($this->source)->mime;
			
		}
		// uploaded file
		if(is_array($source)) {
			$this->type 	= 	$source['type'];
			$this->size 	=	$source['size'];
			$this->source 	=	$source['tmp_name'];
			// Check source image file exist
			if(!JFile::exists($this->source)) {
				throw new InvalidArgumentException(Rb_Text::sprintf('COM_PAYCART_IMAGE_NOT_EXIST', $this->source));
			}
		}
		
		return $this;
	}
	
	/**
	 * 
	 * Method to check image validation
	 * @throws RuntimeException
	 * 
	 * @return $this
	 */
	public function validate()
	{
		$config			= PaycartFactory::getConfig();
		
		$uploadLimit	= (double) $config->get('image_maximum_upload_limit', Paycart::IMAGE_FILE_DEFAULT_MAXIMUM_SIZE);	// size unit is MB
		$uploadLimit	= ( $uploadLimit * 1024 * 1024 );				// Convert to byte
		
		// @rule1: Limit image size based on the maximum upload allowed.
		if( $this->size > $uploadLimit && $uploadLimit != 0 ) {
			throw new RuntimeException( Rb_Text::_('COM_PAYCART_IMAGE_FILE_SIZE_EXCEEDED'));
		}
						
		// @rule2: Image Mime-type validation		
        $type 		= JString::strtolower( $this->type);
        $validType 	= array('image/png', 'image/x-png', 'image/gif', 'image/jpeg', 'image/pjpeg');

        if(!in_array($type, $validType )) {
			throw new RuntimeException(Rb_Text::_('COM_PAYCART_IMAGE_FILE_NOT_SUPPORTED'));
        }
        
        return $this;
	}	
	
	
	/**
	 * 
	 * Method to find out image extension type
	 */
	Private static function _getExtensionType()
	{
		$type = PaycartFactory::getConfig()->get('image_extension', Paycart::IMAGE_FILE_DEFAULT_EXTENSION);
	
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
	public function createThumb($imagePath, $thumbFolder = null,  $thumbWidth=null, $thumbHeight=null, $creationMethod = JImage::SCALE_FILL)
	{
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
		$extension = $config->get('image_extension', Paycart::IMAGE_FILE_DEFAULT_EXTENSION);
		$thumbFileName 	= $thumbFolder.'/'.Paycart::IMAGE_THUMB_PREFIX . $imagePathInfo['filename'].$extension;
		
		return self::resize($thumbFileName, $thumbWidth, $thumbHeight);
	}
	
	/**
	 * 
	 * Method call to Resizing 
	 * @param string $destinationImage		A new created image file.( with Absolute Path)
	 * @param string $width					Width for new created image
	 * @param string $height				Height for new created image
	 * @param Integer $scaleMethod			1-3 resize $scaleMethod | 4 create croppping (not supported)
	 * 
	 * @see JImage Class Constant
	 *
	 * @return (bool) True if image successfully created

	 */
	public function resize($destinationImage, $width, $height, $scaleMethod = JImage::SCALE_FILL) 
	{
		$image = new JImage($this->source);
		$image = $image->resize($width,$height, true, $scaleMethod);
	 	
	 	// Parent image properties
		$imgProperties = $image->getImageFileProperties($this->source);
	
		// Generate image name name
		$fileName 	= $destinationImage;	//$destinationFolder.'/'. $destinationFile;

		// Save thumb file to disk
		//@IMP :: File conversion decided here. is it png/jpg/gif 
		$type = self::_getExtensionType();
		if (!$image->toFile($fileName, $type)) {
			return false;	
		}
		return true;
	}
	
	/**
	 * 
	 * Method call to Create image url
	 * @param $imagePath $image path
	 * 
	 * @return (string) image url
	 */
	public static function getURL()
	{
		$config = PaycartFactory::getConfig();
		$root 	= $config->get('image_render_url', false);
		if(!$root) {
			$root = PaycartFactory::getURI()->root().Paycart::IMAGES_ROOT_PATH;
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
	public function imageInfo($imagePath) 
	{
		return pathinfo($imagePath);
	}
	
	/**
	 * Create new Image with Thumb. We do not provide any extension flexibility. Image extension have configured by Paycart System 
	 * How to Create :
	 * 		1#. Copy Original Image. Prfix 'original_' and Suffix '.orig' will be added to original image like "original_IMAGE-NAME.orig"
	 * 		2#. create New optimize Image (From New copied original Image)
	 * 		3#. Create new thumb(From optimize Image). Prifix "thumb_" will be added to optimized image. Like "thumb_IMAGE_NAME" 
	 * @param array $imageInfo
	 * 		$mageInfo = Array 
	 * 					( 	
	 * 						'targetFolder'	=>	'_ABSOLUTE_PATH_OF_TARGET_FOLDER_'
	 * 						'targetFileName'=>	'_NEW_CREATED_IMAGE_NAME_WITHOUT_IMAGE_EXTENSION' 
	 * 					)
	 */
	
	public function create($targetFolder, $targetFileName, $originalImage = true)
	{
		//if original image required
		if($originalImage) {
			// 1#. Copy source image to target folder it will be usefull for future operation like batch opration, reset operation etc 
			// Build Store path for Original image. Original Image available with prefix nd suffix like original_IMAGE-NAME.orig
			$originalImage	= $targetFolder.'/'.Paycart::IMAGE_ORIGINAL_PREFIX.$targetFileName.Paycart::IMAGE_ORIGINAL_SUFIX;;
			if (!JFile::copy($this->source, $originalImage )) {
				throw new RuntimeException(Rb_Text::sprintf('COM_PAYCART_FILE_COPY_FAILED', $originalImage));
			}
		}
		
		//2#.Create new optimize image
		// make Optimized Image Path. Extension of optimized image confiured by Paycart System
		$extension = PaycartFactory::getConfig()->get('image_extension', Paycart::IMAGE_FILE_DEFAULT_EXTENSION);
		$optimizeImage	= $targetFolder.'/'.$targetFileName.$extension;

		//@PCTODO (Discuss Point ) : height and width calculate with respect to original image.
		if (!$this->resize($optimizeImage, Paycart::IMAGE_OPTIMIZE_WIDTH, Paycart::IMAGE_OPTIMIZE_HEIGHT)) {
			throw new RuntimeException(Rb_Text::sprintf('COM_PAYCART_IMAGE_RESIZE_FAILED', $optimizeImage));
		}

		//3# Create thumbnail
		if(!$this->createThumb($optimizeImage, $targetFolder)){
			throw new RuntimeException(Rb_Text::sprintf('COM_PAYCART_THUMB_IMAGE_CREATION_FAILED', $optimizeImage));
		}

		return $this;
	}
	
	/**
	 * 
	 * Delete existing Images {Original, Optimized and thumb image}
	 * @param $imageFile : Absolute Path of optimized Image
	 */
	public function delete($imageFile, $originalImage = true)
	{
		$imageDetail =  $this->imageInfo($imageFile);
		
		// Original Image 
		if( $originalImage ) {
			$files[]	=	$imageDetail['dirname'].'/'.Paycart::IMAGE_ORIGINAL_PREFIX.$imageDetail['filename'].Paycart::IMAGE_ORIGINAL_SUFIX;
		}
		// Optimized Image
		$files[]	=	$imageFile;
		// thumb image
		$files[]	=	$imageDetail['dirname'].'/'.Paycart::IMAGE_THUMB_PREFIX.$imageDetail['filename'].'.'.$imageDetail['extension'] ;
		
		// Delete Original Image,Optimize Image and thumb Image
		JFile::delete($files);
	}
	
}
