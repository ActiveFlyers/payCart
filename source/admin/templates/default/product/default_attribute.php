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
$appliedAttributes = $form->getFieldset('attributes');  
?>

	<script>
		paycart.jQuery(document).ready(function($) {

			$('.paycart_attribute_add_window').click( function()
			{
				paycart.admin.product.attribute.window();
				
			});
		});
	</script>
	
	<div class="row-fluid">
		<div class="span9">
			<?php  if(!empty($appliedAttributes)):?>
				<?php	foreach ($appliedAttributes as $attribute) : ?>
							<div class="control-group">
								<div class="control-label">	<?php echo $attribute->label; ?> </div>
								<div class="controls">		<?php echo $attribute->input; ?></div>								
							</div>
				<?php 	endforeach;?>
			<?php endif;?>
			
		</div>
			
		<div class="span3">
		
			<a href="#" class="btn btn-success paycart_attribute_add_window">
				<i class="icon-plus-sign icon-white"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_ATTRIBUTE_ADD_NEW');?>
			</a>

			<div class="input-append">  				
  				<input class="span10" id="appendedInputButtons" type="text" placeholder="Search Attribute!!" />
  				<span class="add-on">
					<input type="checkbox" id="" value="">
				</span>
			  	<button class="btn" type="button">Add</button>
			  	
			</div>
			
			<div class="">
				
				<table class="table table-striped" >
					<?php
						$count = 0; 
					?>
					<?php foreach($availbleAttributes as $attribute) :?>
						<tr class="<?php echo "row".$count%2; ?>">								
							
							<th>
			    				<?php echo $attribute->title ; ?>
			    			</th>
			    			
							<th>
			    				<?php echo PaycartHtml::_('grid.id', $count++, 'attribute_id' ); ?>
			    			</th>
			    			
			    		</tr>		
					<?php endforeach;?>
				</table>				
			</div>
		</div>
		
		
	</div>

	
