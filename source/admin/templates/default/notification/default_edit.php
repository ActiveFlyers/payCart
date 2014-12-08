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
<!-- LANGUAGE SWITCHER -->
<?php 
	if(PAYCART_MULTILINGUAL){
		$lang_code = PaycartFactory::getPCCurrentLanguageCode();
		$flag = '<span class="pull-left pc-language">'.PaycartHtmlLanguageflag::getFlag($lang_code).' &nbsp; '.'</span>';
	}
	else{
		$flag = '';
	}
?>
<style>
    .pc-notification .left-config {
        max-height: 400px; 
        overflow-y: auto;
    }
    
</style>

<div id="rbWindowTitle">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo JText::_('COM_PAYCART_ADMIN_EDIT_NOTIFICATION_TEMPLATE'); ?></h3>
	</div>
</div>

<div id="rbWindowBody">
	<div class="modal-body">
            
            <div class="pc-notification " class="pc-form-validate" >
                    <!-- CONTENT START -->	
                    <div class="row-fluid">
                        
                     <div class="span8 left-config">
                        <form action="<?php echo $uri; ?>" method="post" name="adminForm" id="paycart_notification_form" class="pc-form-validate">

                            
                            <div class="row-fluid">
                                <?php $field = $form->getField('to') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            </div>
                            
                            
                            <div class="row-fluid">
                                <?php $field = $form->getField('cc') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            </div>
                            
                            
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
                                        <div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            </div>

                            <div class="row-fluid">
                                <?php $field = $form->getField('body') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            </div>

                            <input type="hidden" name="task" value="save" />
                            <input type='hidden' name='id' value='<?php echo $record_id;?>' />
                            <input type="hidden" name="task" value="" />
                            <?php echo $form->getInput('notification_lang_id'); ?>
                            <?php echo $form->getInput('lang_code'); ?>
                        </form>
                        </div>
                        
                        <div class="span4">
                            <div class="control-group">
                                <div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_TOKEN_LIST'); ?> </div>
                                <div class="controls ">
                                    <select size="20" ondblclick=" paycart.token.insert_at_cursor(this.value);">
                                     <?php   
                                          foreach ($available_token_list as $token_type => $tokens) {
                                        ?>
                                        <optgroup label="<?php echo $token_type; ?>">
                                        <?php
                                            foreach ($tokens as $value) {
                                              echo "<option value='[[$value]]'> $value </option>";  
                                            }
                                          ?>
                                        </optgroup>
                                          <?php  
                                         }
                                       ?>
                                    </select>
                                </div>
                                <span>
                                    <small>
                                      <i> <?php echo JText::_('COM_PAYCART_ADMIN_SELECT_TOKEN_MESSAGE')  ?></i>
                                    </small>
                                </span>
                            </div>
                            
                        </div>
                        
                    </div>    
                </div>	
                    
		 


	</div>
</div>

<div id="rbWindowFooter">
	<div class="modal-footer">
            
<!--                <button class="btn pull-left " onClick="paycart.admin.notification.preview.go();"> 
			<?php //echo JText::_('COM_PAYCART_ADMIN_PREVIEW'); ?> 
                </button>
            
		<button class="btn pull-left" data-dismiss="modal" aria-hidden="true" >
                       <?php    // echo JText::_('COM_PAYCART_ADMIN_NOTIFICATION_TEST'); ?> 
                </button>
            -->
            
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
                // keyup mousedown mousemove mouseup
                // bind element
               $('.paycart-token-container').on("input click", function () {
                   
                   paycart.token.set_cursor_position($(this));  
               });
               
               // default element bind
               paycart.token.set_cursor_position($('#paycart_form_to'));
                
	})(paycart.jQuery);
	
</script>

<?php 
