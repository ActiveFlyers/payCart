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
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}?>

<?php 
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/js/owl.carousel.min.js');
Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/css/owl.carousel.css');

include_once 'default.js.php'; //PCTODO: won't work with template overriding
?>
<script>
paycart.queue.push('$("#pc-screenshots-carousel").owlCarousel({ lazyLoad : true, singleItem:true, autoHeight : true, pagination:true });');
</script>

<div class='pc-product-fullview-wrapper row-fluid clearfix'>

	<h1 class="visible-phone"><?php echo $product->getTitle(); ?></h1>
	 
	 <div class="row-fluid">
	 
		 <!-- ======================
				Left Layout
		 =========================== -->
		 <div class="span6">
		 	<div id="pc-screenshots-carousel" class="owl-carousel pc-screenshots">
			 	<?php $counter = 0; ?>
			    <?php foreach($product->getImages() as $mediaId => $detail):?>
				    <div>
				    	<img class="lazyOwl" data-src="<?php echo $detail['original'];?>" />
				    </div>
				    <?php $counter++; ?>
				<?php endforeach;?>
	 		</div>
		 </div>
	
		 <!-- ======================
				Right Layout
		 =========================== -->
		 <div class="span6">
				<h1 class="hidden-phone"><?php echo $product->getTitle(); ?></h1> 		
		 		<h2><?php JText::_("Price");?> : <span class="currency">$</span><span class="amount"><?php echo $product->getPrice();?></span></h2>	
		 		
		 		<!-- Filterable Attributes -->
		 		<div>
		 		    <form class="pc-product-attributes" method="post">
		 		    	 <fieldset>
		 		    	 	<?php if(!empty($variants)):?>
							    	<?php foreach ($selectors as $productAttributeId => $data):?>
							    		<?php $instance  = PaycartProductAttribute::getInstance($productAttributeId);?>
							    		
							    		 <div>
							    			<label class="muted"><?php echo $instance->getTitle();?>:</label>
							    			<?php echo $product->getAttributeHtml('selector', $productAttributeId, $data['selectedvalue'],$data['options']);?>
							    		 </div>
							        <?php endforeach;?>
							<?php endif;?>
							<!-- -->
							<input type="hidden" name="pc-product-base-attribute" class="pc-product-base-attribute"/>
					    </fieldset>
				    </form>
    			</div>
		 		
		 		<!-- buy now -->
		 		<div class="row-fluid clearfix">
					<div class="span6">	 			
				       <a class="btn btn-block btn-large btn-primary" href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=buy&product_id='.$product->getId()); ?>"><?php echo JText::_("COM_PAYCART_PRODUCT_BUY_NOW");?></a>
				    </div>
					<div class="span6">	 
						<?php ?>			
				        <button class="btn btn-block btn-large" onClick="paycart.url.modal('<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=addProduct&product_id='.$product->getId()) ?>')"><?php echo JText::_("COM_PAYCART_PRODUCT_ADD_TO_CART");?></button>
				    </div>
				</div>
		 		
		 		
		 </div>
	 </div>
	 
	 <!-- ===============================
	 		    Full layout 
	 ==================================== -->
	 <div class="row-fluid">
	 
	  <div class="span12">
	 	<!-- accordion1 Detail description of product -->
	 	<div class="accordion" id="accordion-id">
	 		<div class="accordion-group">
		 		<div class="accordion-heading">
		 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-id" data-target=".accordion-body-id">
		 				<h2><?php echo JText::_("COM_PAYCART_DETAILS");?></h2>
		 			</a>		
		 		</div>
		 		<!-- use class "in" for keeping it open -->
		 		 <div class="accordion-body collapse in accordion-body-id">
		 		 	<div class="accordion-inner">
		 		 		<?php echo $product->getDescription();?>
		 		 	</div>
		 		 </div>
	 		 </div>
	 	</div>
	 	
	 	<!-- Specification -->
	 	<div class="accordion" id="accordion-id2">
	 		<div class="accordion-group">
		 		<div class="accordion-heading">
		 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-id2" data-target=".accordion-body-id2">
		 				<h2><?php echo JText::_("COM_PAYCART_PRODUCT_SPECIFICATION");?></h2>
		 			</a>		
		 		</div>
		 		
		 		 <div class="accordion-body collapse accordion-body-id2">
		 		 	<div class="accordion-inner">
                        <table class="pc-product-specification table table-responsive">
                          		<tr>
                          			<th colspan="2" bgcolor="#F5F5F5">General Details</th>
                          		</tr>
                              <?php foreach ($product->getAttributeValues() as $attributeId => $optionId):?>
                                  <?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
                                  <tr>
                                      <td width="25%">
                                          <?php echo $instance->getTitle();?>
                                      </td>
                                      <td width="75%">
                                          <?php $options = $instance->getOptions(); echo $options[$optionId]->title;?>
                                      </td>
                                  </tr>         
                              <?php endforeach;?>
                        </table>
		 		 	</div>
		 		 </div>
	 		 </div>
	 	</div>
	 	
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