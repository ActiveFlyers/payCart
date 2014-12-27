<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

echo $this->loadTemplate('css');
echo $this->loadTemplate('js');
?>
<div class='pc-account-wrapper row-fluid clearfix'> 
	<div id="pc-account" class ='pc-account span12 clearfix' >
		<div class="row-fluid">				
			<ul class="nav nav-tabs">
				<li class="active"><a href="#pc-account-login-form" data-toggle="tab"><?php echo JText::_('COM_PAYCART_LOGIN');?></a></li>
				<li class=""><a href="#pc-account-guest-form" data-toggle="tab"><?php echo JText::_('COM_PAYCART_ACCOUNT_TRACK_ORDER_AS_GUEST');?></a></li>
			</ul>	
			
			<div class="tab-content">
				<form id="pc-account-login-form" method="POST" action="/login/" novalidate="novalidate" class="form tab-pane active pc-form-validate">					
					<fieldset>						
						<span id="paycart_account_login" for="paycart_account_login" class="pc-error hide"></span>
						<div class="control-group">
							<div class="control-label">
								<label class="" for="paycart_account_loginform_email">
									<?php echo JText::_('JGLOBAL_USERNAME').' / '.JText::_('JGLOBAL_EMAIL');?>
								</label>
							</div>
							<div class="controls">
								<input type="text" class="input-block-level" id="paycart_account_loginform_email" name="paycart_account_loginform[email]" value="" required="">
								<span for="paycart_account_loginform_email" class="pc-error hide"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<label class="" for="paycart_account_loginform_password">
									<?php echo JText::_('JGLOBAL_PASSWORD');?>
								</label>
							</div>
							<div class="controls">
								<input type="password" class="input-block-level" id="paycart_account_loginform_password" name="paycart_account_loginform[password]" value="" required="">
								<span for="paycart_account_loginform_password" class="pc-error hide"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">								
							</div>
							<div class="controls">
	                              <label>
	                                      <input type="checkbox" name="paycart_account_loginform[remember]" id="paycart_account_loginform_remember"> <?php echo JText::_('JGLOBAL_REMEMBER_ME');?>
	                                  </label>
							</div>
						</div>
	                        
	                        <div class="control-group">
							<div class="control-label">
							</div>
							<div class="controls">
	                         	<button type="submit" onclick="return false;" data-pc-selector="pc-login" class="btn btn-primary btn-block"> <?php echo JText::_('COM_PAYCART_LOGIN');?></button>
	                         </div>
	                        </div>   
	                        <div class="pull-right">
	                        <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset');?>" class=""><?php echo JText::_('COM_PAYCART_FORGOT_PASSWORD');?></a>
	                        </div> 
	                        </fieldset>                     
				</form>
						
				<form id="pc-account-guest-form" method="POST" action="/login/" novalidate="novalidate" class="form tab-pane pc-form-validate">
					<div class="alert alert-success hide" data-ppc-selector="pc-account-guest-form-header"> </div>
					<span id="paycart_account_guest" for="paycart_account_guest" class="pc-error hide"></span>
					<fieldset>
						<div class="control-group">
							<div class="control-label">
								<label class="" for="paycart_account_guestform_email">
									<?php echo JText::_('JGLOBAL_EMAIL');?>
								</label>
							</div>
							<div class="controls">
								<input type="email" class="input-block-level validate-email" id="paycart_account_guestform_email" name="paycart_account_guestform[email]" value="" required="">
								<span for="paycart_account_guestform_email" class="pc-error hide"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_INVALID_EMAIL_ADDRESS');?></span>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<label class="" for="paycart_account_guestform_order_id">
									<?php echo JText::_('COM_PAYCART_ORDER_ID');?>
								</label>
							</div>
							<div class="controls">
								<input type="text" class="input-block-level" id="paycart_account_guestform_order_id" name="paycart_account_guestform[order_id]" value="" required="">
								<span for="paycart_account_guestform_order_id" class="pc-error hide"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_INVALID_ORDER_ID');?></span>
							</div>
						</div>    
                        <div class="control-group">
							<div class="control-label">
							</div>
							<div class="controls">
                         		<button type="submit" onclick="return false;" data-pc-selector="pc-guest" class="btn btn-info btn-block"> <?php echo JText::_('COM_PAYCART_ACCOUNT_ORDER_URL_REQUEST');?></button>
                         	</div>
                        </div>
                	</fieldset>                     
				</form>
			</div>			
		</div>
	</div>
</div>