<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * edit screen of configuration
 * 
 * @since 1.0.0
 *  
 * @author Gaurav
 */

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="myModalLabel"><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_SUPPORTED_LANGUAGE');?></h3>
</div>

<div class="modal-body">
	<div class="center">
		<span><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_SUPPORTED_LANGUAGE_WAIT');?></span>
		<p> &nbsp; </p>
		<div class="row-fluid">
			<div class="span2">
			</div>
			<div class="span8">
			    <div class="progress progress-striped active">
		    		<div class="bar" style="width: 100%;"></div>
		    	</div>
		    </div>
		    <div class="span2">
		    </div>
    	</div>
	</div>	
</div>

<div class="modal-footer text-center">
													
</div>

<script>
	(function($){		
		setTimeout(function(){
			paycart.admin.config.updateSupportedLanguage.do(<?php echo json_encode($languages);?>);
		}, 3000);
	})(paycart.jQuery);
</script>
<?php 