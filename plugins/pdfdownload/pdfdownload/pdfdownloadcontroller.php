<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	pdfdownload
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

class PaycartadminControllerPdfdownload extends Rb_Controller
{
	public function getModel($name = '', $prefix = '', $config = array())
		{
			return null;
		}
		
		public function download()
		{
			return true;
		}
		
		function display($cachable = false, $urlparams = array())
		{
			return true;
		}
		
}