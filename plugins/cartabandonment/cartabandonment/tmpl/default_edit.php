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

<!-- CONTENT START -->	
<div class="row-fluid">
	<div class="span2">
		<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=cartabandonment'); 
		?>
	</div>
	<div class="span10 left-config">
		<div class="span7">
         	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="pc-form-validate">
							  <?php $field = $form->getField('title') ?>					
			                  <div class="control-group">
			                       <div class="control-label"><?php echo $field->label; ?> </div>
			                       <div class="controls"><?php echo $field->input; ?></div>
			                  </div>
							
			                  <?php $field = $form->getField('to') ?>					
			                  <div class="control-group">
			                       <div class="control-label"><?php echo $field->label; ?> </div>
			                       <div class="controls"><?php echo $field->input; ?></div>
			                  </div>
                            
                            
				               <?php $field = $form->getField('when_to_email') ?>					
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
                                        <div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> <span class="muted">( <?php echo JText::_('COM_PAYCART_ADMIN_NOTIFICATION_BODY_USE_HTML');?> ) </span></div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>

                            <input type="hidden" name="task" value="save" />
                            <input type='hidden' name='id' value='<?php echo $record_id;?>' />
                            <?php echo $form->getInput('cartabandonment_lang_id'); ?>
                            <?php echo $form->getInput('lang_code'); ?>
                        </form>               
                        </div>
                        
                      <div class="span5 pc-notification-tokenscontrol-group">
                            <div class="control-label"><h3><?php echo JText::_('COM_PAYCART_ADMIN_TOKEN_LIST'); ?> </h3></div>
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
	   paycart.token.set_cursor_position($('#paycart_cartabandonment_form_to'));
	    
	})(paycart.jQuery);
</script>
<?php 