<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		mManish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

class PaycartAdminControllerCountry extends PaycartController 
{
	/**
	 * 
	 * @var overwrite
	 */
	protected $_id_data_type	=	'STRING';
	
	/**
	 * Overridden this function becuase we treat country differently than other entities
	 * User fill the country_id and if it is already exist then we will throw error 
	 * 
	 * (non-PHPdoc)
	 * @see components/com_paycart/paycart/base/PaycartController::save()
	 */
	public function save()
    {    		
        $id = $this->input->get('id',null);
       
		//do this only if the record is new
        if(!$id){    
        	$itemId = $this->_getId(); 
            $new    = $this->getModel()->getTable()->load($itemId)? false : true;;
           
            if(!$new){
                $this->setRedirect('index.php?option=com_paycart&view=country',$this->setMessage(Jtext::_('COM_PAYCART_ADMIN_COUNTRY_DUPLICATE_ERROR')),'error');
                return false;
            }
        }
       
        return parent::save();
    }

	/**
	 * Saves an item (new or old)
	 */
	public function _save(array $data, $itemId=null)
	{		
		// validation will be done on Model
		return  $this->getModel()->save($data, $itemId);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_Controller::_remove()
	 */
	public function _remove($itemId=null, $userId=null)
	{
		return $this->getModel()->delete($itemId);
	}
	
	/**
	 * Initialize import and open popup
	 */
	public function initImport()
	{
		return true;
	}
	
	/**
	 * Start importing the selected countries
	 */
	public function doImport()
	{
		$countries 	 = json_decode($this->input->get('countries', '', 'string'));
		$start		 = $this->input->get('start', 0, 'int');
		$limit		 = paycart::LIMIT_COUNTRY_IMPORT;
		$total       = count($countries);
		$countryData = PaycartFactory::getHelper('country')->getCountryList();
		$model 		 = $this->getModel();
		
		//get language code
		$lang_code	 = PaycartFactory::getPCDefaultLanguageCode();
		$post_data   = $model->getState('post_data', array());
		if(isset($post_data['lang_code']) && !empty($post_data['lang_code'])){
			$lang_code = $post_data['lang_code'];
		}
		
		$ajax 	  	 = PaycartFactory::getAjaxResponse();
		$response 	 = new stdClass();
		
		for ($count = $start; $count <= $start+$limit && $count<$total; $count++){
			//1. create country
			$key = $countries[$count];
			$data = array();
			$data['country_id'] = $countryData[$key]['isocode3'];
			$data['title']		= $countryData[$key]['title'];
			$data['isocode2']	= $countryData[$key]['isocode2'];
			$data['published']  = 1;
			$data['lang_code']  = $lang_code;
			
			try{
				$model->save($data, $countryData[$key]['isocode3']);
								
				//2. create state of the current country
				$this->_createStates($data, $lang_code);
			}
			catch(Exception $e){
				$ajax->addScriptCall('paycart.admin.country.importerror');
				$ajax->sendResponse();
			}
		}	
		
		$response->start     = $start+$limit+1;
		$response->next      = true;
		$response->countries = json_encode($countries);
		
		if($count >= $total){
			$response->next  = false;	
		}
					
		// set call back function
		$ajax->addScriptCall('paycart.admin.country.importsuccess', json_encode($response));
		$ajax->sendResponse($response);
		
		return false;
	}	
	
	/**
	 * Create states of the given country data
	 * @param array $data : containing details about country
	 * @param string $lang_code
	 */
	protected function _createStates($data, $lang_code)
	{
		$url 	      = 'http://www.westclicks.com/webservices/';
		$requestData  = 'f=json&c='.$data['isocode2'];
			
		jimport('joomla.http.transport');
		$link 		= new JURI($url);		
		$curl 		= new JHttpTransportCurl(new Rb_Registry());
		$response 	= $curl->request('POST', $link, $requestData);	

		if (!$response || !$response->body){
			throw new Exception(JText::_("COM_PAYCART_ADMIN_COUNTRY_IMPORT_FAILED"));
		}
		
		$states = json_decode($response->body,true);
		$model = PaycartFactory::getModel('state');
		foreach ($states as $key=>$title){
			$stateData = array();
	
			$stateData['published']   = 1;
			$stateData['lang_code']   = $lang_code;
			$stateData['isocode']	  = $key;
			$stateData['title']	  	  = $title;
			$stateData['country_id']  = $data['country_id'];
			  
			$model->save($stateData);
		}
	}
}