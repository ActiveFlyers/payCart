<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );
echo $this->loadTemplate('edit_js');
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
		
		<!-- LANGUAGE SWITCHER -->
		<?php 
			if(PAYCART_MULTILINGUAL){
				if($record_id){
					$displayData = new stdClass();
					$displayData->uri  = $uri.'&id='.$record_id;
					echo Rb_HelperTemplate::renderLayout('paycart_language_switcher', $displayData);
				}
				
				$lang_code = PaycartFactory::getPCCurrentLanguageCode();
				$flag = '<span class="pull-left pc-language">'.PaycartHtmlLanguageflag::getFlag($lang_code).' &nbsp; '.'</span>';
			}
			else{
				$flag = '';
			}
		?>

			<div class="row-fluid">
				
				<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="pc-form-validate">	
					
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
										<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>
									</div>
								
									<?php $field = $form->getField('description') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
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
								<div class="span6">
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
						
					</div>
				
				
					<?php echo $form->getInput('paymentgateway_id'); ?>
					<?php echo $form->getInput('paymentgateway_lang_id'); ?>
					<?php echo $form->getInput('lang_code'); ?>
					<input type="hidden" name="task" value="" />
			</form>
		</div>
	</div>		
</div>	
</div>
<?php  
	