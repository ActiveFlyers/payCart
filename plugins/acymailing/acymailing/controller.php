<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		Paycart
* @subpackage	acymailing
* @contact 		support@readybytes.in
* website		http://www.readybytes.net
*/
if(defined('_JEXEC')===false) die();

class PaycartadminControllerAcymailing extends PaycartController
{
	
		function display($cachable = false, $urlparams = array())
		{
			if(!JFolder::exists(JPATH_ADMINISTRATOR .'/components'.'/com_acymailing')){
				$this->setError(JText::_('PLG_PAYCART_INSTALL_ACYMAILING_BEFORE_USING_THIS_APPLICATION'));
				return false;
			}
		return true;
		}
	
		public function saveCategoryList()
		{	
				$list_ids   = $this->input->get('acyList',array(),'ARRAY');
			
				$category_id = $this->input->get('category_id','');
				$result = false;
			
				$model            = PaycartFactory::getInstance('acymailing','model');
				$existingdata     = $model->loadRecords(array('object_id'=>$category_id,'type' =>'category'));
				//if no entry in the table for that category then create new entry
				//else update the existing one
				
				$data['acymailing_groups'] = json_encode($list_ids);
				$data['object_id']			= $category_id;
				$data['type']	   			= "category";
				
				if(!empty($existingdata)){
					$existingdata = array_shift($existingdata);
					$data['acymailing_id']		= $existingdata->acymailing_id;
				}
				else{
					$data['acymailing_id']	=	null;
				}		
			
				if($model->save($data,$data['acymailing_id'])){
					$result = true;
				}
				$this->getView()->assign('success', $result);
				return true;
		}
}