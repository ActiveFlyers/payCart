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
		$post = $this->input->post->get('paycart_form', array(), 'array');
		
		if(!empty($post['localization_origin_address'])){
			$post['localization_origin_address']['address'] = trim($post['localization_origin_address']['address']);
			$originAddress = json_encode($post['localization_origin_address']);
			$post['localization_origin_address'] = $originAddress;
		}
		
		//Get All files from paycart form
		$image = $this->input->files->get('paycart_form', false);	
		
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
		
		$this->getModel()->save($post);
		
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
}