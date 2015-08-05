<?php 
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	pdfDownload
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

$downloadUrl = JRoute::_('index.php?option=com_paycart&view=pdfdownload&task=sitedownload&action=sitePdfDownload&cart_id='.$cartId.'&key='.$secure_key); ?>

<a class="pull-right btn btn-primary btn-large"  onclick="rb.url.redirect('<?php echo $downloadUrl; ?>'); return false;" >
	<h5><?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_FRONT_INVOICE_DOWNLOAD_LINK');?></h5>
</a> 
<?php  

