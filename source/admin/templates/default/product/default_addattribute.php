<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	Frontend
* @contact 		support+paycart@readybytes.in
* @author 		Manish Trivedi
*/

/**
 * 
 * List of Populated Variables
 * $attributeView => Attribute View
 * 
 */
defined('_JEXEC') or die();


?>

<script>
	<?php include_once dirname(dirname(__FILE__)).'/_media/js/template.js'; ?>
</script>

	<div id="rbWindowTitle">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="myModalLabel"><?php echo Rb_Text::_('COM_PAYCART_ADD_NEW_ATTRIBUTE'); ?></h3>
		</div>
	</div>
	
	<div class="modal-body" id="rbWindowBody">
		<!--  New_atrribute_creation body		-->
		<div class="">
		 
			<?php
				echo $attributeView->loadtemplate('edit');
			?> 
		</div>
	</div>
	
	<div id="rbWindowFooter">
		<div class="modal-footer">
			<button class="btn btn-primary"> create &amp; Add</button>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		</div>
	</div>

<?php 