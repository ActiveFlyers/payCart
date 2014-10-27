<?php

/**
* @copyright        Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PAYCART
* @subpackage       Back-end
* @contact          support+paycart@readybytes.in
* @author           rimjhim
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
Rb_HelperTemplate::loadMedia(array('angular'));
?>

<script type="text/javascript">	
    
    Joomla.submitbutton = function(task)
	{
		if (task == 'approved' && !paycart.admin.cart.edit.approved()) {
			return false;
		}
                
        if (task == 'paid' && !paycart.admin.cart.edit.paid()) {
			return false;
		}

		if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};
        
        (function($){
            
            paycart.admin.cart          = {};
            paycart.admin.cart.edit     = {};
            
            paycart.admin.cart.edit.approved = function()
            {
                // validate here, if required then pop-up what it will do
                
                $('.pc-confimbox-ok').attr('onclick', " Joomla.submitform('approved', document.getElementById('adminForm'))" );
                $('.pc-confimbox-body').html('<?php echo JText::_('COM_PAYCART_ADMIN_DESCRIBE_APPROVED_TASK'); ?>');             
                $('.pc-confimbox').modal('toggle');

            };
            
            paycart.admin.cart.edit.paid = function()
            {
                // validate here, if required then pop-up what it will do
                
                $('.pc-confimbox-ok').attr('onclick', " Joomla.submitform('paid', document.getElementById('adminForm'))" );
                $('.pc-confimbox-body').html('<?php echo JText::_('COM_PAYCART_ADMIN_DESCRIBE_PAID_TASK'); ?>');
                $('.pc-confimbox').modal('toggle');
                
            };
                   
        })(paycart.jQuery);
        
</script>
<?php 