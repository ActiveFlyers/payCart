<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

echo $processor_config_html;	

?>
<script>
(function($) {
	paycart.formvalidator.initialize('form.pc-form-validate');
})(paycart.jQuery);
</script>
