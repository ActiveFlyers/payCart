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
// validation for required fields
PaycartHtml::_('behavior.formvalidation');
Rb_HelperTemplate::loadMedia(array('angular'));

echo $this->loadTemplate('edit_js');
echo $this->loadTemplate('edit_angularjs');
?>

<div class="pc-product-wrapper clearfix">
<div class="pc-product row-fluid" data-ng-app="pcProductApp">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=product'); 
	?>
</div>
<!-- ADMIN MENU -->

<div class="span10">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form" enctype="multipart/form-data" >
	<div class="row-fluid">
		<div class="<?php echo count($variants)? 'span10' : 'span12'; ?>">
			<?php echo PaycartHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'basic')); ?>
<!--========	Product Basic Attributes	========-->
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'basic', Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_DETAILS', true)); ?>
				
				<?php $prefix = $this->_component->getNameSmall().'_form_language_';?>
				<?php $language_fields = $form->getFieldset('language');?>				
				<!-- PRODUCT DETAILS -->
				<div class="row-fluid">
					<div class="span3">
						<h2><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_DETAILS_HEADER');?></h2>
						<div>
							<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_DETAILS_HEADER_MSG');?>
						</div>
					</div>
					<div class="span9">
						<fieldset class="form">
							<div class="row-fluid">
								<div class="control-group">
									<div class="control-label"><?php echo $language_fields[$prefix.'title']->label; ?></div>
									<div class="controls"><?php echo $language_fields[$prefix.'title']->input; ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $language_fields[$prefix.'teaser']->label; ?></div>
									<div class="controls"><?php echo $language_fields[$prefix.'teaser']->input; ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $language_fields[$prefix.'description']->label; ?></div>
									<div class="controls"><?php echo $language_fields[$prefix.'description']->input; ?></div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span6">
									<div class="control-group">
										<div class="control-label"><?php echo $language_fields[$prefix.'alias']->label; ?> </div>
										<div class="controls"><?php echo $language_fields[$prefix.'alias']->input; ?></div>
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
							<div class="row-fluid">
								<div class="span6">
									<?php $field = $form->getField('status') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
								<div class="span6">
									<?php $field = $form->getField('featured') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span6">
									<?php $field = $form->getField('productcategory_id') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls "><?php echo $field->input; ?></div>								
									</div>
								</div>
								<div class="span6">
									
								</div>
							</div>
						</fieldset>
					</div>					
				</div>
				
				<hr/>
				
				<!-- INVENTORY DETAILS -->
				<div class="row-fluid">
					<div class="span3">
						<h2><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_PRICING_AND_INVENTORY_HEADER');?></h2>
						<div>
							<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_PRICING_AND_INVENTORY_HEADER_MSG');?>
						</div>
					</div>
					<div class="span9">
						<fieldset class="form">
							<div class="row-fluid">
								<div class="span6">
									<?php $field = $form->getField('price') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
								<div class="span6">
									<?php $field = $form->getField('sku') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span6">
									<?php $field = $form->getField('quantity') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
								<div class="span6">
									<?php $field = $form->getField('stockout_limit') ?>
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
				
				<!-- INVENTORY DETAILS -->
				<div class="row-fluid">
					<div class="span3">
						<h2><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_GALLERY_HEADER');?></h2>
						<p>
							<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_GALLERY_HEADER_MSG');?>
						</p>
						
						<?php if(!empty($images)):?>
							<div>								
								<div class="row-fluid">
									<input type="file" name="paycart_form[images][]" multiple="true">
								</div>								
							</div>
						<?php endif;?>
					</div>
					<div class="span9">
						<fieldset class="form">
							<div class="row-fluid">
								<?php if(empty($images)):?>
									<input type="file" name="paycart_form[images][]" multiple="true">
								<?php else :?>								
									<script>
										var pc_product_images 	= <?php echo json_encode($images);?>;
										var pc_product_id		= <?php echo $record_id;?>;
									</script>
									
									<div data-ng-controller="pcProductImagesCtrl">
										<ul class="thumbnails">
		    								<li data-ng-repeat="(index,image) in images" class="thumbnail">		    									
		    									<a href="#pc-product-gallery-modal" data-toggle="modal" onClick="return false;" data-ng-click="setActiveImage(index);">
		    									<img data-ng-src="{{ image.thumbnail }}" alt="">
		    									</a>
		    									<div>		    										
		    										<span class="pull-right"><a href="#" onClick="return false;" class="muted" data-ng-click="remove(index);"><i class="fa fa-trash-o"></i></a></span>
		    									</div>
		    								</li>		    									    								
		    							</ul>
		    							
		    							<!-- Modal Popup -->
		    							<div id="pc-product-gallery-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:600px; margin-left:-300px;">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
												<h3 id="myModalLabel"><?php echo JText::_('COM_PAYCART_ADMIN_IMAGE_EDIT_TITLE');?></h3>
											</div>
											
											<div class="modal-body">											
												<div data-ng-show="message" class="alert alert-success">{{ message }}</div>
												<div data-ng-show="errMessage" class="alert alert-danger">{{ errMessage }}</div>
												
												<div class="row-fluid">
													<div class="span5 center">
														<img data-ng-src="{{ activeImage.thumbnail }}">
													</div>
													<div class="span7">
														<div class="control-group">
															<div class="control-label">
																<label id="title_lbl"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_TITLE')?></label>
															</div>
															 <div class="controls">
																<input type="text" data-ng-model="activeImage.language.title" value=""/>																
															</div>
														</div>
													</div>
												</div>
											</div>
											
											<div class="modal-footer text-center">
												<button class="btn" data-ng-click="cancel();" onclick="return false;" data-dismiss="modal"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_CANCEL')?></button>
												<button class="btn btn-primary" data-ng-click="save();" onclick="return false;"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_SAVE')?></button>												
											</div>
										</div>	
									</div>
								<?php endif;?>
							</div>
						</fieldset>
					</div>					
				</div>
				
				<hr />
				
				<!--	Product Meta Data			-->
				<div class="row-fluid">
					<div class="span3">
						<h2><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_METADATA_HEADER');?></h2>
						<div>
							<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_METADATA_HEADER_MSG');?>
						</div>
					</div>
					<div class="span9">
						<fieldset class="form">
							<div class="row-fluid">								
								<?php $field = $language_fields[$prefix.'metadata_title']; ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?> </div>
									<div class="controls"><?php echo $field->input; ?></div>								
								</div>
								
								<?php $field = $language_fields[$prefix.'metadata_description']; ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?> </div>
									<div class="controls"><?php echo $field->input; ?></div>								
								</div>
								
								<?php $field = $language_fields[$prefix.'metadata_keyword']; ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?> </div>
									<div class="controls"><?php echo $field->input; ?></div>								
								</div>								
							</div>
						</fieldset>
					</div>					
				</div>
				
				<hr />
				<!--	Dimenssions 	-->
				<div class="row-fluid">
					<div class="span3">
						<h2><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_WEIGHT_AND_DIMENSION_HEADER');?></h2>
						<div>
							<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_WEIGHT_AND_DIMENSION_HEADER_MSG');?>
						</div>
					</div>
					<div class="span9">
						<fieldset class="form">
							<div class="row-fluid">								
								<div class="span6">
									<?php $field = $form->getField('weight') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
								<div class="span6">
									<?php $field = $form->getField('weight_unit') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span6">
									<?php $field = $form->getField('height') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>									
								</div>
								<div class="span6">
									<?php $field = $form->getField('width') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
							</div>
							<div class="row-fluid">								
								<div class="span6">
									<?php $field = $form->getField('length') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
								<div class="span6">
									<?php $field = $form->getField('dimension_unit') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
							</div>
						</fieldset>
					</div>					
				</div>
				
			<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
			
