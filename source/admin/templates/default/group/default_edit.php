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
paycart.admin.group = {};

// will be used to maintain the counter of grouprule added
paycart.admin.group.ruleCounter = <?php echo $ruleCounter;?>;

(function($){	
	paycart.admin.group.addrule = function(ruleType){
		var ruleClass = $('#paycart-grouprule-list	').val();		
		var url = 'index.php?option=com_paycart&view=group&task=addRule&ruleType='+ruleType+'&ruleClass='+ruleClass+'&counter='+paycart.admin.group.ruleCounter;

		//@PCTODO : add one more parametere in url to escape from caching of browser

		paycart.ajax.go(url, {});
	};	

	$(document).ready(function(){
		<?php foreach($ruleScripts as $script):?>
			<?php echo $script;?>
		<?php endforeach;?>
	});
})(paycart.jQuery);
</script>

<div class="pc-product-wrapper clearfix">
<div class="pc-group row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=group'); 
	?>
</div>
<!-- ADMIN MENU -->


<div class="span10">
<div class="row-fluid">	
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form">
		<div class="row-fluid">
			<div class="span3">
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_GROUP_DETAILS_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_GROUP_DETAILS_HEADER_MSG');?>
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
							<?php $field = $form->getField('type') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>		
		
		<hr/>
		
		<div class="row-fluid">
			<div class="span3">
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_GROUP_RULES_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_GROUP_RULES_HEADER_MSG');?>
				</div>
			</div>
			<div class="span9">
				<div class="control-group">					
					<div class="controls">
						<select id="paycart-grouprule-list">
							<option value=""><?php echo JText::_('COM_PAYCART_ADMIN_SELECT');?></option>
							<?php foreach($group_rules as $class => $rule):?>
								<option value="<?php echo $class;?>"><?php echo $rule->title;?></option>
							<?php endforeach;?>
						</select>
						
						<button class="btn btn-primary" id="paycart-grouprule-add" type="button" onClick="paycart.admin.group.addrule('<?php echo $form->getvalue('type');?>');">
							<?php echo JText::_('Add New');?>
						</button>
					</div>	
				</div>
				
				<fieldset class="form-horizontal">
					<div id="paycart-grouprule-config">
						<?php echo $ruleHtml;?>
					</div>
				</fieldset>
			</div>
		</div>
		<?php echo $form->getInput('group_id'); ?>
		<input type="hidden" name="task" value="" />
	</form>
</div>
</div>
</div>
</div>
<?php 
	