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
 * Admin Ajax view of config
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
require_once dirname(__FILE__).'/view.php';

class PaycartAdminViewConfig extends PaycartAdminBaseViewConfig
{	
	public function changeDefaultLanguage()
	{
		$action = $this->get('action');
		
		//now record is always an array, pick all records
		$modelform  = PaycartFactory::getInstance('config', 'Modelform');
		$form		= $modelform->getForm();
		
		switch($action){
			case 'do' :
				$ajax = PaycartFactory::getAjaxResponse();
				$lang_code = $this->get('language');				
				$flag = PaycartHtmlLanguageflag::getFlag($lang_code, true);
				$ajax->addScriptCall('paycart.admin.config.changeDefaultLanguage.done', $flag); 
				$this->setTpl('defaultlanguage_do');
				break;

			case 'confirmed' :
				$this->assign('language', $this->get('language'));
				$this->assign('form', $form);
				$this->setTpl('defaultlanguage_confirmed');
				break;
				
			case 'init' :
			default		:
				$form->bind(array('localization_default_language' => $this->get('prev_lang')));
				$this->assign('form', $form);
				$this->setTpl('defaultlanguage_init');
		}		
		
		$this->_renderOptions = array('domObject'=>'pc-config-localization-defaultlang-modal','domProperty'=>'innerHTML');
		return true;
	}
	
	public function updateSupportedLanguage()
	{
		$action = $this->get('action');
		
		//now record is always an array, pick all records
		$modelform  = PaycartFactory::getInstance('config', 'Modelform');
		$form		= $modelform->getForm();
		
		switch($action){
			case 'do' :
				$languages = $this->get('languages');
				$html = '';
				foreach($languages as $language){
					$html .= '<div>'.PaycartHtmlLanguageflag::getFlag($language, true).'</div>';
				}
				
				$ajax = PaycartFactory::getAjaxResponse();
				$ajax->addScriptCall('paycart.admin.config.updateSupportedLanguage.done', $html); 
				$this->setTpl('supportedlanguage_do');
				break;

			case 'confirmed' :
				$this->assign('languages', $this->get('languages'));
				$form->bind(array('localization_supported_language' => $languages));
				$this->assign('form', $form);
				$this->setTpl('supportedlanguage_confirmed');
				break;
				
			case 'init' :
			default		:
				$this->assign('default_language', PaycartFactory::getConfig()->get('localization_default_language'));
				$this->assign('supported_language', PaycartFactory::getConfig()->get('localization_supported_language'));
				$this->assign('languages', Rb_HelperJoomla::getLanguages());
				$this->assign('form', $form);
				$this->setTpl('supportedlanguage_init');
		}		
		
		$this->_renderOptions = array('domObject'=>'pc-config-localization-supportedlang-modal','domProperty'=>'innerHTML');
		return true;
	}
}