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
<div class="text-center">
<a class="btn btn-info "  onclick="rb.url.redirect('<?php echo $downloadUrl; ?>'); return false;" >
	<h5><i class="fa fa-download"></i> <?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_FRONT_INVOICE_DOWNLOAD_LINK');?></h5>
</a> 
</div>
<?php  

