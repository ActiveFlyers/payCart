<?php 
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license	    GNU/GPL, see LICENSE.php
* @package	    PayPlans
* @subpackage	pdfDownload
* @contact 	    payplans@readybytes.in
*/

defined('_JEXEC') or die('Restricted access'); ?>

<!-- Script to reset the fields and post the values through hidden variables-->
<script type="text/javascript">
	function resetVariables(event)
	{
		var pdfdownload_cartId =  paycart.jQuery('#pdfdownload_cartId').val();
		var pdfdownload_txnDateFrm =  paycart.jQuery('#pdfdownload_txnDateFrm').val();
		var pdfdownload_txnDateTo  =  paycart.jQuery('#pdfdownload_txnDateTo').val();
		//var pdfdownload_status =  paycart.jQuery('#pdfdownload_status').val();

		//if all fields are left blank
//		if(pdfdownload_cartId == "" && pdfdownload_txnDateFrm == "" && pdfdownload_txnDateTo == "" && pdfdownload_status == ""){
		if(pdfdownload_cartId == "" && pdfdownload_txnDateFrm == "" && pdfdownload_txnDateTo == ""){
			alert("<?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_ENTER_EITHER_KEY_OR_DATES');?>");
			event.preventDefault();
			return false;
		}
		paycart.jQuery("#invoiceForm").submit();
	}
</script>
<div id="pdfdownload">
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=pdfdownload'); 
	?>
</div>

<div class="span10">
<form action="<?php echo JRoute::_( 'index.php?option=com_paycart&view=pdfdownload&task=download&action=adminPdfDownload&pdfdownload_deleteFiles=1',false ); ?>" method="post" id="invoiceForm" name="invoiceForm">
<div class="row-fluid form-horizontal">	

		<div class="control-group">
			<div class="control-label"><?php echo JText::_("PLG_PAYCART_PDFDOWNLOAD_CARTID"); ?></div>
			<div class="controls">
				<input type="text" value="" size="20" name="pdfdownload_cartId" id="pdfdownload_cartId">
			</div>
		</div>
		  
<!--		<div class="control-group">
			<div class="control-label"><?php // echo JText::_("PLG_PAYCART_PDFDOWNLOAD_STATUS"); ?></div>
			<div class="controls">
				<?php //$status = Paycart::getCartStatus();?>
				<?php //unset($status[Paycart::STATUS_CART_DRAFTED])?>
				<?php // echo JHtml::_('select.genericlist', $status, "pdfdownload_status");?>
			</div>
		</div> -->
		
		<h6>Transaction Date</h6>
				
		<div class="control-group">
			<div class="control-label"><?php echo JText::_("PLG_PAYCART_PDFDOWNLOAD_FROM"); ?></div>
				
			<div class="controls">
				<?php 
			          echo JHTML::_('behavior.calendar');
			          echo JHTML::_('calendar', '', 'pdfdownload_txnDateFrm', 'pdfdownload_txnDateFrm','%Y-%m-%d', array('class'=>'inputbox', 'maxlength'=>'19'));
		        ?>
				
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label"><?php echo JText::_("PLG_PAYCART_PDFDOWNLOAD_TO"); ?></div>
				
			<div class="controls">
				<?php 
			          echo JHTML::_('behavior.calendar');
			          echo JHTML::_('calendar', '', 'pdfdownload_txnDateTo', 'pdfdownload_txnDateTo','%Y-%m-%d', array('class'=>'inputbox', 'maxlength'=>'19'));
		        ?>	
		        <button  onclick="resetVariables(event)" name="save" id="submitbtn" class="btn btn-primary" title="<?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_DOWNLOAD_PDF_BTN');?>">
				<i class="icon-download icon-white"></i>&nbsp;<?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_INVOICE_DOWNLOAD');?>
				</button>
			</div>
		</div>
</div>
	
<?php  echo JHTML::_ ( 'form.token' ); ?>
</form>
</div>
</div>
<?php 