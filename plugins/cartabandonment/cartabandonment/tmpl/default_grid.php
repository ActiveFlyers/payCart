<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		garima agal
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

JHtml::_('behavior.formvalidation');
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
<div class="row-fluid">
	<!-- ADMIN MENU -->
	<div class="span2">
		<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=cartabandonment'); 
		?>
	</div>

<!--<div class="span10">
   <div class="row-fluid">
 	  <div class="span6 accordion" id="notificationAccordion">
		<div class="accordion-group">
		    <div class="accordion-heading">
		      <a class="accordion-toggle" data-toggle="collapse" data-parent="#notificationAccordion" href="#collapse1">
		        <?php echo JText::_('1st')?>
		      </a>
	    	</div>
            <div id="collapse1" class="accordion-body collapse in"> 
                  <div class="accordion-inner">
                   		<form action="<?php //echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="pc-form-validate">
							 <fieldset class="form">
								<button onclick="Joomla.submitbutton('apply')" class="btn btn-small btn-success pull-right">
									<span class="icon-apply icon-white"></span>
									Save
								</button>
								
                                <?php //$field = $form1->getField('when_to_email') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php //echo $field->label; ?> </div>
                                        <div class="controls"><?php //echo $field->input; ?></div>
                                </div>
                                
                                <?php //$field = $form1->getField('to') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>     
                            
                                <?php //$field = $form1->getField('bcc') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            
                            
                                <?php //$field = $form1->getField('subject') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>

                                <?php //$field = $form1->getField('body') ?>					
                                <div class="control-group">
                                        <div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> <span class="muted">( <?php echo JText::_('COM_PAYCART_ADMIN_NOTIFICATION_BODY_USE_HTML');?> ) </span></div>
                                        <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                              </fieldset>
                              <input type="hidden" name="task" value="save" />
							  <?php //echo $form1->getInput('cartabandonment_id'); ?>
			                  <?php //echo $form1->getInput('cartabandonment_lang_id'); ?>
			                  <?php //echo $form1->getInput('lang_code'); ?>
                         </form>
                   </div>
             </div>
        </div>                                    
                       
                 
              </div>
              <div class="span6 pc-notification-tokens">
                      <div class="offset1 control-group">
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
</div>

--><div class="span10">
		<!--  LANGUAGE SWITCHER --> 
		<?php		
		if(PAYCART_MULTILINGUAL){
			$displayData = new stdClass();
			$displayData->uri  = $uri;
			echo Rb_HelperTemplate::renderLayout('paycart_language_switcher', $displayData);
		}		
		?>
		
		<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">		
			<table class="table table-striped ">					
				<!-- TABLE HEADER START -->
                <thead>
                	<tr>
                		<th width="1%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
                		<th class="hidden-phone"><?php echo PaycartHtml::_('grid.sort', 'COM_PAYCART_ADMIN_ID', 'cartabandonment_id', $filter_order_Dir, $filter_order);?></th>
                		<th><?php echo PaycartHtml::_('grid.sort', 'COM_PAYCART_ADMIN_TITLE', 'title', $filter_order_Dir, $filter_order);?></th>
                		<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_STATUS", 'published', $filter_order_Dir, $filter_order);?></th>					</tr>
				<!-- TABLE HEADER END -->
                </thead>
					
				<tbody>
				<!-- TABLE BODY START -->
					<?php $count= $limitstart;
					$cbCount = 0;
					foreach ($records as $record):?>
						<tr class="<?php echo "row".$count%2; ?>">								
							<th><?php echo PaycartHtml::_('grid.id', $cbCount, $record->cartabandonment_id ); ?></th>				
							<td class="hidden-phone"> <?php echo $record->cartabandonment_id;?></td>
							<td>
								<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cartabandonment&task=edit&cartabandonment_id='.$record->cartabandonment_id)?>" class="edit-abandonment" >
                                	<?php  echo $record->title   ?>
                                </a>
                            </td>
                            <td><?php echo PaycartHtml::_("rb_html.boolean.grid", $record, 'published', $cbCount, 'tick.png', 'publish_x.png', '', $langPrefix='COM_PAYCART');?></td>
                        </tr>
					<?php $count++;?>
					<?php $cbCount++;?>
					<?php endforeach;?>
				<!-- TABLE BODY END -->
				</tbody>
					
				<tfoot>
					<tr>
						<td colspan="5"><?php echo $pagination->getListFooter(); ?></td>
					</tr>
				</tfoot>
			</table>
			
			<input type="hidden" name="filter_order" value="<?php echo $filter_order;?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir;?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>			
	</div>
</div>
<?php 