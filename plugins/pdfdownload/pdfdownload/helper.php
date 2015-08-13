<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		support+paycart@readybytes.in
 * @author 		Puneet Singhal, mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * buyer Helper
 * @author Garima
 *
 */
class PaycartHelperPdfdownload extends PaycartHelper
{
	
	/**
	 * get the result invoices to be generated
	 */
	function getResultForInvoices()
	{
		$mysess  	  = Rb_Factory::getSession();
		$application  = Rb_Factory::getApplication()->input;
		$txnDateFrm   = $mysess->get('pdfdownload_txnDateFrm',null);
		$txnDateTo    = $mysess->get('pdfdownload_txnDateTo',null);
		$status 	  = $application->get('pdfdownload_status',Paycart::STATUS_CART_PAID);
		$invoiceKey   = 0;
		//get variables from URL 
		if( empty($txnDateFrm) || empty($txnDateTo )){
			$txnDateFrm  = $application->get('pdfdownload_txnDateFrm',null);
			$txnDateTo  = $application->get('pdfdownload_txnDateTo',null);
			
			if (empty($txnDateFrm) && !empty($txnDateTo)){
				$txnDateFrm = $txnDateTo;
			}
			elseif (empty($txnDateTo) && !empty($txnDateFrm))
				$txnDateTo  = $txnDateFrm; 
				
			//also set in session for future use
			$mysess->set('pdfdownload_txnDateFrm',$txnDateFrm);
			$mysess->set('pdfdownload_txnDateTo',$txnDateTo);
		}
		
	    $result['totalResult'] = $mysess->get('pdfdownload_total',null);		
		//get total result only for the first time,deciding it through variable 'pdfdownload_deleteFiles'
		if(JRequest::getVar('pdfdownload_deleteFiles',0)){
			$result['totalResult'] = $this->getTotalResults($txnDateFrm,$txnDateTo, $status);
			$mysess->set('pdfdownload_total',$result['totalResult']);			
		}
		
		$result['result'] = $this->_getResultInvoices($txnDateFrm,$txnDateTo,$status);
		return $result;
	}
	
	/**
	 * get total result exist in between the given dates
	 */
	function getTotalResults($txn_from , $txn_to,$status)
	{	
		//set lock variable so that files/folders can be restricted to delete through cron  
	    Rb_Factory::getSession()->set('pdfdownload_lock',Rb_Factory::getSession()->get('pdfdownload_lock',0)+1);
		$query  = new Rb_Query();
		
		if(!empty($txnDateFrm) && !empty($txnDateTo)){
					 $query->where('Date(`paid_date`) >= "'.$txn_from.'"')
				           ->where('Date(`paid_date`) <= "'.$txn_to.'"');
				}
		
		return  count($query->select('DISTINCT `cart_id`')
		                ->from('`#__paycart_cart`')
		               ->where('`status` = "'.$status.'"')
		                ->dbLoadQuery()
		                ->loadAssocList());
	}
	
	
/**
	 * build condition to be applied in query
	 */
	function _getResultInvoices($txnDateFrm,$txnDateTo,$status)
	{
		$mysess  	= Rb_Factory::getSession();
		//need to ask this from users
		$limit 	    = PAYCART_PDF_EXPORT_LIMIT;
		$limitStart = $mysess->get('pdfdownload_start',0);
		$records    = $this->getInvoiceWithinDates($txnDateFrm , $txnDateTo, $limitStart, $limit,$status);
		return $records;
	}
	
    function getInvoiceWithinDates($startDate, $endDate, $limitStart, $limit,$status)
		{
			$query = new Rb_Query();
			
			if(!empty($txnDateFrm) && !empty($txnDateTo)){
				 $query->where('Date(`paid_date`) >= "'.$txn_from.'"')
			           ->where('Date(`paid_date`) <= "'.$txn_to.'"');
			}
			
			$query->select('*')
			      ->from('`#__paycart_cart`')
			      ->where('`status` = "'.$status.'"')
			      ->limit($limit,$limitStart);
			
			return $query->dbLoadQuery()->loadObjectList('cart_id');
		}
	
	
}
