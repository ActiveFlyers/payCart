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


<div id="rbWindowTitle">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo JText::_('COM_PAYCART_EDIT_NOTIFICATION_TEMPLATE'); ?></h3>
	</div>
</div>

<div id="rbWindowBody">
	<div class="modal-body">
            
            <div class="pc-notification row-fluid" class="rb-validate-form" >
                    <!-- CONTENT START -->		
                    <div class="row-fluid">
                        <form action="<?php echo $uri; ?>" method="post" name="adminForm" id="paycart_notification_form" class="rb-validate-form">

                            <div class="row-fluid">
                                <?php $field = $form->getField('bcc') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            </div>
                            
                            
                            <div class="row-fluid">
                                <?php $field = $form->getField('subject') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            </div>

                            <div class="row-fluid">
                                <?php $field = $form->getField('body') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            </div>

                            <input type="hidden" name="task" value="save" />
                            <input type='hidden' name='id' value='<?php echo $record_id;?>' />
                            <input type="hidden" name="task" value="" />
                        </form>
                    </div>    
                </div>	
                    
		 


	</div>
</div>

<div id="rbWindowFooter">
	<div class="modal-footer">
            
                <button class="btn pull-left " onClick="paycart.admin.notification.preview.go();"> 
			<?php echo JText::_('COM_PAYCART_ADMIN_PREVIEW'); ?> 
                </button>
		<button class="btn pull-left" data-dismiss="modal" aria-hidden="true" >
                       <?php echo JText::_('COM_PAYCART_ADMIN_NOTIFICATION_TEST'); ?> 
                </button>
            
            
		<button class="btn btn-primary " onClick="paycart.admin.notification.update.go();"> 
			<?php echo JText::_('COM_PAYCART_ADMIN_SAVE'); ?> 
                </button>
		<button class="btn" data-dismiss="modal" aria-hidden="true" >
                       <?php echo JText::_('COM_PAYCART_ADMIN_CANCEL'); ?> 
                </button>
	</div>
</div>

	
<script>
	(function($) {
		
	})(paycart.jQuery);
	
</script>
<?php  
	