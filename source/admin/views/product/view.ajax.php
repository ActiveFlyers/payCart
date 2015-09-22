<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );
/** 
 * Product Html View
* @author Team Readybytes
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminAjaxViewProduct extends PaycartAdminBaseViewProduct
{	
	protected $_response = null;
	
	function __construct($config = array() ) 
	{
		$this->_response = PaycartFactory::getAjaxResponse();
		return parent::__construct($config);
	}
	
	/**
	 * Unique Alias return on Ajax Call  
	 * 
	 * @throws Exception
	 */
	public function getAlias()
	{
		$title  = $this->input->get('title');
		$id 	= $this->input->get('product_id',0);
		
		if(!$title) {
			throw new Exception(Rb_Text::sprintf('COM_PAYCART_INVALID_POST_DATA', '$title missing'));
		}
		$alias = PaycartFactory::getInstance('product','model')->getTable()->getUniqueAlias($title, $id);
		//set ajax response and return it
		$this->_response->addRawData('row',$alias);
		$this->_response->sendResponse();	
	}
	
	/**
     * edit or create a new digiital content row
     */
	function editDigitalContent()
	{
		$mediaId	= $this->input->get('main_id',0,'INT');
		$teaserMediaId	= $this->input->get('teaser_id',0,'INT');
		$media		= PaycartMedia::getInstance($mediaId);
		$media      = $media->toArray();
		
		$teaserMedia = PaycartMedia::getInstance($teaserMediaId);
		$teaserMedia = $teaserMedia->toArray();
		
		$this->assign('media', $media);
		$this->assign('teaserMedia', $teaserMedia);
		$this->setTpl('edit_digital');
		return true;
	}
	
	function mapImportedCsvFields()
	{
		$filename	= $_SESSION['filename'];
		
		$modelform  = PaycartFactory::getInstance('config', 'Modelform');
		$form		= $modelform->getForm();
		
		//get the current language code
		$currentLanguage 	= PaycartFactory::getPCCurrentLanguageCode();
		
		//get the csv headers and entity's fields
		$helper				= PaycartFactory::getInstance('ImportFromCSV' , 'helper');
		$fields				= $helper->getCsvMapping('product' , $filename);
		
		$options			     = array();
		$options['-- Select --'] = '<option value=""> -- Select --</option>';
		foreach($fields['entity_fields'] as $field)
		{
			$options[$field] 	 = '<option value="'.$field.'">'.$field.'</option>';	
		}
		
		//bind the fields with template
		$this->assign('csv_headers' , 		 $fields['csv_headers']);
		$this->assign('options' , 	 		 $options);
		$this->assign('current_language' ,   $currentLanguage);
		$this->assign('form',				 $form);
				
		$html = $this->loadTemplate('mapFields');
		
		$this->_setAjaxWinHeight('auto');
		$this->_setAjaxWinWidth('auto');
		$this->_setAjaxWinBody($html); 
		$this->_setAjaxWinTitle(JText::_('COM_PAYCART_ADMIN_PRODUCT_IMPORT_TITLE'));
		$this->_addAjaxWinAction(JText::_('COM_PAYCART_ADMIN_PRODUCT_TOOLBAR_IMPORT'), "paycart.admin.product.doImport()", 'btn btn-success');
		$this->_setAjaxWinAction();
		
		$ajax = Rb_Factory::getAjaxResponse();
		$ajax->sendResponse();
	}
}