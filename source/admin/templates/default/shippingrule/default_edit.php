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
							<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
							<div class="controls">
								<?php echo $field->input; ?>
								<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
							</div>								
						</div>
						<?php $field = $form->getField('description') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
						<?php $field = $form->getField('message') ?>
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
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_SHIPPINGRULE_SHIPPING_METHOD_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_SHIPPINGRULE_SHIPPING_METHOD_HEADER_MSG');?>
				</div>
			</div>
			<div class="span9">
				<div class="row-fluid">
						<div class="control-group">
							<?php $field = $form->getField('processor_classname') ?>
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls" data-pc-shippingrule="processor">
								<?php echo $field->input; ?>
								<div class="pc-error clearfix" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_SHIPPINGRULE_PROCESSOR_REQUIRED');?></div>
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
		
		<hr />
		
		<div class="row-fluid">
			<div class="span3">
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_SHIPPINGRULE_DELIVERY_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_SHIPPINGRULE_DELIVERY_HEADER_MSG');?>
				</div>
			</div>
			<div class="span9">
				<div class="row-fluid">
					<div class="span6">
						<?php $field = $form->getField('packaging_weight') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?></div>						
							<div class="controls">
								<span class="input-prepend">
									<span class="add-on"><?php echo PaycartFactory::getConfig()->get('catalogue_weight_unit')?></span>
									<input type="text" class="input-block-level" name="paycart_shippingrule_form[packaging_weight]" value="<?php echo $formatter->weight($rule->getPackagingWeight());?>">			
								</span>
							</div>								
						</div>
					</div>	
					
					<div class="span6">		
						<?php $field = $form->getField('handling_charge') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>				
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>	
						
				</div>
				<div class="row-fluid">
					<div class="span6">		
						<?php $field = $form->getField('delivery_min_days') ?>
						<div class="control-group">			
							<div class="control-label"><?php echo $field->label; ?> </div>			
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
					
					<div class="span6">
						<?php $field = $form->getField('delivery_max_days') ?>
						<div class="control-group">			
							<div class="control-label"><?php echo $field->label; ?> </div>			
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<?php $field = $form->getField('delivery_grade') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>						
						</div>
					</div>
					
					<div class="span6">&nbsp;</div>
				
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
	