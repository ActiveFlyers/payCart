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
<script>
paycart.admin.discountrule = {};

(function($){
	paycart.admin.discountrule.getProcessorConfig = function(){
		var processor_classname = $('[data-pc-discountrule="processor"] select').val();
		if(processor_classname.length > 0){
			var url  = 'index.php?option=com_paycart&view=discountrule&task=getProcessorConfig&processor_classname='+processor_classname+'&discountrule_id='+<?php echo $form->getValue('discountrule_id');?>;
			paycart.ajax.go(url);
		}
		else{
			$('#pc-discountrule-processorconfig').html('');
		}	
	};

	$(document).ready(function(){
		paycart.admin.discountrule.getProcessorConfig();
		$('[data-pc-discountrule="processor"] select').change(function(){					
			paycart.admin.discountrule.getProcessorConfig();
		});
	});
})(paycart.jQuery);
</script>

<div class="pc-product-wrapper clearfix">
<div class="pc-discountrule row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=discountrule'); 
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
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_DISCOUNTRULE_DETAILS_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_DISCOUNTRULE_DETAILS_HEADER_MSG');?>
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
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_DISCOUNTRULE_CONFIG_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_DISCOUNTRULE_CONFIG_HEADER_MSG');?>
				</div>
			</div>
			<div class="span9">
				<div class="row-fluid">
					<div class="span6">
						<?php $field = $form->getField('amount') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
					<div class="span6">
						<?php $field = $form->getField('is_percentage') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
				</div>
				<br/>
				<div class="row-fluid">
					<div class="span6">
						<?php $field = $form->getField('is_clubbable') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
					<div class="span6">
						<?php $field = $form->getField('is_successive') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
				</div>		
				<br/>
				<div class="row-fluid">
					<div class="span6">
						<?php $field = $form->getField('usage_limit') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
					<div class="span6">
						<?php $field = $form->getField('buyer_usage_limit') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
				</div>	
					
				<br/>
				
				<div class="row-fluid">
					<div class="span6">
						<?php $field = $form->getField('start_date') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
					<div class="span6">
						<?php $field = $form->getField('end_date') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
				</div>
				
				<br/>
				
				<div class="row-fluid">
				
					<div class="span6">
						<?php $field = $form->getField('sequence') ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?> </div>
							<div class="controls"><?php echo $field->input; ?></div>								
						</div>
					</div>
					
				</div>
									
				<br/>
				
				
				<hr/>
				
				<div class="row-fluid">		
					<?php $field = $form->getField('apply_on') ?>
					<div><?php echo Jtext::_('COM_PAYCART_ADMIN_DISCOUNTRULE_APPLY');?> <?php echo JText::_('COM_PAYCART_ADMIN_DISCOUNTRULE_ON');?></div>
					<div class="control-group">						
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
				</div>	
				
				<div class="row-fluid">
					<div><?php echo Jtext::_('COM_PAYCART_ADMIN_DISCOUNTRULE_BY');?></div>
					<div class="control-group">
						<div class="controls" data-pc-discountrule="processor">
							<?php $field = $form->getField('processor_classname') ?>
							<?php echo $field->input; ?>
						</div>	
					</div>
					
					<fieldset class="form-horizontal">
						<div id="pc-discountrule-processorconfig">
							&nbsp;
						</div>
					</fieldset>
				</div>
			</div>
		</div>
		
		<hr />
		
		<div class="row-fluid">
			<div class="span3">
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_DISCOUNTRULE_GROUPS_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_DISCOUNTRULE_GROUPS_HEADER_MSG');?>
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
		<?php echo $form->getInput('discountrule_id'); ?>
		<input type="hidden" name="task" value="" />
		<?php echo $form->getInput('discountrule_lang_id');?>
		<?php echo $form->getInput('lang_code');?>
	</form>
</div>
</div>
</div>
</div>
<?php 
	