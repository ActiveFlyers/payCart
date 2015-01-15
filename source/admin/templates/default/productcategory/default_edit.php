<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

JHtml::_('behavior.formvalidation');
Rb_HelperTemplate::loadMedia(array('angular'));
echo $this->loadTemplate('edit_js');
echo $this->loadTemplate('edit_ng');
?>
<div class="pc-productCategory-wrapper clearfix">
<div class="pc-productCategory row-fluid" data-ng-app="pcngProductCategoryApp">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=productcategory'); 
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
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="pc-form-validate" enctype="multipart/form-data">
	<div class="row-fluid">
		<div class="span3">
			<h2><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCTCATEGORY_DETAILS_HEADER');?></h2>
			<div>
				<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCTCATEGORY_DETAILS_HEADER_MSG');?>
			</div>
		</div>
		<div class="span9">
			<div class="row-fluid">
				<?php $field = $form->getField('title') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>
					<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>								
				</div>
				<?php $field = $form->getField('alias') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>
					<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_ALIAS');?></div>								
				</div>
				<?php $field = $form->getField('description') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('parent_id') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('published') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
				</div>
				<div class="span6">
					<?php $field = $form->getField('created_date') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('modified_date') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
				</div>			
			</div>		
		</div>
	</div>	
	
	<hr />
		
	<!--	Category Image			-->
	<div class="row-fluid">
		<div class="span3">
			<h2><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCTCATEGORY_IMAGE_HEADER');?></h2>
			<div>
				<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCTCATEGORY_IMAGE_HEADER_MSG');?>
			</div>
		</div>
		<div class="span9">
			<fieldset class="form">	
				<?php if(!empty($cover_media)):?>
				<script>
					var pc_productCategory_id		= <?php echo $record_id;?>;
					var pc_cover_media				= <?php echo json_encode($productCategory->getCoverMedia());?>;
				</script>
				<div data-ng-controller="pcngProductCategoryImagesCtrl">				
					<ul data-ng-show="cover_media" class="thumbnails">
		    			<li class="thumbnail">		    									
    						<a href="#" onClick="return false;">
    							<img data-ng-src="{{ cover_media.thumbnail }}" alt="">
    						</a>
    						<div>		    										
    							<span class="pull-right"><a href="#" onClick="return false;" class="muted" data-ng-click="remove()">
    								<i class="fa fa-trash-o"></i></a>
    							</span>
    						</div>
    					</li>		    									    								
    				</ul>
    			</div>
				<?php endif;?>	    			
				<div class="row-fluid">								
					<input type="file" name="paycart_productcategory_form[cover_media]">
				</div>				
			</fieldset>
		</div>					
	</div>
	
	<hr />
	
	<!--	Product Meta Data			-->
	<div class="row-fluid">
		<div class="span3">
			<h2><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCTCATEGORY_METADATA_HEADER');?></h2>
			<div>
				<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCTCATEGORY_METADATA_HEADER_MSG');?>
			</div>
		</div>
		<div class="span9">
			<fieldset class="form">
				<div class="row-fluid">								
					<?php $field = $form->getField('metadata_title') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('metadata_description') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('metadata_keywords') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>								
				</div>
			</fieldset>
		</div>					
	</div>
	
	<hr />

<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="save" />
	<?php echo $form->getInput('productcategory_id');?>
	<?php echo $form->getInput('productcategory_lang_id');?>
	<?php echo $form->getInput('lang_code');?>
</form>
</div>
</div>
</div>
<?php 
