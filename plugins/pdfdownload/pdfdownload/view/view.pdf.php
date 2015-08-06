<?php

/**
* @copyright	Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+PAYCART@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 *Pdf Export Html View
 * @author Garima Agal
 */

// constant for limit
define('PAYCART_PDF_EXPORT_LIMIT', 5);

require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewPdfDownload extends PaycartAdminBaseViewPdfdownload
{
	
	function display($tpl=null)	{	
			return true;
		}
	
	protected function _basicFormSetup($task){
			return true;
		}
	
	public function download()
	{
		$action    = JRequest::getcmd('action');
		//for backend
		
			if($action == 'adminPdfDownload' ){
			
				if(!$this->doAdminAction())
					return ;
				if(!$this->createZipOfFiles()){
					Rb_Factory::getApplication()->enqueueMessage(JText::_('PLG_PAYCART_PDFDOWNLOAD_NO_RESULT_FOUND'), 'warning');
				}
				return;
			}
			//for frontend with action sitePdfDownload
			elseif ($action == 'sitePdfDownload'){
				 $cart_id	   	= Rb_Factory::getApplication()->input->get('cart_id','');
				 if(!$cart_id){
						Rb_Factory::getApplication()->enqueueMessage(JText::_('PLG_PAYCART_PDFDOWNLOAD_NO_CART_ID'), 'error');
						Rb_Factory::getApplication()->redirect('index.php?option=com_paycart&view=cart&task=complete&cart_id='.$cart_id);
						return ;
					}
					$cart 			= PaycartCart::getInstance($cart_id);
				$this->_streamPdf($this->doSiteAction($cart),$cart_id);
				return ;
			}
	}
	
	function doRefresh()
	{
		$app 		 = PaycartFactory::getApplication();
		$currentUrl  = JURI::getInstance();
		// set task for generate pdf files for next slote
		$currentUrl->setVar('task', 'download');
		$redirectUrl = $currentUrl->toString();
       	$app->redirect($currentUrl);
	}
	
		function getTemplate($obj,$resultCount)
	{

		//get instances
		$cart		= PaycartCart::getInstance($obj->cart_id);
		$buyer		= PaycartBuyer::getInstance($obj->buyer_id);

		$cartHelper	= PaycartFactory::getHelper('cart');
		$data = $cartHelper->getDetailedCartData($obj->cart_id);
		
		$address  	= $data['cart']->getBillingAddress(true);
		$shippingAddress  = $data['cart']->getShippingAddress(true);
		$config = PaycartFactory::getConfig();
		$pluginData = JPluginHelper::getPlugin('paycart','pdfdownload');
		$pluginData = json_decode($pluginData->params);
		
		$this->assign('note', $pluginData->note);
			
		$this->assign('product_particular',		$data['product_particular']);
		$this->assign('shipping_particular',	$data['shipping_particular']);
		$this->assign('promotion_particular',	$data['promotion_particular']);
		$this->assign('duties_particular',		$data['duties_particular']);
		$this->assign('cart',$data['cart']);
		$this->assign('usageDetails', $data['usageDetails']);
		$this->assign('transactions',$data['transactions']);
		$this->assign('billingAddress' , $address);
		$this->assign('shippingAddress' , $shippingAddress);
		$this->assign('config_data',$config);
		$this->assign('buyer', 				$buyer);
		$this->assign('resultCount', $resultCount);
		$this->assign('rb_invoice', $obj->invoice);
		
		return $this->loadTemplate('pdfcontent');
		//return $this->_render('pdfcontent',null,'default');
	}
	

	/**
	 *  Creating folder of given pdf files
	 */
	function createFolder($pdf, $count = 0 )
	{
		$buyer	  = Rb_Factory::getUser($buyerId);
		$dir_path = dirname(dirname(dirname(__FILE__))).'/pdfexport'.$buyer->id;
		if(!is_dir($dir_path)){
			mkdir($dir_path);
		} 
		ob_clean();	
		
		$content = $pdf->output('order_'.$count.'.pdf', 'S');
		file_put_contents($dir_path.'/order'.$count.'.pdf', $content);
		return ;
	}	

	/**
	 * delete folder and contained files
	 */
	function deleteFolder($dirPath)
	{
		if(is_dir($dirPath)){	
			$files = JFolder::files($dirPath);
			foreach ($files as $file){
				unlink($dirPath.'/'.$file);
			}
			JFolder::delete($dirPath);
		}
		return true;	
	}
	
	function deleteUserFiles($buyerId = null)
	{   
		$buyer 	= Rb_Factory::getUser($buyerId);
		//delete files and folder before and after processing
		$this->deleteFolder(dirname(dirname(dirname(__FILE__))).'/pdfexport'.$buyer->id);
		return true;
	}
	
	/**
	 * Create zip of pdf files 
	 */
	function createZipOfFiles()
	{
		$buyer 			   = Rb_Factory ::getUser();
		$dir_path 		   = dirname(dirname(dirname(__FILE__))).'/pdfexport'.$buyer->id.'/';
		$archive_file_name = $dir_path."pdfinvoices".$buyer->id.".zip";
		$files             = JFolder::files($dir_path);
		$zip 			   = new JArchive();
		$zip_adapter       = JArchive::getAdapter('zip'); // compression type
		
		$filesToZip 	   = array();
		//create file data required for zip_adapter
		foreach ($files as $file){
			$data 		   = JFile::read($dir_path.'/'.$file);
			$filesToZip[]  = array('name'=> $file, 'data'=>$data);
		}
		
		//create the file and throw the error if unsuccessful
		if (!$zip_adapter->create($archive_file_name,$filesToZip)) {
          exit('Error creating zip file');
        }
		
	    //then send the headers to force download the zip file
	    if(file_exists($archive_file_name)){
		    header("Content-type: application/zip");
		    header("Content-Disposition: attachment; filename=pdfInvoices.zip");
		    header("Pragma: no-cache");
		    header("Expires: 0");
		    readfile("$archive_file_name");
		    exit;
	    }
	    return true;
	}

	/**
	 * clear all session variables
	 */
	function clearSessionVariables()
	{
		$mysess   = Rb_Factory::getSession();
		$sessVars = array('pdfdownload_start','pdfdownload_count','pdfdownload_deleteFiles',
						  'pdfdownload_txnDateFrm','pdfdownload_txnDateTo','pdfdownload_total');
		foreach ($sessVars as $var){
			$mysess->clear($var);
		}
		return true;	
	}
	
	
	
/**
	 * start processing for invoices
	 */
	function doAdminAction()
	{
		$mysess      = Rb_Factory::getSession();
		$count	     = $mysess->get('pdfdownload_count',1);
		$deleteFiles = Rb_Factory::getApplication()->input->get('pdfdownload_deleteFiles',0,'GET');
		$limitStart  = $mysess->get('pdfdownload_start',0);
		//delete folders related to the login user,if exist
		if($deleteFiles){
			$this->deleteUserFiles();
			$this->clearSessionVariables();
		}
		
		//if invoice key is given then use site side's function
		$cartId = Rb_Factory::getApplication()->input->get('pdfdownload_cartId',0);
	
		if($cartId){
			try{
				$cart = PaycartCart::getInstance($cartId);
			}
			catch (Exception $e) {
				Rb_Factory::getApplication()->enqueueMessage(JText::_('PLG_PAYCART_PDFDOWNLOAD_NO_RESULT_FOUND'), 'warning');
				Rb_Factory::getApplication()->redirect("index.php?option=com_paycart&view=pdfdownload");
				return ;
			}
				$this->_streamPdf($this->doSiteAction($cart), $cartId);
				return;
			
		}
			
		
		$pdf_helper = $this->getHelper('pdfdownload');
		
		//else get result for in between given dates
		$result 	= $pdf_helper->getResultForInvoices();
		if(isset($result['result']) && !empty($result['result'])){
			$this->createFolder($this->getContentForPdf($result['result']), $count);
		}else {
			Rb_Factory::getApplication()->enqueueMessage(JText::_('PLG_PAYCART_PDFDOWNLOAD_NO_RESULT_FOUND'), 'warning');
			Rb_Factory::getApplication()->redirect("index.php?option=com_paycart&view=pdfdownload");
			return;
		}

		//if total result is greater than processed result then set next limit and refresh the page 
		if(isset($result['totalResult']) && 
		         $result['totalResult']   >  ($limitStart + count($result['result']))){
			$mysess->set('pdfdownload_count',++$count);
			$mysess->set('pdfdownload_start', ($limitStart + count($result['result'])));
			$this->doRefresh();
			exit;
		}
		return true;
	}
	
	/**
	 * stream the pdf
	 */
	protected function _streamPdf($pdf, $cart_id)
	{
		ob_clean();
		$pdf->output('order_'.$cart_id.'.pdf', 'D');
		exit;
	}
	
	/**
	 * set content in a pdf file and save it in a folder
	 */
	function getContentForPdf($result)
	{
	   require_once dirname(dirname(__FILE__)).'/mpdf'.'/mpdf.php';
	   $mpdf     = new mPDF("en-GB-x","A4","","",10,10,10,10,6,3);
	   
	  	// $mpdf->SetDirectionality('rtl');
	   $mpdf->autoScriptToLang = true;
	   $this->assign('mpdf' , $mpdf);
		$contents = $this->includeHeader();
		if($result){
			foreach($result as $row){
				
				$content = $this->getTemplate($row,count($result));
				if(!$content){
					continue;
				}
				$contents .= $content;
			}
		}
		
		//create pdf file
		$contents.= "</body></html>";
		$mpdf->WriteHTML($contents);
		
		if(count($result)>1){
			$mpdf->DeletePages(1,1);
		}
		return $mpdf;
	}
	
	/**
	 * Start processing invoice for front end
	 */
	function doSiteAction($cart)
	{	
		$record 				= new stdClass();
		$record->cart_id   	= $cart->getId();
		$record->created_date 	= null;        
		$record->created_date 	= $cart->getPaidDate();
		$record->invoice     =  $cart->getInvoiceData();
		
		
		
		$pdf = $this->getContentForPdf($result = array($record) , 1);
		return $pdf;
	}
	
	/**
	 * set header of pdf file 
	 */
	function includeHeader()
	{
		return $this->loadTemplate('pdfheader');
	}
}

