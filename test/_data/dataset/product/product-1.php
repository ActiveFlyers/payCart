<?php

// get schema of attribute table 
list($tmpl,$tmpl_lang) = include 'tmpl.php';

$row 	 = Array();
$rowLang = Array();

$row[] 	= 	array_replace($tmpl, Array( 'product_id'=>1,
									'sku'=>'product-1',
									'created_date'=>'2013-09-19 09:47:12',
									'modified_date'=>'2013-09-19 10:33:07',
									'ordering'=>1,
									'variation_of'=>1
									));
							
$rowLang[] = array_replace($tmpl_lang, Array('product_id'=>1,'product_lang_id'=>1,
									'title' => 'Product-1',
									'alias'=>'Product-1',
									'lang_code'=>'en-GB'
									));	
//									
//$rowLang[] = array_replace($tmpl_lang, Array('product_id'=>1,'product_lang_id'=>2,
//									'title' => 'Pro-1',
//									'alias'=>'pro-1',
//									'lang_code'=>'fr-FR'
//									));	

$row[] 	= 	array_replace($tmpl, Array(  'product_id'=>2,
									'sku'=>'product-2',
									'created_date'=>'2013-09-19 09:49:36',
									'modified_date'=>'2013-09-19 10:34:15',
									'ordering'=>2,
									'variation_of'=>2
									));

$rowLang[] = array_replace($tmpl_lang, Array('product_id'=>2,'product_lang_id'=>3,
									'title'=>'Product-2',
									'alias'=>'product-2',
									));	
									
$row[] 	= 	array_replace($tmpl, Array(  'product_id'=>3,
									'sku'=>'product-3',
									'price' => '200',
									'created_date'=>'2013-09-19 09:49:36',
									'modified_date'=>'2013-09-19 10:34:15',
									'ordering'=>3,
									'variation_of'=>3
									));
									
$rowLang[] = array_replace($tmpl_lang, Array('product_id'=>3,'product_lang_id'=>4,
									'title'=>'Product-3',
									'alias'=>'product-3',
									));	

$row[] 	= 	array_replace($tmpl, Array(  'product_id'=>4,
									'sku'=>'product-4',
									'price' => '250',
									'created_date'=>'2013-09-19 09:49:36',
									'modified_date'=>'2013-09-19 10:34:15',
									'ordering'=>4,
									'variation_of'=>4
									));				

$rowLang[] = array_replace($tmpl_lang, Array('product_id'=>4,'product_lang_id'=>5,
									'title'=>'Product-4',
									'alias'=>'product-4',
									));	

return  Array('jos_paycart_product' => $row,
			  'jos_paycart_product_lang' => $rowLang );
	