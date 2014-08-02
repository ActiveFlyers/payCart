<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );
?>

<div class="pc-paymentgateway-wrapper clearfix">

	<div class="pc-paymentgateway row-fluid">

		<!-- ADMIN MENU -->
		<div class="span2">
		<?php
			$helper = PaycartFactory::getHelper('adminmenu');	
			echo $helper->render('index.php?option=com_paycart&view=paymentgateway');
		?>
		</div>

		<!-- CONTENT START -->
		<div class="span10">
		
			<div class="row-fluid">
				
				<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form">
				
					<div class="row-fluid">
						
						<!-- Header Message				-->
						<div class="span3">
							<h2><?php echo JText::_('COM_PAYCART_ADMIN_PAYMENTGATEWAY_DETAILS_HEADER');?></h2>
							
							<div>
								<?php echo JText::_('COM_PAYCART_ADMIN_PAYMENTGATEWAY_DETAILS_HEADER_MESSAGE');?>
							</div>
							
						</div>
						
						<div class="span9">
						
							<fieldset class="form">
							
								<div class="row-fluid">
								
									<?php $field = $form->getField('title') ?>					
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>
									</div>
								
									<?php $field = $form->getField('description') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>
									</div>
									
								</div>
								
								<div class="row-fluid">
									<div class="span6">
										<?php $field = $form->getField('published') ?>
										<div class="control-group">
											<div class="control-label"><?php echo $field->label; ?> </div>
											<div class="controls"><?php echo $field->input; ?></div>
										</div>
									</div>
									
									<div class="span6">
									</div>
								
								</div>
							
							</fieldset>
						</div>
						
					</div>
				
					<hr />
				
					<div class="row-fluid">
						
						<div class="span3">
							<h2><?php echo JText::_('COM_PAYCART_ADMIN_PAYMENTGATEWAY_CONFIG_HEADER');?></h2>
							
							<div>
								<?php echo JText::_('COM_PAYCART_ADMIN_PAYMENTGATEWAY_CONFIG_HEADER_MSG');?>
							</div>
						</div>
						
						<div class="span9">
						
							<div class="row-fluid">
								<div><?php echo Jtext::_('COM_PAYCART_ADMIN_PAYMENTGATEWAY_BY');?>
								</div>
								
								<div class="control-group">
									<div class="controls" data-pc-paymentgateway="processor">
										<?php $field = $form->getField('processor_type') ?>
										<?php echo $field->input; ?>
									</div>
								</div>
								
								<div id="pc-paymentgateway-processorconfig">
								</div>
								
							</div>
						</div>
						
					</div>
				
				
					<?php echo $form->getInput('paymentgateway_id'); ?>
					<input type="hidden" name="task" value="" />
			</form>
		</div>
	</div>		
</div>
	
</div>
<script>
	(function($) {

		paycart.admin.paymentgateway = {};
		
		paycart.admin.paymentgateway.getProcessorConfigHtml = function()
		{
			var data	=	{
								'processor_type' 	:	$('#paycart_form_processor_type').val(),
								'processor_id'		:	$('#paycart_form_paymentgateway_id').val()
							};
			var url		=	'index.php?option=com_paycart&view=paymentgateway&task=getConfigHtml';
			 
			paycart.ajax.go(url, data);
		};

		paycart.admin.paymentgateway.setProcessorConfigHtml = function(response_data)
		{
			$('#pc-paymentgateway-processorconfig').html(response_data['html']);
			
		};

		$(document).ready(function(){

			paycart.admin.paymentgateway.getProcessorConfigHtml();
			
			$('[data-pc-paymentgateway="processor"] select').change(function() {					
				paycart.admin.paymentgateway.getProcessorConfigHtml();
			});
			
		});
		
	})(paycart.jQuery);
	
</script>
<?php  
	