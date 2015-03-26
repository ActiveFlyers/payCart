<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );
?>
<script>
paycart.admin.group = {};

// will be used to maintain the counter of grouprule added
paycart.admin.group.ruleCounter = <?php echo $ruleCounter;?>;

(function($){	
	paycart.admin.group.addrule = function(ruleType){
		var ruleClass = $('#paycart-grouprule-list	').val();		
		var url = 'index.php?option=com_paycart&view=group&task=addRule&ruleType='+ruleType+'&ruleClass='+ruleClass+'&counter='+paycart.admin.group.ruleCounter;

		//@PCTODO : add one more parametere in url to escape from caching of browser

		paycart.ajax.go(url, {});
	};	

	$(document).ready(function(){
		<?php foreach($ruleScripts as $script):?>
			<?php echo $script;?>
		<?php endforeach;?>
			  
		$(document).on('change', '[data-pc-selector="pc-option-manipulator"]', function(){
			var id = $(this).attr('id');
			if(!$(this).val() || $(this).val() == 'any'){
				$('[data-pc-option-manipulator="'+id+'"]').addClass('hide');
			}
			else{
				$('[data-pc-option-manipulator="'+id+'"]').removeClass('hide');
			}		  
		});
	});
})(paycart.jQuery);
</script>

<?php 
	