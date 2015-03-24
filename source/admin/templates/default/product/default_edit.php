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
Rb_HelperTemplate::loadMedia(array('angular'));

echo $this->loadTemplate('js');
echo $this->loadTemplate('edit_ng');
echo $this->loadTemplate('edit_css');
?>
<style>
.paycart .label-left label{
	float:left;
}
</style>

<div class="pc-product-wrapper clearfix">
<div class="pc-product row-fluid" data-ng-app="pcngProductApp">

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
<?php echo Rb_HelperTemplate::renderLayout('paycart_spinner','',PAYCART_LAYOUTS_PATH);?>
		
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="pc-form-validate" enctype="multipart/form-data" novalidate>
	<div class="row-fluid">
		<div class="<?php echo count($variants)? 'span10' : 'span12'; ?>">
			<?php echo PaycartHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'basic')); ?>
<!--========	Product Basic Attributes	========-->
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'basic', Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_DETAILS', true)); ?>			
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
							
							<!--  Some hidden Fields -->
							<?php $field = $form->getField('type') ?>
							<input type="hidden" name="<?php echo $field->name;?>" id="<?php echo $field->id;?>" value="<?php echo Paycart::PRODUCT_TYPE_PHYSICAL;?>">

							<?php $field = $form->getField('featured') ?>
							<input type="hidden" name="<?php echo $field->name;?>" id="<?php echo $field->id;?>" value="0">
														
							<div class="row-fluid">
								<div class="span6">
									<?php $field = $form->getField('published') ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?> </div>
										<div class="controls"><?php echo $field->input; ?></div>								
									</div>
								</div>
								
								<div class="span6">
									<?php $field = $form->getField('visible') ?>
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
									<?php $currency = $formatter->currency($global_config->get('localization_currency')); ?>
									<?php $field = $form->getField('price') ?>
									<div class="control-group">
										<div class="control-label label-left"><?php echo $field->label; ?>&nbsp; ( <?php echo $currency;?> )</div>										
										<div class="controls"><?php echo $field->input; ?></div>
										<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_NUMERIC');?></div>								
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
										<div class="control-label"><?php echo $field->label; ?></div>
										<div class="controls">
    									<?php if($record_id):?>
											<div class="badge badge-info"><h6 data-pc-selector="quantityBadge"><?php echo $field->value;?></h6></div>
											<div class="input-prepend input-append ">
											    <input type="text" data-pc-selector="quantity" class="validate-integer" id="<?php echo $field->id;?>" placeholder="<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_QUANTITY_HELP')?>"/>
											    <button class="btn" type="button" data-pc-selector="addQuantity">
											    	<span class="text-success"><i class="fa fa-plus"></i></span>
											    	<?php echo JText::_('COM_PAYCART_ADMIN_ADD');?>											    	
											    </button>
											    <button class="btn" type="button" data-pc-selector="reduceQuantity">
												    <span class="text-error"><i class="fa fa-minus"></i></span>
												  	<?php  echo JText::_('COM_PAYCART_ADMIN_REDUCE');?>
											    </button>
											    
	   			 							</div> 
    										<input type="hidden" name="paycart_product_form[quantity]" data-pc-selector="paycart_product_form[quantity]" value="<?php echo $field->value;?>"/>
    									<?php else:?>
    										<?php echo $field->input;?>
    									<?php endif;?>

										<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_INTEGER');?></div>							
									</div>
								</div>
								</div>
								<?php if($record_id):?>
									<div class="span6">
										<?php $field = $form->getField('quantity_sold') ?>
										<div class="control-group">
											<div class="control-label"><?php echo $field->label; ?> </div>
											<div class="controls">
												<div class="badge badge-info"><h6><?php echo $field->value;?></h6></div>
											</div>	
											<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_INTEGER');?></div>							
										</div>
									</div>
									<!--<div class="span6">
										<?php $field = $form->getField('stockout_limit') ?>
										<div class="control-group">
											<div class="control-label"><?php echo $field->label; ?> </div>
											<div class="controls"><?php echo $field->input; ?></div>	
											<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_INTEGER');?></div>							
										</div>
									</div>
								--></div>
							<?php endif;?>
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
									<input type="file" class="validate-image" name="paycart_product_form[images][]" multiple="true" id="paycart_product_form__uploaded_files_images" data-fileUploadLimit="<?php echo $uploadLimit;?>">
								</div>								
							</div>
						<?php endif;?>
					</div>
					<div class="span9">
						<fieldset class="form">
							<div class="row-fluid">
								<?php if(empty($images)):?>
									<input type="file" class="validate-image" name="paycart_product_form[images][]" multiple="true" id="paycart_product_form__uploaded_files_images" data-fileUploadLimit="<?php echo $uploadLimit;?>">
									
								<?php else :?>								
									<script>
										var pc_product_images 	= <?php echo json_encode(array_values($images));?>;
										var pc_product_id		= <?php echo $record_id;?>;										
									</script>
									
									<div data-ng-controller="pcngProductImagesCtrl" id="pcngProductImagesCtrl">
										<ul class="thumbnails">
		    								<li data-ng-repeat="image in images" class="thumbnail" ng-class="{ 'pc-product-coverimage center' : $index == 0 }">		    									
		    									<a href="#pc-product-gallery-modal" data-toggle="modal" onClick="return false;" data-ng-click="setActiveImage($index);">
		    									<img data-ng-src="{{ image.thumbnail }}" alt="">
		    									</a>
		    									<div>		    										
		    										<span class="pull-right"><a href="#" onClick="return false;" class="muted" data-ng-click="remove($index);"><i class="text-error fa fa-trash-o"></i></a></span>
		    									</div>		    									
		    									<div class="badge" ng-show=" $index == 0 ">
		    										<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_COVER_IMAGE');?>
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
																<?php echo $flag; ?><label id="title_lbl"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_TITLE')?></label>
															</div>
															 <div class="controls">
																<input type="text" data-ng-model="activeImage.title" value=""/>																
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
							<br>
							<div class="pc-error" for="paycart_product_form__uploaded_files_images"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_INVALID_IMAGE');?></div>
							
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
				<!--	Dimenssions 	-->
				<div class="row-fluid">
					<div class="span3">
						<h2><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_WEIGHT_AND_DIMENSION_HEADER');?></h2>
						<div>
							<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_WEIGHT_AND_DIMENSION_HEADER_MSG');?>
						</div>
					</div>
					<div class="span9">
						<fieldset class="form label-left">
							<div class="row-fluid">							
								<?php $catalogue_weight_unit = $global_config->get('catalogue_weight_unit');?>
								<?php $field = $form->getField('weight_unit') ?>
								<input type="hidden" name="<?php echo $field->name;?>" value="<?php echo $catalogue_weight_unit;?>">					
																
								<?php $field = $form->getField('weight') ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?>&nbsp; ( <?php echo $catalogue_weight_unit;?> )</div>
									<div class="controls">
										<input class="input-block-level validate-numeric" type="text" name="<?php echo $field->name;?>" id="<?php echo $field->id;?>" value="<?php echo $formatter->weight($product->getWeight())?>">																															
									</div>								
									<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_NUMERIC');?></div>
								</div>						
							</div>
							
							<?php $catalogue_dimension_unit = $global_config->get('catalogue_dimension_unit');?>
							<div class="row-fluid">								
								<?php $field = $form->getField('dimension_unit') ?>									
								<input type="hidden" name="<?php echo $field->name;?>" value="<?php echo $catalogue_dimension_unit;?>">					
							
								<?php $field = $form->getField('height') ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?> &nbsp; ( <?php echo $catalogue_dimension_unit;?> )</div>
									<div class="controls">
										<input class="input-block-level validate-numeric" type="text" name="<?php echo $field->name;?>" id="<?php echo $field->id;?>" value="<?php echo $formatter->dimension($product->getHeight())?>">										
									</div>		
									<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_NUMERIC');?></div>						
								</div>							
							</div>
							<div class="row-fluid">
								<?php $field = $form->getField('length') ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?> &nbsp; ( <?php echo $catalogue_dimension_unit;?> )</div>
									<div class="controls">
										<input class="input-block-level validate-numeric" type="text" name="<?php echo $field->name;?>" id="<?php echo $field->id;?>" value="<?php echo $formatter->dimension($product->getLength())?>">										
									</div>			
									<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_NUMERIC');?></div>				
								</div>
							</div>
							<div class="row-fluid">
								<?php $field = $form->getField('width') ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?> &nbsp; ( <?php echo $catalogue_dimension_unit;?> )</div>
									<div class="controls">
										<input class="input-block-level validate validate-numeric" type="text" name="<?php echo $field->name;?>" id="<?php echo $field->id;?>" value="<?php echo $formatter->dimension($product->getWidth())?>">										
									</div>
									<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_NUMERIC');?></div>								
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
	<?php echo $form->getInput('product_id') ?>
	<?php echo $form->getInput('product_lang_id') ?>
	<?php echo $form->getInput('lang_code') ?>		
</form>
</div>
</div>
</div>
<?php 