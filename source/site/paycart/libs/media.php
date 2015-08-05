<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Lib for Media
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartMedia extends PaycartLib 
{	
	protected $media_id	= 0;
	protected $filename = '';
	protected $mime_type = '';
	protected $is_free = true;
	protected $media_lang_id = 0;
	protected $lang_code     = '';
	protected $title 		 = '';
	protected $decsription   = '';
	protected $metadata_title    = '';
	protected $metadata_keywords = '';
	protected $metadata_description = '';
	
	/**
	 * @var PaycartHelperMedia
	 */
	protected $_helper 		= null;
	protected $_basepath 	= '';
	protected $_baseurl 	= '';
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_helper 		= PaycartFactory::getHelper('media');
		$this->_baseurl 	= JURI::ROOT().Paycart::MEDIA_PATH_ROOT.'/';
		$this->_basepath	= JPATH_ROOT.'/'.Paycart::MEDIA_PATH_ROOT.'/'; 
	}
	
	/**
	 * @return PaycartMedia
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('media', $id, $bindData);
	}
	
	public function reset() 
	{	
		$this->media_id	= 0;
		$this->filename = '';
		$this->mime_type = '';
		$this->is_free = true;
		$this->media_lang_id = 0;
		$this->lang_code = PaycartFactory::getPCDefaultLanguageCode(); //Current Paycart language Tag
		$this->title = '';
		$this->decsription = '';
		$this->metadata_title = '';
		$this->metadata_keywords = '';
		$this->metadata_description = '';
		
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::_delete()
	 */
	protected function _delete()
	{
		//1#. Delete Table fields
		if(!$this->getModel()->delete($this->getId())) {
			return false;
		}
		
		// delete original, thumb and optimized image
		if(JFile::exists($this->_basepath.$this->filename)){ 
			JFile::delete($this->_basepath.$this->filename);
		}
		
		if(JFile::exists($this->_basepath.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename)){
			JFile::delete($this->_basepath.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename);
		}
		
		if(JFile::exists($this->_basepath.Paycart::MEDIA_SQUARED_FOLDER_NAME.'/'.$this->filename)){
			JFile::delete($this->_basepath.Paycart::MEDIA_SQUARED_FOLDER_NAME.'/'.$this->filename);
		}

		
		if(JFile::exists($this->_basepath.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename)){
			JFile::delete($this->_basepath.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename);
		}
		
		return true;
	}
	
	function getTitle()
	{
		return $this->title;
	}
	
	function moveUploadedFile($source, $name, $ext)
	{
		$dest = $this->_basepath;
		if(!JFolder::exists($dest)){
			if(!JFolder::create($dest)){
				throw new Exception(JText::sprintf('COM_PAYCART_ADMIN_EXCEPTION_PERMISSION_DENIED', $dest));
			}
		}
		
		$dest = $dest.$name.'-'.$this->getId().'.'.$ext;
		if(!JFile::upload($source, $dest, false, false, PaycartFactory::getConfig()->get('catalogue_allowed_files'))){
			throw new Exception(JText::sprintf('COM_PAYCART_ADMIN_EXCEPTION_MOVE_PERMISSION_DENIED', $source, $dest));
		}
		
		$this->filename = $name.'-'.$this->getId().'.'.$ext;
		
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$this->mime_type = finfo_file($finfo, $this->_basepath.$this->filename);
		
		return $this->save();
	}
	
	public function toArray()
	{		
		$data = parent::toArray();		
		$data['original'] 	= $this->_baseurl.$this->filename;
		$data['optimized'] 	= JFile::exists($this->_basepath.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename) ? $this->_baseurl.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename : $data['original'];
		$data['thumbnail'] 	= JFile::exists($this->_basepath.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename) ? $this->_baseurl.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename : $data['original'];
		$data['squared'] 	= JFile::exists($this->_basepath.Paycart::MEDIA_SQUARED_FOLDER_NAME.'/'.$this->filename) ? $this->_baseurl.Paycart::MEDIA_SQUARED_FOLDER_NAME.'/'.$this->filename : $data['original'];
		
		$data['path_original'] 	= $this->_basepath.$this->filename;
		$data['path_optimized']	= JFile::exists($this->_basepath.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename) ? $this->_basepath.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename : $data['original'];
		$data['path_thumbnail']	= JFile::exists($this->_basepath.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename) ? $this->_basepath.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename : $data['original'];
		$data['path_squared']	= JFile::exists($this->_basepath.Paycart::MEDIA_SQUARED_FOLDER_NAME.'/'.$this->filename) ? $this->_basepath.Paycart::MEDIA_SQUARED_FOLDER_NAME.'/'.$this->filename : $data['original'];
	
		return $data;
	}
	
	public function createThumb($thumbWidth, $thumbHeight)
	{
		$properties = JImage::getImageFileProperties($this->_basepath.$this->filename);
		
		if($thumbHeight == 'auto'){
			$variation = $thumbWidth * 100 / $properties->width; 
			$thumbHeight = $properties->height * $variation / 100;
		}
		
		$image = new JImage($this->_basepath.$this->filename);
		$image = $image->resize($thumbWidth, $thumbHeight, true, JImage::SCALE_FILL);
	 	
		// Generate image name name
		$fileName 	= $this->_basepath.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename;

		if (!$image->toFile($fileName, $properties->type)) {
			return false;	
		}
		
		return true;
	}
	
	public function createOptimized($width, $height)
	{
		$properties = JImage::getImageFileProperties($this->_basepath.$this->filename);
		
		if($height == 'auto'){
			$variation = $width * 100 / $properties->width; 
			$height = $properties->height * $variation / 100;
		}
		
		$image = new JImage($this->_basepath.$this->filename);
		$image = $image->resize($width, $height, true, JImage::SCALE_FILL);
	 	
		// Generate image name name
		$fileName 	= $this->_basepath.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename;

		if (!$image->toFile($fileName, $properties->type)) {
			return false;	
		}
		
		return true;
	}

	public function createSquared($size)
	{
		if($size <= 0){
			throw new Exception('Invalid size ['.$size.']');
		}
		
		// get image handle
		$image = new JImage($this->_basepath.$this->filename);
		$properties = JImage::getImageFileProperties($this->_basepath.$this->filename);

		// image is small in height & width
		$width  = $image->getWidth();
		$height = $image->getHeight();
		$offsetX =  0; 
		$offsetY = 0 ;


		// #1. if wider, then correct the width first	
		if($height >= $size && $height >= $width){
			// expected width
			$width = $size;

			// calculate target height
			$variation = $size * 100 / $image->getWidth(); 
			$height = $image->getHeight() * $variation / 100;

			// generate new resized image
			$image  = $image->resize($width, $height, false, JImage::SCALE_OUTSIDE);
			
			$size = $width;
			$offsetY = 0; // ALWAYS USE image from top ($height > $size) ? ($height-$size)/2 : 0;


		}elseif($width >= $size && $width >= $height){
			
			// expected height
			$height = $size;

			// calculate target weight
			$variation = $size * 100 / $image->getHeight(); 
			$width = $image->getWidth() * $variation / 100;

			// generate new resized image
			$image  = $image->resize($width, $height, false, JImage::SCALE_OUTSIDE);
			$size = $height;
			$offsetX =  ($width  > $size) ? ($width-$size)/2 : 0;
		}


		// no resize required
		// #2. Now crop square from top-left
		$image = $image->crop($size, $size,$offsetX,$offsetY);


		// Generate image name name
		$fileName 	= $this->_basepath.Paycart::MEDIA_SQUARED_FOLDER_NAME.'/'.$this->filename;

		if (!$image->toFile($fileName, $properties->type)) {
			return false;	
		}
		
		return true;
	}
	
	/**
	 * Bind/populate model data on lib object if required
	 * @return PaycartMedia
	 */
	protected function _bindAfterSave()
	{
		$data = PaycartFactory::getModel('media')->loadRecords(array('media_id' => $this->getId()));
	
		//populate only required data
		if(!empty($data)){
			$data = array_shift($data);
			$this->media_lang_id = $data->media_lang_id;
		}
	
		return $this;
	}
	
	public function getOriginal()
	{
		return $this->_baseurl.$this->filename;
	}

	function setBasePath($path)
	{
		$this->_basepath = $path;
		return $this;
	}
	
	function setBaseUrl($url)
	{
		$this->_baseurl = $url;
		return $this;
	}
	
	function getFilename()
	{
		return $this->filename;
	}
}