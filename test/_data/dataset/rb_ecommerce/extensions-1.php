<?php

// get schema of extension table 
$tmpl_extension = include dirname(dirname(__FILE__)).'/joomla/tmpl_extensions.php';
$rows_extension = Array();

$rows_extension[]=  array_replace($tmpl_extension,
									Array(
					//				  'extension_id'		=> '' ,
									  'name'	 			=> 'plg_rb_ecommerceprocessor_stripe' ,
									  'type' 				=> 'plugin' ,
									  'element' 			=> 'stripe' ,
									  'folder' 				=> 'rb_ecommerceprocessor' ,
									  'client_id' 			=> '0' ,
									  'enabled' 			=> '1' ,
									  'access' 				=> '1' ,
									  'protected'			=> '0' ,
									  'manifest_cache' 		=> '{"name":"plg_rb_ecommerceprocessor_stripe","type":"plugin","creationDate":"Nov 2012","author":"Team PayInvoice","copyright":"2009-14 Ready Bytes Software Labs Pvt. Ltd.","authorEmail":"support+payinvoice@readybytes.in","authorUrl":"http:\/\/www.readybytes.net\/payinvoice.html","version":"0.9.0","description":"PLG_RB_ECOMMERCEPROCESSOR_STRIPE_DESCRIPTION","group":""}' ,
									  'params' 				=> '{}' ,
									  'custom_data' 		=> '' ,
									  'system_data' 		=> '' ,
									  'checked_out'			=> '0' ,
									  'checked_out_time' 	=> '0000-00-00 00:00:00',
									  'ordering' 			=> '0' ,
									  'state' 				=> '0'
				  			));
								
return  Array('jos_extensions' 		=> $rows_extension);


