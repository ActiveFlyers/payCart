<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
* @author		rimjhim
*/

defined('_JEXEC') or die( 'Restricted access' );
?>
<script>
paycart.admin.shippingrule = {};

(function($){
	paycart.admin.shippingrule.getProcessorConfig = function(){
		var processor_classname = $('[data-pc-shippingrule="processor"] select').val();
		if(processor_classname.length > 0){
			var url  = 'index.php?option=com_paycart&view=shippingrule&task=getProcessorConfig&processor_classname='+processor_classname+'&shippingrule_id='+<?php echo $form->getValue('shippingrule_id');?>;
			paycart.ajax.go(url);
		}
		else{
			$('#pc-shippingrule-processorconfig').html('');
		}	
	};

	$(document).ready(function(){
		paycart.admin.shippingrule.getProcessorConfig();
		$('[data-pc-shippingrule="processor"] select').change(function(){					
			paycart.admin.shippingrule.getProcessorConfig();
		});
	});
})(paycart.jQuery);
</script>

<div class="pc-shippingrule-wrapper clearfix">
<div class="pc-shippingrule row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=shippingrule'); 
	?>
</div>
<!-- ADMIN MENU -->


<div class="span10">
<div class="row-fluid">	
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form">
		<div class="row-fluid">
			<div class="span3">
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_SHIPPINGRULE_DETAILS_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_SHIPPINGRULE_DETAILS_HEADER_MSG');?>
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
						<?php $field = $form->getField('message') ?>
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
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_SHIPPINGRULE_CONFIG_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_SHIPPINGRULE_CONFIG_HEADER_MSG');?>
				</div>
			</div>
			<div class="span9">
				<div class="row-fluid">
					<div class="span6">
						<?php $field = $form->getField('grade') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>						
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>	
					
					<div class="span6">		
						<?php $field = $form->getField('tracking_url') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>				
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>	
						
				</div>
				<div class="row-fluid">
					<div class="span6">		
						<?php $field = $form->getField('min_days') ?>
						<div class="control-group">			
							<div class="control-label"><?php echo $field->label; ?> </div>			
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
					
					<div class="span6">
						<?php $field = $form->getField('max_days') ?>
						<div class="control-group">			
							<div class="control-label"><?php echo $field->label; ?> </div>			
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
				</div>
				
				<div class="row-fluid">					
					<div class="control-group">
						<?php $field = $form->getField('processor_classname') ?>
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls" data-pc-shippingrule="processor">
							<?php echo $field->input; ?>
						</div>	
					</div>
					
					<fieldset class="form-horizontal">
						<div id="pc-shippingrule-processorconfig">
							&nbsp;
						</div>
					</fieldset>
				</div>
			</div>
		</div>
		
		<hr/>
		
		<div class="row-fluid">
			<div class="span3">
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_SHIPPINGRULE_GROUPS_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_SHIPPINGRULE_GROUPS_HEADER_MSG');?>
				</div>
			</div>
			<div class="span9">
				<div class="row-fluid">				
					<div class="control-group">
						<?php $field = $form->getField('_buyergroups') ?>
						<?php // an hidden varialbe wih same name so that it will be posted if none is selected ?>
						<input type="hidden" name="<?php echo $field->name; ?>" value="">
						<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_GROUPRULE_TYPE_BUYER_SELECTION_MSG'); ?></div>
						<div class="controls"><?php echo $field->input; ?></div>	
					</div>
				</div>
				<br/>
				<div class="row-fluid"><?php echo JText::_('COM_PAYCART_ADMIN_AND');?></div>
				<br/>
				<div class="row-fluid">
					<div class="control-group">
						<?php $field = $form->getField('_productgroups') ?>
						<?php // an hidden varialbe wih same name so that it will be posted if none is selected ?>
						<input type="hidden" name="<?php echo $field->name; ?>" value="">
						<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_GROUPRULE_TYPE_PRODUCT_SELECTION_MSG'); ?></div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>
				<br/>
				<div class="row-fluid"><?php echo JText::_('COM_PAYCART_ADMIN_AND');?></div>
				<br/>
				<div class="row-fluid">
					<div class="control-group">
						<?php $field = $form->getField('_cartgroups') ?>
						<?php // an hidden varialbe wih same name so that it will be posted if none is selected ?>
						<input type="hidden" name="<?php echo $field->name; ?>" value="">
						<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_GROUPRULE_TYPE_CART_SELECTION_MSG'); ?></div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>			
			</div>
		</div>
		<?php echo $form->getInput('shippingrule_id'); ?>
		<?php echo $form->getInput('shippingrule_lang_id');?>
		<?php echo $form->getInput('lang_code');?>
		<input type="hidden" name="task" value="" />
	</form>
</div>
</div>
</div>
</div>
<?php 
	