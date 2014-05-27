<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	Frontend
* @contact 		manish@readybytes.in
* @author 		Manish Trivedi
*/

/**
 * List of Populated Variables
 * $heading = COM_PAYCART_ADMIN_BLANK_PRODUCT
 * $msg 	= COM_PAYCART_ADMIN_BLANK_PRODUCT_MSG
 * $model	= Instance of PaycartModelProduct
 * $filters = Array of availble filters
 * $uri		= Current URL (SITE_NAME/administrator/index.php?option=com_paycart&view=product&task=display)
 * 
 */
defined('_JEXEC') or die();
// List of applied attribtes on Product
$appliedAttributes = $product->getAttributeValues();
?>
	<script>
		paycart.jQuery(document).ready(function($) {

			var checkValues =  <?php echo json_encode($appliedAttributes)?>;
			paycart.admin.product.attribute.attach(checkValues);
			
			$('.paycart-attribute-add-window').click( function()
			{
				paycart.admin.product.attribute.window();
				
			});

			$('.paycart-attribute-attach-value').click( function()
			{
				var checkValues = $('input[name="productattribute[]"]:checked').map(function()
		        {
		            return $(this).val();
		        }).get();

				paycart.admin.product.attribute.attach(checkValues);
			});
		});
	</script>
	
	<div class="row-fluid">
		<div class="span6 paycart-product-applied-attributes">
			<!--
			    Here comes the applied and new attributes 
			-->
		</div>
			
		<div class="span6">
			<a href="#" class="btn btn-success paycart-attribute-add-window">
				<i class="icon-plus-sign icon-white"></i>&nbsp;<?php echo JText::_('COM_PAYCART_ATTRIBUTE_ADD_NEW');?>
			</a>

			<div class="input-append">  				
  				<input class="span10" id="appendedInputButtons" type="text" placeholder="Search Attribute!!" />
  				<span class="add-on">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</span>
			  	<button class="btn paycart-attribute-attach-value" type="button"><?php echo Jtext::_("COM_PAYCART_PRODUCT_ATTACH_ATTRIBUTES");?></button>
			  	
			</div>
			
			<div>				
				<table class="table table-condensed" >
					<?php
						$count = 0;
					?>
					<?php $remainingAttributes = array_diff_key($availableAttributes,$appliedAttributes)?>
					<?php foreach($remainingAttributes as $attribute) :?>
						<tr class="<?php echo "row".$attribute->productattribute_id; ?>">
							<th>
			    				<?php $instance = PaycartProductAttribute::getInstance($attribute->productattribute_id,$attribute);
			    					  echo $instance->getTitle() ; ?>
			    			</th>
			    			
							<th>
			    				<?php echo PaycartHtml::_('grid.id', $count++, $attribute->productattribute_id, false,'productattribute' ); ?>
			    			</th>
			    			
			    		</tr>		
					<?php endforeach;?>
				</table>				
			</div>
		</div>
		
		<input type="hidden" name="boxchecked" value="0" />
	</div>	
