<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );?>

<?php 
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/jquery.fancybox.min.js');

Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/jquery.fancybox.css');


/**
 * Template Parameters
 * @param $isAvailableInStock
 * @param $product
 * @param $positions
 * 
 */

 $position = 'pc-product-media-gallery';
$attributes = $product->getAttributes();
$postionedAttributes = (array)$product->getPositionedAttributes();

$showMediaGallery = false;
  if(isset($plugin_result) && isset($plugin_result[$position]) && !empty($plugin_result[$position])):
  	$showMediaGallery = true;
  	
  else :
  	Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/owl.carousel.min.js');
	Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/owl.carousel.css');
	?>
	<script>
				paycart.queue.push('$("#pc-screenshots-carousel").owlCarousel({ lazyLoad : true, singleItem:true, autoHeight : true, pagination:true });');
	</script><?php 
  endif;
 $this->assign('showMediaGallery', $showMediaGallery);
 echo $this->loadTemplate('js');
 echo  Rb_HelperTemplate::renderLayout('paycart_spinner'); 

?>


<div class='pc-product-fullview-wrapper row-fluid clearfix'>

	<h1 class="visible-phone pc-break-word"><?php echo $product->getTitle(); ?></h1>
	 
	 <div class="row-fluid">
	 
		 <!-- ======================
				Left Layout
		 =========================== -->
		 <div class="span6">
	 		
	   <?php if($showMediaGallery):?>
                <div class="<?php echo $position;?>">
                        <?php echo $plugin_result[$position]; ?>
                </div>
      			<?php 
	 		 else:
	 		?>
	 		<div id="pc-screenshots-carousel" class="owl-carousel pc-screenshots center" >
			 
			    <?php foreach($product->getImages() as $mediaId => $detail):?>
				    <a class="pc-fancybox" rel="gallery1" href="<?php echo $detail['original'];?>">
				    	<img class="lazyOwl" data-src="<?php echo $detail['optimized'];?>" />
				    </a>
				<?php endforeach;?>
	 		</div>
	 		<?php endif;?>
	 		
		 </div>
	
		 <!-- ======================
				Right Layout
		 =========================== -->
		 <div class="span6">
				<h1 class="hidden-phone pc-break-word pc-product-detail-title"><?php echo $product->getTitle(); ?></h1>	
			
				<div class="row-fluid">
					<?php $retail_price = $product->getRetailPrice();?>
		 			<?php $price = $product->getPrice();?>
		 			<?php if($retail_price > $price):?>
						<?php $percentage = (($retail_price-$price)*100)/$retail_price;?>
			 			<h4>
				 			<strike class="muted"><?php echo $formatter->amount($retail_price,true);?></strike>
				 			<?php $percentage = floatval(PaycartFactory::getHelper('format')->amount($percentage,false));?>
				 			<span class="pc-discount label label-important"><?php echo JText::_('COM_PAYCART_SAVE').' '.$percentage.'%'?></span>
				 		</h4>
			 		<?php endif;?>
				</div>
				
		 		<h2>
		 			<span><?php echo $formatter->amount($price,true);?></span>	
		 		</h2>
		 		
		 		<!-- ======================
				Position == product-overview	
		 		=========================== -->		 		
		 		<div class="pc-product-overview">
		 			<?php if(isset($postionedAttributes['product-overview']) && !empty($postionedAttributes['product-overview'])) : ?>
		 				<ul>
		 				<?php foreach($postionedAttributes['product-overview'] as $attributeId) : ?>
		 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
		 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
								<?php $options 	= $instance->getOptions();?> 
								<li><?php echo $options[$attributes[$attributeId]]->title;?></li>
							<?php endif?>	                         
		 				<?php endforeach;?>
		 				</ul>
		 			<?php endif;?>
		 		</div>
		 		
		 		
		 		<!-- Filterable Attributes -->
		 		<?php if(!empty($selectors)):?>
				<hr />
		 		<div>
		 		    <form class="pc-product-selector" method="post">
		 		    	 <fieldset>		 		    	 	
					    	<?php foreach ($selectors as $productAttributeId => $data):?>
					    		<?php $instance  = PaycartProductAttribute::getInstance($productAttributeId);?>					    		
					    		 <div>
					    			<label class="muted"><?php echo $instance->getTitle();?>:</label>
					    			<?php echo $product->getAttributeHtml('selector', $productAttributeId, $data['selectedvalue'],$data['options']);?>
					    		 </div>
					        <?php endforeach;?>							
							<!-- -->
							<input type="hidden" name="pc-product-base-attribute" class="pc-product-base-attribute"/>
					    </fieldset>
				    </form>
    			</div>
    			<?php endif;?>
		 		<hr/>
		 		<!-- buy now -->
                <?php if($isAvailableInStock):?>                     
				<div class="row-fluid clearfix">
             
                    <div class="span6 help-block">    
                    	<?php if(!$isExistInCart):?>            
                        	<button class="btn btn-block btn-large pc-btn-addtocart" onClick="paycart.product.addtocart(<?php echo $product->getId();?>);">
                        		<?php echo JText::_("COM_PAYCART_PRODUCT_ADD_TO_CART");?>
                            </button>
                        <?php else:?>
                        	<button class="btn btn-block btn-large pc-btn-addtocart" onClick='rb.url.redirect("<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=display'); ?>"); return false;'>
                        		<?php echo JText::_('COM_PAYCART_CART_VIEW')?>&nbsp;&nbsp; <i class='fa fa-chevron-right'></i>
                        	</button>
                        <?php endif;?>
                    </div>
                    
                    <div class="span6 help-block ">                 
                        	<button class="pc-btn-buynow btn btn-block btn-large btn-primary" style="<?php echo $isExistInCart ?'display:block':'display:none';?>" onClick='rb.url.redirect("<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=checkout'); ?>"); return false;'>
                            		<?php echo JText::_("COM_PAYCART_PRODUCT_CHECKOUT");?>
                            </button>
                 
                      
                    </div>
              	</div>
              	<?php else :?>
                	<div class="row-fluid">
                    	<h2 class="text-error"><?php echo JText::_("COM_PAYCART_PRODUCT_IS_OUT_OF_STOCK");?></h2>
                    </div>
                <?php endif;?>
                
                <!-- ======================
				Position == product-addons	
		 		=========================== -->		 		
		 		<div class="row-fluid">		
					<?php $position = 'pc-product-addons';?> 		
			 		<div class="<?php echo $position;?>"> 			
			 			<?php if(isset($postionedAttributes['product-addons']) && !empty($postionedAttributes['product-addons'])) : ?>
			 				<ul>
			 				<?php foreach($postionedAttributes['product-addons'] as $attributeId) : ?>
			 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
			 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
									<?php $options 	= $instance->getOptions();?> 
									<li><?php echo $options[$attributes[$attributeId]]->title;?></li>
								<?php endif?>	                         
			 				<?php endforeach;?>
			 				</ul>
			 			<?php endif;?>
			 			
				 		 <?php if(isset($plugin_result) && isset($plugin_result[$position])):?>
						 <div class="row-fluid">
			                  <?php echo $plugin_result[$position]; ?>
						 </div>
		      			 <?php endif;?>
			 		</div>
		 		</div>
		 		
		 		 <!-- ======================
				Position == product-teaser	
		 		=========================== -->	
		 		<?php if(!empty($digital_teasers) && $product->getType() == Paycart::PRODUCT_TYPE_DIGITAL):?>
			 		<div class="row-fluid teaser-download">
				 		<div class="pc-product-teaser">
				 			<h4><span class="muted"><?php echo JText::_("COM_PAYCART_PRODUCT_DIGITAL_GET_SAMPLE")?></span></h4>
				 			<ul class="unstyled">
				 			<?php foreach ($digital_teasers as $id => $data):?>
				 				 <li>
				 				 	<?php $fileName  = base64_encode('file-'.$id);?>
				 				 	<?php $extension = JFile::getExt($data['filename']);?>
				 				 	<a href="javascript:void(0);" onClick="rb.url.redirect('<?php echo PaycartRoute::_('index.php?option=com_paycart&view=product&task=serveTeaser&product_id='.$product->getId().'&file_id='.$fileName)?>')">
				 				 		<i class="fa fa-download dwn-icon"></i> <?php echo $data['title']?>
				 				 	</a>
									<span class="muted"><?php echo ' ('.$extension.')';?></span>
				 				 </li>
				 			<?php endforeach;?>
				 			</ul>
				 		</div>
				 	</div>
			 	<?php endif;?>
			 	<hr/>
		 </div>
	 </div>
	 
	 <br>
	 
	 <!-- ===============================
	 		    Full layout 
	 ==================================== -->
	 <div class="row-fluid">
	 
	  <div class="span12">
	  	<?php $description = $product->getDescription();?>
	  	<?php if(!empty($description) || (isset($postionedAttributes['product-details']) && !empty($postionedAttributes['product-details']))):?>
		 	<!-- accordion1 Detail description of product -->
		 	<div class="accordion" id="accordion-id">
		 		<div class="accordion-group">
			 		<div class="accordion-heading">
			 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-id" data-target=".accordion-body-id">		 				
			 				<h2><span class="pull-right"><i class="fa fa-minus-square"></i></span><?php echo JText::_("COM_PAYCART_DETAILS");?></h2>
			 			</a>		
			 		</div>
			 		<!-- use class "in" for keeping it open -->
			 		 <div class="accordion-body collapse in accordion-body-id">
			 		 	<div class="accordion-inner">
			 		 		<div class="pc-product-details">
				 		 		<?php if(!empty($description)) : ?>
					 		 		<div class="row-fluid">
					 		 			<?php echo $description;?>
					 		 		</div>
				 		 		<?php endif;?>
			 		 		
			 		 			<div class="row-fluid">						 		
						 			<p>&nbsp;</p>		 			
						 			<?php if(isset($postionedAttributes['product-details']) && !empty($postionedAttributes['product-details'])) : ?>
						 				<ul>
						 				<?php foreach($postionedAttributes['product-details'] as $attributeId) : ?>
						 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
						 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
												<?php $options 	= $instance->getOptions();?> 
												<li><?php echo $options[$attributes[$attributeId]]->title;?></li>
											<?php endif?>	                         
						 				<?php endforeach;?>
						 				</ul>
						 			<?php endif;?>
						 		</div>
						 	</div>
			 		 	</div>
			 		 </div>
		 		 </div>
		 	</div>
		<?php endif;?>
	 	
	 	
	 <?php if(isset($postionedAttributes['product-specifications']) && !empty($postionedAttributes['product-specifications'])) : ?>
		 	<!-- Specification -->
		 	<div class="accordion" id="accordion-id2">
		 		<div class="accordion-group">
			 		<div class="accordion-heading">
			 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-id2" data-target=".accordion-body-id2">
			 				<h2><span class="pull-right"><i class="fa fa-minus-square"></i></span><?php echo JText::_("COM_PAYCART_PRODUCT_SPECIFICATION");?></h2>
			 			</a>		
			 		</div>
			 		
			 		 <div class="accordion-body collapse in accordion-body-id2">
			 		 	<div class="accordion-inner">
	                        <table class="pc-product-specification table table-responsive">
	                          	
	                            <?php foreach ($postionedAttributes['product-specifications'] as $attributeId):?>
	                            	<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
		                                <?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
		                                <?php $options = $instance->getOptions();?>
		                                <?php if($instance->getType() == 'header') : ?>
		                                	</table>
		                               		<table class="pc-product-specification table table-responsive">
			                             	   	<tr>
		                          					<th colspan="2" class="pc-product-attribute-header">
		                          					<?php echo $options[$attributes[$attributeId]]->title;?>
		                          					</th>
		                          				</tr>
		                                <?php else : ?>
			                                <tr>
			                                	<td width="25%">
			                                    	<?php echo $instance->getTitle();?>
			                                	</td>
			                                    <td width="75%">
			                                         <?php echo $options[$attributes[$attributeId]]->title;?>
			                                    </td>
			                                </tr>
										<?php endif;?>
	                                <?php endif;?>         
	                              <?php endforeach;?>
	                        </table>
			 		 	</div>
			 		 </div>
		 		 </div>
	 		</div>
	 	<?php endif;?>
	 	
	 	<!-- <div class="accordion" id="accordion-id3">
	 		<div class="accordion-group">
		 		<div class="accordion-heading">
		 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-id3" data-target=".accordion-body-id3">
		 				<h2>Shipping</h2>
		 			</a>		
		 		</div>
		 		
		 		 <div class="accordion-body collapse accordion-body-id3">
		 		 	<div class="accordion-inner">
		 		 		<h3>content of a page when looking at its layout.</h3>
		 		 	 	<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
		 		 	</div>
		 		 </div>
	 		 </div>
	 	</div>
	 	
	 	--></div>
	 </div>
</div>
<?php 
