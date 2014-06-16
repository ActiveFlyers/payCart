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
	protected $_language = null;
	
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
			
		$this->_language = new stdClass();
		$this->_language->media_lang_id = 0;
		$this->_language->media_id = 0;
		$this->_language->lang_code = PaycartFactory::getLanguage()->getTag(); //Current Paycart language Tag
		$this->_language->title = '';
		$this->_language->decsription = '';
		$this->_language->metadata_title = '';
		$this->_language->metadata_keywords = '';
		$this->_language->metadata_description = '';
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::bind()
	 */
	public function bind($data,  $ignore=array())
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		
		//1#. Bind Table specific fields
		parent::bind($data, $ignore);
		
		//2#. Bind related table fields (language specific)
		$records = array();
		//@PCTODO :: improve condition as per your requirment
		if(isset($data['language'])){
			// like data is Posted
			$records = $data['language'];
		} else if($this->getId()){
			//@PCTODO :: move to helper and array_shift record
			$records	 = PaycartFactory::getModelLang('media')
										->loadRecords(Array('lang_code' => $this->_language->lang_code,
															'media_id' => $this->getId()
															));
			$records = (array)array_shift($records);															
		}
		
		$this->setLanguageData($records);
		
		return $this;
	}
	
	/**
	 *
	 * Set language specific data on $this _language property
	 * @param $data, Array of available data which are set on $this language
	 * 
	 * @return $this
	 */
	public function setLanguageData($data)
	{
		if(!is_array($data)){
			$data = (array) ($data);
		}
		
		// set _language stuff
		foreach ($this->_language as $key => $value) {
			// set data value on $this if key is set on data 
			if(isset($data[$key])) {
				$this->_language->$key = $data[$key];
			}
		}
		
		return $this;
	}	
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::_save()
	 */
	protected function _save($previousObject) 
	{
		// Save Table fields data
		$saveId = parent::_save($previousObject);
		
		if (!$saveId) {
			return false;
		}
		
		$this->setId($saveId);
		
		// Save Related table fields (Language stuff)
		$languageData = (Array)$this->_language;
		$languageData['media_id'] = $saveId;
				
		// store new data
		if(!PaycartFactory::getModelLang('media')->save($languageData,$this->_language->media_lang_id)) {
			//@PCTODO :: notify to admin
		}
		
		// few class properties might be changed by model validation or might be chage related fields
		// so we need to reflect these kind of changes to lib object
		$this->reload();
		
		return $saveId;
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
		
		//2#. Delete Related table fields
		if (!$this->_deleteLanguageData()) {
			return false;
		}
		
		// delete original, thumb and optimized image
		if(JFile::exists($this->_basepath.$this->filename)){ 
			JFile::delete($this->_basepath.$this->filename);
		}
		
		if(JFile::exists($this->_basepath.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename)){
			JFile::delete($this->_basepath.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename);
		}
		
		if(JFile::exists($this->_basepath.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename)){
			JFile::delete($this->_basepath.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename);
		}
		
		return true;
	}
	
	/**
	 * 
	 * Delete language specific data for product category
	 * @param string $langCode, have language code if need to delete specificlanguage code data
	 * 
	 */
	protected function _deleteLanguageData($langCode = '')
	{
		// Product category id
		$condition['media_id'] = $this->getId();
		
		// specific language code
//		if ($langCode) {
//			$condition['lang_code'] = $langCode;
//		}
		
		return PaycartFactory::getModelLang('media')->deleteMany($condition);
	}
	
	function getLanguage()
	{
		return $this->_language;
	}
	
	function getTitle()
	{
		return $this->_language->title;
	}
	
	function moveUploadedFile($source, $ext)
	{
		$dest = $this->_basepath;
		if(!JFolder::exists($dest)){
			if(!JFolder::create($dest)){
				throw new Exception(JText::sprintf('COM_PAYCART_ADMIN_EXCEPTION_PERMISSION_DENIED', $dest));
			}
		}
		
		$dest = $dest.$this->getId().'.'.$ext;
		if(!JFile::upload($source, $dest)){
			throw new Exception(JText::sprintf('COM_PAYCART_ADMIN_EXCEPTION_MOVE_PERMISSION_DENIED', $source, $dest));
		}
		
		$this->filename = $this->getId().'.'.$ext;
		$properties = JImage::getImageFileProperties($this->_basepath.$this->filename);
		$this->mime_type = $properties->mime;
		return $this->save();
	}
	
	public function toArray()
	{		
		$data = parent::toArray();		
		$data['original'] 	= $this->_baseurl.$this->filename;
		$data['optimized'] 	= JFile::exists($this->_basepath.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename) ? $this->_baseurl.Paycart::MEDIA_OPTIMIZED_FOLDER_NAME.'/'.$this->filename : $data['original'];
		$data['thumbnail'] 	= JFile::exists($this->_basepath.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename) ? $this->_baseurl.Paycart::MEDIA_THUMB_FOLDER_NAME.'/'.$this->filename : $data['original'];
		$data['language'] 	= (array)$this->_language;		
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
}