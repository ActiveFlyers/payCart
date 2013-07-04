<?php
/**
 *@copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 *@license		GNU/GPL, see LICENSE.php
 *@package		PayCart
 *@subpackage	Pacart Form
 *@author 		mManishTrivedi 
*/

defined('JPATH_PLATFORM') or die;

/**
 * 
 * Define here all required Paycart internal events/triggers
 *
 * @author Manish Trivedi
 *
 */
class PaycartEvent extends JEvent
{
	/**
	 * Invoke this method on every save task of all entity 
	 * @param Rb_Lib $previousObject, previous lib object (Beofre save)
	 * @param RB_Lib $currentObject , Current Lib object (After save )
	 * @param string $entity, entity name. Save task call on this entity. 
	 */
	public function onPaycartAfterSave($previousObject, $currentObject, $entity)
	{
		switch ($entity) {
			case 'product' :
				self::_onProductAfterSave($previousObject, $currentObject);
				break;
		}
	}

	/**
	 * 
	 * Method invoke when Product will be save 
	 * @param Product_Lib $previousObject, Before save
	 * @param Product_Lib $currentObject, After save
	 */
	protected static function _onProductAfterSave($previousObject, $currentObject) 
	{
		return self::_ImageProcess($previousObject, $currentObject);
	}
	
	/**
	 * 
	 * Process Cover Image
	 * @param Lib_object $previousObject
	 * @param Lib_object $currentObject
	 * 
	 * @return (bool) True if successfully proccessed
	 */
	private function _ImageProcess($previousObject, $currentObject)
	{
		// @IMP :: must be sure data post from paycart form	
		$file 		= PaycartFactory::getApplication()->input->files->get('paycart_form');
		// no image
		if ( !$file || !isset($file['cover_image']) || !$file['cover_image']['name'] ) {
			return true;
		}
		
		// 	Upload new image while Previous Image exist 
		// need to remove previous image and thumbnail image
		if ($previousObject  && $previousObject->get('cover_image')) {
			// PCTODO::need to remove previous image and thumbnail image
		}
		
		$imageFile 	= $file['cover_image'];

		// Image validation required	
		if (!PaycartHelperImage::isValid($imageFile)) {
			$error = PaycartHelperImage::getError();
			PaycartFactory::getApplication()->enqueueMessage($error, 'warning');
			return false;
		}
		
		//Create new folder
		// PCTODO:: Should be common
		$entity 	= $currentObject->getname();
		// Dyamically get constant name 
		$constant	= JString::strtoupper($entity.'_IMAGES_PATH');
		$folderPath = JPATH_ROOT.constant("Paycart::$constant");
		$folderName	= $currentObject->getId();	
		$imagePath	= "$folderPath/$folderName";
		
		if(!JFolder::exists($imagePath) && !JFolder::create($imagePath)) {
			// PCTODO:: Warning
			return false;
		}
		
		//Store original image
		$source 		= $imageFile["tmp_name"];
		//PCTODO:: Image name should be clean
		$destination 	= $imagePath.'/'.$currentObject->getCoverImage();

		if (!JFile::copy($source, $destination)) {
			// PCTODO:: Warning
			return false;
		}
		
		//@PCTODO : Create new optimize image
		
		//Create thumbnail 
		if(!PaycartHelperImage::createThumb($destination)){
			// PCTODO:: Warning
			return false;
		}
		
		return true;
	}
}

/**
 * Event Registeration here 
 */
$dispatcher = JDispatcher::getInstance();
$dispatcher->register('onPaycartAfterSave', 'PaycartEvent');
