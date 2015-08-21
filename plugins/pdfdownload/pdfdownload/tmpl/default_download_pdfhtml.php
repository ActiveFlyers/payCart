<?php 
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	pdfDownload
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

$downloadUrl = JRoute::_('index.php?option=com_paycart&view=pdfdownload&task=sitedownload&action=sitePdfDownload&cart_id='.$cartId); ?>
<div class="text-right" >
<a class="pull-right"  onclick="rb.url.redirect('<?php echo $downloadUrl; ?>'); return false;" href="#" >
	<h1><span class="muted" style="font-weight: 300; padding: 0px 10px 10px;"><i class="fa fa-1x  fa-download"></i> <?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_FRONT_INVOICE_DOWNLOAD_LINK');?></span></h1>
</a> 
</div>
<?php  

