<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/
defined('_JEXEC') or die( 'Restricted access' );
/**
 * Admin Controller for Config
 * 
 * @since 1.0.0
 *  
 * @author rimjhim
 */

class PaycartAdminControllerConfig extends PaycartController 
{
	function save()
	{
		$post = $this->input->post->get($this->getControlNamePrefix(), array(), 'array');
		
		if(!empty($post['localization_origin_address'])){
			$post['localization_origin_address']['address'] = trim($post['localization_origin_address']['address']);
			$originAddress = json_encode($post['localization_origin_address']); //@PCTODO : should not use json_encode
			$post['localization_origin_address'] = $originAddress;
		}
		
		//Get All files from paycart form
		$image = $this->input->files->get($this->getControlNamePrefix(), false);	
		
		//save logo image
		if(!empty($image['company_logo']['tmp_name'])){
			
			$media = PaycartMedia::getInstance();
			$data = array();
			$data['language']['title'] = $image['company_logo']['name'];
			$media->bind($data);
			$media->save();
			
			$media->moveUploadedFile($image['company_logo']['tmp_name'], JFile::getExt($image['company_logo']['name']));
			
			$post['company_logo'] = $media->getId();
		}
		
		PaycartFactory::saveConfig($post);
		
		return true;
	}
	
	function deleteCompanyLogo()
	{
		$imageId   = $this->input->get('company_logo',0);
		$instance  = PaycartMedia::getInstance($imageId);
		$ret       = $instance->delete($imageId);
		
		$view = $this->getView();
		if($ret && $this->getModel()->save(array('company_logo' => ''))){
			$view->assign('success', true);
		}
		else{
			$view->assign('success', false);
		}
	
		return true;
	}
	
	
	public function changeDefaultLanguage()
	{
		$action = $this->input->get('action', 'init');
		$view = $this->getView();
		$view->assign('action', $action);
		
		switch($action){
			case 'do' :
				$prev_lang = PaycartFactory::getPCDefaultLanguageCode();
				$new_lang = $this->input->get('language');
				
				if($prev_lang != $new_lang){					
					PaycartFactory::saveConfig(array('localization_default_language' => $new_lang));
				}
				
				$view->assign('language', $new_lang);
				break;

			case 'confirmed' :				
				$view->assign('language', $this->input->get('language'));
				break;
				
			case 'init' :
				$prev_lang = PaycartFactory::getPCDefaultLanguageCode();
				$view->assign('prev_lang', $prev_lang);
			default		:
				// do nothing			
		}	
		
		return true;
	}
	
	protected static $_langModelNames = array('color', 'country', 'discountrule', 'media', 'notification', 'paymentgateway', 'productattribute',
												'productattributeoption', 'productcategory', 'product', 'shippingrule', 'state', 'taxrule');
	public function updateSupportedLanguage()
	{
		$action = $this->input->get('action', 'init');
		$view = $this->getView();
		$view->assign('action', $action);
		
		switch($action){
			case 'do' :
				$new_languages 	= json_decode($this->input->get('languages', '', 'string'));
				$old_languages	= PaycartFactory::getPCSupportedLanguageCode();
				
				$languagesToBeRemoved = array_diff($old_languages, $new_languages);
				foreach(self::$_langModelNames as $modelname){
					$model = PaycartFactory::getModel($modelname);
					if(!$model->deleteLanguageData($languagesToBeRemoved)){
						//@PCTODO : What to do if any error occurs
					}
				}
				
				$languagesToBeAdded = array_diff($new_languages, $old_languages);
				$defaultLang = PaycartFactory::getPCDefaultLanguageCode();
				foreach(self::$_langModelNames as $modelname){
					$model = PaycartFactory::getModel($modelname);
					foreach ($languagesToBeAdded as $newLang){
						if(!$model->copyDefaultLanguageData($defaultLang, $newLang)){
							//@PCTODO : What to do if any error occurs
						}
					}
				}
					
				PaycartFactory::saveConfig(array('localization_supported_language' => $new_languages));
				$view->assign('languages', $new_languages);
				break;

			case 'confirmed' :
				$languages 	 = json_decode($this->input->get('languages', '', 'string'));
				$languages[] = PaycartFactory::getPCDefaultLanguageCode();  // add default language also, as it will not get posted
				$view->assign('languages', $languages);
				break;
				
			case 'init' :		
				
			default		:
				// do nothing			
		}
		
		return true;
	}
}