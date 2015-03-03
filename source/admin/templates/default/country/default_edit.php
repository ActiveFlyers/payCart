<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

//@PCTODO: mention all populated variables

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

JHtml::_('behavior.formvalidation');
echo $this->loadTemplate('js');
?>
<div class="pc-country-wrapper clearfix">
<div class="pc-country row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=country'); 
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

	<?php echo JHtml::_('bootstrap.startTabSet', 'country', array('active' => 'detail')); ?>
	<!--  Country Details Tab	-->
	<?php echo JHtml::_('bootstrap.addTab', 'country', 'detail', JText::_('COM_PAYCART_COUNTRY')); ?>
	
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="pc-form-validate" enctype="multipart/form-data">
		
		<div class="row-fluid">
			<div class="span3">
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_COUNTRY_DETAILS_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_COUNTRY_DETAILS_HEADER_MSG');?>
				</div>
			</div>
			<div class="span9">
				<fieldset class="form">
					<div class="row-fluid">								
						<div class="span6">
							<?php $field = $form->getField('title') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?>
									<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
								</div>								
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<?php $field = $form->getField('country_id')?>
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls">
									<input type="text" id="<?php echo $field->id?>" name="<?php echo $field->name;?>" class="required validate-rb-regex-pattern" 
									       data-validate-pattern="^[A-Z]{3}$" maxlength="3" value="<?php echo $field->value?>" placeholder="<?php echo JText::_("COM_PAYCART_ADMIN_COUNTRY_ISOCODE3_PLACEHOLDER");?>">
									<a href="#" data-toggle="popover" class="pc-popover" title="<?php echo JText::_("JHELP")?>"
									  data-content="<?php echo JText::_("COM_PAYCART_ADMIN_COUNTRY_ISOCODE_POPOVER");?>">
									 	 <i class="fa fa-question-circle"></i>
								  	</a>
									<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_ISOCODE3');?></div>
								</div>								
							</div>
						</div>
					</div>
				
					<div class="row-fluid">			
						<div class="span6">
							<?php $field = $form->getField('isocode2') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls">
									<input type="text" id="<?php echo $field->id?>" name="<?php echo $field->name;?>" class="required validate-rb-regex-pattern" 
										   data-validate-pattern="^[A-Z]{2}$" maxlength="2" value="<?php echo $field->value?>" placeholder="<?php echo JText::_("COM_PAYCART_ADMIN_COUNTRY_ISOCODE2_PLACEHOLDER");?>">
									<a href="#" data-toggle="popover" class="pc-popover" title="<?php echo  JText::_("JHELP")?>"
									  data-content="<?php echo JText::_("COM_PAYCART_ADMIN_COUNTRY_ISOCODE_POPOVER");?>">
									 	 <i class="fa fa-question-circle"></i>
								  	</a>
									<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_ISOCODE2');?></div>
								</div>								
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<?php $field = $form->getField('published')?>
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
					</div>
					
					<!--<div class="row-fluid">	
						<div class="span6">
							<?php $field = $form->getField('zip_format') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<?php $field = $form->getField('call_prefix')?>
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
					</div>
							
				--></fieldset>
			</div>
		</div>
				
		<input type="hidden" name="task" value="save" />
		<input type='hidden' name='id' value='<?php echo $record_id;?>' />
		<?php echo $form->getInput('lang_code') ?>	
		<?php echo $form->getInput('country_lang_id') ?>	
	</form>
	<?php echo JHtml::_('bootstrap.endTab'); ?>	
	
	<!--	 Address Details Tab		-->
	<?php echo JHtml::_('bootstrap.addTab', 'country', 'state', JText::_('COM_PAYCART_STATE')); ?>
		
	<?php echo $this->loadTemplate('states'); ?>

	<?php echo JHtml::_('bootstrap.endTab'); ?>
</div>
</div>
</div>
<?php 