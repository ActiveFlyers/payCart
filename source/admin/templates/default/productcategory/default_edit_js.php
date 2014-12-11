<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>
<script type="text/javascript">	

	(function($){
		$(document).ready(function(){
			<?php if(!empty($error_fields)):?>
				var error_fields = <?php echo json_encode($error_fields);?>;
				for(var field_id in error_fields){
					if(error_fields.hasOwnProperty(field_id) == false){
						continue;
					}
					paycart.formvalidator.handleResponse(false, $('#'+error_fields[field_id]));
				}  

				paycart.formvalidator.scrollToError('#adminForm');
			<?php endif;?>

			// Don't allow to set itself as parent category
			$("#paycart_productcategory_form_parent_id option[value=<?php echo $productCategory->getId()?>]").remove();
			$('#paycart_productcategory_form_parent_id').trigger("liszt:updated");
		});
	})(paycart.jQuery);	
</script>

<?php 