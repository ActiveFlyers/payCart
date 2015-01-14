<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class PaycartAdminControllerAppstore extends PaycartController
{
	function getModel($name = '', $prefix = '', $config = array())
	{
		return null;
	}
	
	public function display($cachable = false, $urlparams = array())
	{
		$db		= PaycartFactory::getDbo();
		$query	= 'SELECT * FROM `#__extensions`'
				 .'WHERE `type` LIKE "component"'
				 .'AND `element` LIKE "com_rbinstaller"';
				
		$db->setQuery($query);
		$object = $db->loadObject();
		if(!$object)
		{
	 		$file_url  = 'http://pub.readybytes.net/rbinstaller/update/live.json';
     		$link     = new JURI($file_url);  
      		$curl     = new JHttpTransportCurl(new Rb_Registry());
     		$response   = $curl->request('GET', $link);
      
      		if($response->code != 200){
      			$msg = JText::_('COM_PAYCART_ADMIN_APPSTORE_UNABLE_TO_FIND_FILE');
       	 		$this->setRedirect("index.php?option=com_paycart", $msg, 'error');
       	 		return false;
      		}
                
     		$content   	=  json_decode($response->body, true);    
     		$file_path	= new JUri($content['rbinstaller']['file_path']);
			
			$data			= $curl->request('GET', $file_path);		
			$content_type 	= $data->headers['Content-Type'];
    
   			 if ($content_type != 'application/zip'){ 
   			 	$msg = JText::_('COM_PAYCART_ADMIN_APPSTORE_UNABLE_TO_FIND_FILE');
      			$this->setRedirect("index.php?option=com_paycart", $msg, 'error');
      			return false;
   		 	}
    		else {
      			$file =  $data->body;
				if(!$this->_install($file)){
					$msg  = JText::_('COM_PAYCART_ADMIN_APPSTORE_INSTALLATION_FAILED');
					$this->setRedirect("index.php?option=com_paycart", $msg, 'error');
					return false;
				}
			}
		}
		
		//In case rbinstaller is installed but disable
		elseif (!$object->enabled){
			$this->setRedirect("index.php?option=com_installer&view=manage&filter_search=rb", JText::_('COM_PAYCART_ADMIN_APPSTORE_ENABLE_RBINSTALLER'), 'warning');
			return false;
		}
       	 		
		$this->setRedirect("index.php?option=com_rbinstaller&view=item&product_tag=rbappspaycart&tmpl=component#/app");
		return false;
	}
	
	private function _install($file)
	{
		$random	= rand(1000, 999999);
		$tmp_file_name = JPATH_ROOT.'/tmp/'.$random.'.zip';
		$tmp_folder_name = JPATH_ROOT.'/tmp/'.$random;
		// create a file
		JFile::write($tmp_file_name, $file);
		jimport('joomla.filesystem.archive');
		jimport( 'joomla.installer.installer' );
		jimport('joomla.installer.helper');
		
		JArchive::extract($tmp_file_name, $tmp_folder_name);
		$installer = JInstaller::getInstance();
		if(!$installer->install($tmp_folder_name))
		{
			return false;
		}
		
		if (JFolder::exists($tmp_folder_name)){
			JFolder::delete($tmp_folder_name);
		}
		
		if (JFile::exists($tmp_file_name)){
			JFile::delete($tmp_file_name);
		}
		return true;
	}
}
