<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );
echo $this->loadTemplate('js');
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

.pc-notification-tokens li{
	cursor: pointer;
}
</style>
<!--<style>
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
            -->
<div class="row-fluid"> 
    <div class="span2">
		<?php $helper = PaycartFactory::getHelper('adminmenu');?>			
		<?php echo $helper->render('index.php?option=com_paycart&view=notification');?> 
	</div>
    <div class="pc-notification span10">
        <form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="pc-form-validate">
        	       
			<fieldset class="form">
			   <div class="row-fluid">
			   	  <div class="span6">
			   	  <?php foreach ($form->getFieldset('params') as $field):?>
						<h4 class="pull-right">
						    <input type='hidden' value='0' name='paycart_notification_form[params][send_same_copy]'/>						    
							<?php echo $field->input.' '.JText::_('COM_PAYCART_ADMIN_NOTIFICATION_SEND_SAME_COPY')?>
						</h4>
				  <?php endforeach;?>
				  </div>
				  
			   </div>
			   <div class="row-fluid">
			 	  <div class="span6 accordion" id="notificationAccordion">
					<div class="accordion-group">
					    <div class="accordion-heading">
					      <a class="accordion-toggle" data-toggle="collapse" data-parent="#notificationAccordion" href="#collapseCustomer">
					        <strong><?php echo JText::_('COM_PAYCART_ADMIN_NOTIFICATION_CUSTOMER_CONFIGURATION')?></strong>
					        <span class="pull-right"><i class="fa fa-chevron-down"></i></span>
					      </a>
				    	</div>
                		<div id="collapseCustomer" class="accordion-body collapse in"> 
                		  <div class="accordion-inner">
                                <?php $field = $form->getField('to') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            
                                <?php $field = $form->getField('cc') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>                     
                            
                                <?php $field = $form->getField('bcc') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            
                            
                                <?php $field = $form->getField('subject') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>

                                <?php $field = $form->getField('body') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?>                                        	
                                        </div>
                                        <div class="well well-small">
												<a href="javascript:void(0);" data-pc-selector="notification_template">
														<i class="fa fa-hand-o-up fa-2x"></i>
														<?php echo JText::_('COM_PAYCART_ADMIN_NOTIFICATION_USE_DEFAULT_EMAIL_TEMPLATE')?>
												</a>
										</div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                          </div>
                        </div>
                       </div>                                    
                       
                       <div class="accordion-group" data-pc-selector="admin-notification-config">
					    	<div class="accordion-heading">
					      		<a class="accordion-toggle" data-toggle="collapse" data-parent="#notificationAccordion" href="#collapseAdmin">
					       			 <strong><?php echo JText::_('COM_PAYCART_ADMIN_NOTIFICATION_ADMIN_CONFIGURATION')?></strong>
					       			 <span class="pull-right"><i class="fa fa-chevron-up"></i></span>
					      		</a>
				    		</div>
                			<div id="collapseAdmin" class="accordion-body collapse"> 
                		  		<div class="accordion-inner">  
                                	<?php $field = $form->getField('admin_subject') ?>					
                                	<div class="control-group">
                                        <div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> <span class="muted">( <?php echo JText::_('COM_PAYCART_ADMIN_NOTIFICATION_BODY_USE_HTML');?> ) </span></div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                	</div>
                            
                               		 <?php $field = $form->getField('admin_body') ?>					
                                	<div class="control-group">
                                        <div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> <span class="muted">( <?php echo JText::_('COM_PAYCART_ADMIN_NOTIFICATION_BODY_USE_HTML');?> ) </span></div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                               		</div>
                        	</div>
              		 	</div>
                    </div>
					<input type="hidden" name="task" value="save" />
					<?php  echo $form->getInput('notification_id'); ?>
                    <?php echo $form->getInput('notification_lang_id'); ?>
                    <?php echo $form->getInput('lang_code'); ?>
              </div>
              <div class="span6 pc-notification-tokens">
                      <div class="offset1 control-group">
                            <div class="control-label"><h3><?php echo JText::_('COM_PAYCART_ADMIN_TOKEN_LIST'); ?> <small> (<?php echo JText::_('COM_PAYCART_ADMIN_SELECT_TOKEN_MESSAGE')?>)</small> </h3></div>
                            <div class="controls ">
                                  <ul class="unstyled">
                                   <?php   
                                       foreach ($available_token_list as $token_type => $tokens) {
                                    ?>
                                    <br><strong><?php echo $token_type; ?></strong>
                                    <?php
                                       foreach ($tokens as $value) {
                                           echo "<li data-pc-selector='[[$value]]' ondblclick='paycart.token.insert_at_cursor(this)'>$value</li>";  
                                       }
                                     ?>
                                     <?php  
                                      }
                                    ?>
                                    </ul>
                             </div>
                    </div>
                </div>
               </div> 
            </fieldset>
        </form>
   </div>
</div>

<script>
	(function($) {
                // keyup mousedown mousemove mouseup
                // bind element
               $('.paycart-token-container').on("input click", function () {
            	   inFocus = true;
                   paycart.token.set_cursor_position($(this));  
               });

               //set inFocus to false, if we are in iframe 
               //as normal method won't work in case of joomla editor
               $(window).load(function(){
                	$('iframe').contents().find("body").on('click', function(event) { inFocus = false; });
                });               
               
               // default element bind
               paycart.token.set_cursor_position($('#paycart_notification_form_to'));
                
	})(paycart.jQuery);
	
</script>
<?php 