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

?>
<div class="pc-product-wrapper clearfix">
<div class="pc-product row-fluid" data-ng-app="pcngProductApp">

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
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form" enctype="multipart/form-data">
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
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				<?php $field = $form->getField('alias') ?>
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
					<ul class="thumbnails">
		    			<li class="thumbnail">		    									
    						<a href="#" onClick="return false;">
    						<img src="<?php echo $cover_media['thumbnail']; ?>" alt="">
    						</a>
    						<div>		    										
    							<span class="pull-right"><a href="#" onClick="return false;" class="muted">
    								<i class="fa fa-trash-o"></i></a>
    							</span>
    						</div>
    					</li>		    									    								
    				</ul>
				<?php endif;?>	    			
				<div class="row-fluid">								
					<input type="file" name="paycart_form[cover_media]" multiple="true">
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
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('metadata_description') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('metadata_keywords') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
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
</form>
</div>
</div>
</div>
<?php 
