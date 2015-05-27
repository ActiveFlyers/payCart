<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Buyer
* @subpackage       BuyerJusergroup
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 
?>


	<div class="control-group">
	  <label class="control-label" for="textinput">
	  		<?php echo JText::_('COM_PAYCART_CATEGORIES');?>
	  </label>
	  <div class="controls">			
	  			<?php echo PaycartHtmlCategory::getList($namePrefix.'[categories][]', @$config['categories'], "{$idPrefix}categories", array('class' => "pc-chosen"));?>
	  				
	  		
	  		
	  </div>
	</div>
	
	<div class="control-group" >
	  
	  <label class="control-label" for="textinput">
	  		<?php echo JText::_('COM_PAYCART_PRODUCT');?>
	  </label>
	  <div class="controls">
	  	<select class="paycart-grouprule-product-assignment" name="<?php echo $namePrefix;?>[products_assignment]" id="<?php echo $idPrefix;?>products-assignment" data-pc-selector="pc-option-manipulator">
				<option value="selected" <?php echo isset($config['products_assignment']) && $config['products_assignment'] == 'selected' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_SELECTED');?></option>
				<option value="except" <?php echo isset($config['products_assignment']) && $config['products_assignment'] == 'except' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_EXCEPT');?></option>
			</select>
			<span data-pc-option-manipulator="<?php echo $idPrefix.'products-assignment';?>">
		  	<?php 
		  		echo PaycartHtmlProduct::getList($namePrefix.'[products][]', @$config['products'],  "{$idPrefix}products", array('class' => "pc-chosen",'multiple' => "true"));
		  	?>
		  	</span>
	  </div>
	</div>
	
	
<script>
	(function($){

		if (typeof(paycart.grouprule)=='undefined'){
			paycart.grouprule = {}
			paycart.grouprule.product = {}
		}
		
		paycart.grouprule.product = {

			onCategoryChange : function(category_selector, product_selector)
			{
			var link = rb_vars.url.root +'administrator/index.php?option=com_paycart&view=productcategory&task=getproducts';

			paycart.ajax.go( link, 
							{ 	'category_id' : $(category_selector).val(), 'product_selector' : product_selector, 
								'default_product' : $(product_selector).val() ,  'spinner_selector' :'#paycart-ajax-spinner'  
							}
					);
			},

			updateHtml : function(response)
			{
				var product_selector = response['product_selector'];
				$(product_selector).html(response['product_option_html']);
				
				//reinitialte chosen (using in backend configuration) 
				$(product_selector).trigger("liszt:updated");			
			}
			};

		paycart.grouprule.product.onCategoryChange(<?php echo "'#{$idPrefix}categories'" ?>, <?php echo "'#{$idPrefix}products'" ?>);
		$(<?php echo "'#{$idPrefix}categories'"; ?>).on('change',  function() {

			
			paycart.grouprule.product.onCategoryChange(<?php echo "'#{$idPrefix}categories'" ?>, <?php echo "'#{$idPrefix}products'" ?>);
		});	

	})(paycart.jQuery);
</script>
