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

<div class="row-fluid">	
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form">
		<div class="span6">
			<fieldset class="form-horizontal">
				<h3><?php echo Rb_Text::_('Detial' ); ?></h3>
				<hr>		          
				
	            <div class="control-group">
					<?php echo $form->getLabel('title'); ?>
					<div class="controls"><?php echo $form->getInput('title'); ?></div>	
				</div>
				
				<div class="control-group">
					<?php echo $form->getLabel('desciption'); ?>
					<div class="controls"><?php echo $form->getInput('description'); ?></div>	
				</div>
				
				<div class="control-group">					
					<?php echo $form->getLabel('published'); ?>
					<div class="controls"><?php echo $form->getInput('published'); ?></div>	
				</div>
				
				<div class="control-group">
					<?php echo $form->getLabel('type'); ?>
					<div class="controls"><?php echo $form->getInput('type'); ?></div>						
				</div>	
			</fieldset>
		</div>
		
		<div class="span6">
			<div class="control-group">
				<?php echo JText::_('COM_PAYCART_GROUP_TYPE') ?>
				<div class="controls">
					<select id="paycart-grouprule-list">
						<option value=""><?php echo Rb_Text::_('JSELECT');?></option>
						<?php foreach($group_rules as $class => $rule):?>
							<option value="<?php echo $class;?>"><?php echo $rule->title;?></option>
						<?php endforeach;?>
					</select>
					
					<button class="btn btn-primary" id="paycart-grouprule-add" type="button" onClick="paycart.admin.group.addrule('<?php echo $form->getvalue('type');?>');">
						<?php echo Rb_Text::_('Add New');?>
					</button>
				</div>	
			</div>
			
			<fieldset class="form-horizontal">
				<div id="paycart-grouprule-config">
					<?php echo $ruleHtml;?>
				</div>
			</fieldset>
		</div>
		
		<?php echo $form->getInput('group_id'); ?>
		<input type="hidden" name="task" value="" />
	</form>
</div>
<?php 
	