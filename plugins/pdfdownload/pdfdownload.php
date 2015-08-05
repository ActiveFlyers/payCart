<?php

/**
* @copyright        Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license                GNU/GPL, see LICENSE.php
* @package                 Joomla.Plugin
* @subpackage        Paycart
* @contact                support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filesystem.archive' );
jimport( 'joomla.document.document' );

/**
* @author Garima Agal
*/
class PlgPaycartPdfdownload extends RB_Plugin
{
var $_name = 'pdfdownload';	

	public function __construct(&$subject, $config = array())
		{
			parent::__construct($subject, $config);
			
			// set some required variables in instance of plugin ($this)
			$this->app 		= JFactory::getApplication();
			$this->input 	= $this->app->input;
			
			// load language file also
			$this->loadLanguage();	
		}	
	
	public function onRbControllerCreation(&$option, &$view, &$controller, &$task, &$format)
		{
			if($controller === 'pdfdownload' )
			{
				$dir = dirname(__FILE__).DS.'pdfdownload';
		     	$this->__loadFiles();
			}
			else{
				return true;
			}
			if($task == 'sitedownload'){			
				
				$controller = "cart";
				$task		= "complete";
				// load class of mpdf
				require_once dirname(__FILE__).DS.'pdfdownload'.DS.'mpdf'.DS.'mpdf.php';
				$this->doSitePdf();
			}
		}
		
	protected function __loadFiles($format = 'pdf')
		{
			$dir = dirname(__FILE__).DS.'pdfdownload';
			Rb_HelperLoader::addAutoLoadFile($dir.DS.'view'.DS.'view.'.$format.'.php', 'PaycartAdminViewPdfdownload');
			Rb_HelperLoader::addAutoLoadFile($dir.DS.'pdfdownloadcontroller.php', 'PaycartadminControllerPdfdownload');	
			Rb_HelperLoader::addAutoLoadFile($dir.DS.'helper.php', 'PaycartHelperPdfdownload');			
		}
	
	
	public function onPaycartViewBeforeRender(Rb_View $view, $task)
	{
		
		$adminMenu['Reports'] =	Array(
										'title' => JText::_('COM_PAYCART_ADMIN_REPORTS'),
											'url' => '#',
											'class' => 'fa-newspaper-o',
										'children' => Array(
											Array(
												'title' => JText::_('COM_PAYCART_ADMIN_PDFDOWNLOAD'),
												'url' => 'index.php?option=com_paycart&view=pdfdownload',
												'class' => 'fa-tags'
											)
										)
									);
		$menu = PaycartFactory::getHelper('adminmenu');
		$menu->addMenu($adminMenu);
		
		
	if(($view instanceof PaycartsiteHtmlViewcart) && ($task == 'complete')){
				$cartId 	= $view->getModel()->getId();
				$cart 		= PaycartCart::getInstance($cartId);
				$this->_assign('cartId', $cartId);
				
				$html 	  	= $this->_loadTemplate("download_pdfhtml",null,'','default');
				return array("pc-invoice-thanks-action" => $html);
			}
	}

	
	public function doSitePdf(){
			$cart_id = PaycartFactory::getApplication()->input->get('cart_id',0);
			if (empty($cart_id)){
				$cart_id = $this->_session->get('cart_id',0);
			}
			
			$pdf_controller	= PaycartFactory::getInstance('pdfdownload', 'controller', 'Paycartadmin');
			$pdf_view 		= $pdf_controller->getView('pdfdownload', 'pdf');
			$pdf_view->download();
	}


}