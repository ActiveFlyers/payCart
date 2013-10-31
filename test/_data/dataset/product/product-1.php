<?php


// get schema of attribute table 
$tmpl = include 'tmpl.php';

$row 	=	Array();

$row[] 	= 	array_replace($tmpl, Array( 
									'title'=>'Product-1',
									'alias'=>'product-1',
									'sku'=>'product-1',
									'publish_up'=>'2013-09-19 09:46:09',
									'publish_down'=>'0000-00-00 00:00:00',
									'created_date'=>'2013-09-19 09:47:12',
									'modified_date'=>'2013-09-19 10:33:07',
									'created_by'=>489,
									'ordering'=>1
									));

$row[] 	= 	array_replace($tmpl, Array(  
									'title'=>'product-2',
									'alias'=>'product-2',
									'sku'=>'product-2',
									'publish_up'=>'2013-09-19 09:49:17',
									'publish_down'=>'0000-00-00 00:00:00',
									'created_date'=>'2013-09-19 09:49:36',
									'modified_date'=>'2013-09-19 10:34:15',
									'created_by'=>489,
									'ordering'=>2
									));
									
$row[] 	= 	array_replace($tmpl, Array(  
									'title'=>'Product-3',
									'alias'=>'Product-3',
									'sku'=>'Product-3',
									'amount' => '200',
									'publish_up'=>'2013-09-19 09:49:17',
									'publish_down'=>'0000-00-00 00:00:00',
									'created_date'=>'2013-09-19 09:49:36',
									'modified_date'=>'2013-09-19 10:34:15',
									'created_by'=>489,
									'ordering'=>3
									));	

$row[] 	= 	array_replace($tmpl, Array(  
									'title'=>'Product-4',
									'alias'=>'Product-4',
									'sku'=>'Product-4',
									'amount' => '250',
									'publish_up'=>'2013-09-19 09:49:17',
									'publish_down'=>'0000-00-00 00:00:00',
									'created_date'=>'2013-09-19 09:49:36',
									'modified_date'=>'2013-09-19 10:34:15',
									'created_by'=>489,
									'ordering'=>4
									));										

return  Array('jos_paycart_product' => $row );
	