<!--========	Product Custom Attributes	========-->			
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'custom', Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_CUSTOM_ATTRIBUTES', true)); ?>				
				<?php 
						echo $this->loadtemplate('attribute');
				?>
			<?php echo PaycartHtml::_('bootstrap.endTab'); ?>

<!--========	Product System Attributes	========-->			
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'system', Rb_Text::_('COM_PAYCART_PRODUCT_SYSTEM_ATTRIBUTES_FIELDSET_LABEL', true)); ?>
			
				<div class="span6">
					<?php $field = $form->getField('product_id') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
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
				
			<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
				
<!--========	Product Custom Attributes	========-->			
			<!--<?php // echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'log', Rb_Text::_('COM_PAYCART_PRODUCT_SYSTEM_ATTRIBUTES_FIELDSET_LABEL', true)); ?>
				<?php //@PCTODO:: ?>
			<?php //echo PaycartHtml::_('bootstrap.endTab'); ?>-->
			
		<?php echo PaycartHtml::_('bootstrap.endTabSet'); ?>
			

<!--========	Product Variants	========-->
		<?php if (count($variants)) { ?>
		<div class="span2 ">
			<h4><?php echo Rb_Text::_('COM_PAYCART_PRODUCT_VARIANT' ); ?> </h4> <hr>
			<fieldset class="form-vertical">	
				Product Variant
			</fieldset>
		</div>		
		<?php }?>	

		</div>
	</div>
	
<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="apply" />
	<input type='hidden' name='id' id="product_id" value='<?php echo $record_id;?>' />	
</form>
</div>
</div>
</div>
<?php 